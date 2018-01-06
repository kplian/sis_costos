--------------- SQL ---------------

CREATE OR REPLACE FUNCTION cos.f_balance_costos_auxiliar (
  p_desde date,
  p_hasta date,
  pa_id_deptos integer [],
  p_nro_nodo numeric,
  p_codigo_orden varchar,
  p_nivel_ini integer,
  p_id_tipo_costo_fk integer,
  p_tipo varchar,
  p_incluir_cierre varchar = 'no'::character varying,
  p_tipo_balance varchar = 'general'::character varying,
  p_id_auxiliares integer [] = NULL::integer[],
  p_id_cuenta integer = NULL::integer,
  p_id_gestion integer = NULL::integer
)
RETURNS integer AS
$body$
DECLARE


v_parametros  		record;
v_registros 		record;
v_reg_aux 			record;
v_nombre_funcion   	text;
v_resp				varchar;
v_nivel				integer;
va_mayor			numeric[];
v_id_gestion  		integer;
va_tipo_cuenta		varchar[];
v_gestion 			integer;
v_sw_force			boolean;

va_suma				numeric[];
va_tipo				varchar[];
v_cont_nro_nodo		integer;
v_mayor				numeric;
v_mayor_mt			numeric;
v_mayor_debe				numeric;
v_mayor_mt_debe				numeric;
v_mayor_haber				numeric;
v_mayor_mt_haber			numeric;
v_suma						numeric;
v_suma_mt					numeric;
v_suma_debe					numeric;
v_suma_mt_debe				numeric;
v_suma_haber				numeric;
v_suma_mt_haber				numeric;
v_registros_aux				record;
v_reg_ctc					record;
v_id_moneda_tri				integer;
v_id_moneda_base			integer;
v_memoria					numeric;
v_reg_pe					record;
v_reg_periodo				record;
v_id_aux					integer;
va_id_auxiliares 			integer[];
va_cbte_cierre				varchar[];

 

BEGIN

    v_nombre_funcion = 'cos.f_balance_costos_auxiliar';
    va_id_auxiliares = p_id_auxiliares;
    v_cont_nro_nodo = p_nro_nodo;
    
    va_cbte_cierre[1] = 'no';
    
    if p_incluir_cierre = 'todos' then
        va_cbte_cierre[2] = 'balance';
        va_cbte_cierre[3] = 'resultado';
     elsif p_incluir_cierre = 'balance' then
        va_cbte_cierre[2] = 'balance';
     ELSIF p_incluir_cierre = 'resultado' then
        va_cbte_cierre[2] = 'resultado';
     end if;
     
      IF p_incluir_cierre = 'solo_cierre' THEN
         
         --sobreexribe la posicion uno ... 
         va_cbte_cierre[1] = 'resultado';
         va_cbte_cierre[2] = 'balance';
     
     END IF;
    
  
     -- raise notice 'N· a registrar .... %',v_cont_nro_nodo;      
     insert  into temp_balance_costos (
                                  id_tipo_costo_fk ,
                                  id_cuenta ,
                                  id_auxiliar,
                                  id_periodo,
                                  codigo,
                                  nombre,
                                  monto,
                                  nivel,
                                  tipo,
                                  nro_nodo	,
                                  codigo_orden
                             )
                        
                        select 
                           p_id_tipo_costo_fk,
                           p_id_cuenta,
                           a.id_auxiliar,
                           c.id_periodo,
                           a.codigo_auxiliar,
                           a.nombre_auxiliar,
                           sum(COALESCE(t.importe_debe_mb,0))- sum(COALESCE(t.importe_haber_mb,0)),
                           p_nivel_ini,
                           'auxiliar',
                           v_cont_nro_nodo,
                           p_codigo_orden||'.'||a.codigo_auxiliar
                        
                          
                        from conta.tint_transaccion t
                        inner join conta.tint_comprobante c on t.id_int_comprobante = c.id_int_comprobante
                        inner join conta.tauxiliar a on t.id_auxiliar = a.id_auxiliar and a.id_auxiliar =ANY(va_id_auxiliares)
                        where 
                            t.id_cuenta = p_id_cuenta AND 
                            t.estado_reg = 'activo'  AND 
                            c.estado_reg = 'validado' AND
                            c.cbte_cierre = ANY(va_cbte_cierre) AND
                            c.fecha::date BETWEEN  p_desde  and p_hasta  AND
                            c.id_depto::integer = ANY(pa_id_deptos)  
                        group by 
                            a.id_auxiliar,
                            a.codigo_auxiliar,
                            a.nombre_auxiliar,
                            c.id_periodo;            
                                                                            
                    
  
     RETURN v_cont_nro_nodo +1;
    
EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;