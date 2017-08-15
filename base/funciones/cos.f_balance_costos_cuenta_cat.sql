--------------- SQL ---------------

CREATE OR REPLACE FUNCTION cos.f_balance_costos_cuenta_cat (
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
v_cont_nro_nodo_temp		integer;
v_codigo_orden_aux			varchar;
 

BEGIN

    v_nombre_funcion = 'cos.f_balance_costos_cuenta_cat';    
    
    v_cont_nro_nodo = p_nro_nodo;   
    va_id_auxiliares = p_id_auxiliares;
    
    v_codigo_orden_aux = p_codigo_orden;
    
    
    
    v_cont_nro_nodo_temp  = cos.f_balance_costos_auxiliar_cat(p_desde, 
                                           p_hasta, 
                                           pa_id_deptos, 
                                           v_cont_nro_nodo + 1,
                                           v_codigo_orden_aux, 
                                           p_nivel_ini +1, 
                                           p_id_tipo_costo_fk, 
                                           p_tipo, 
                                           p_incluir_cierre, 
                                           p_tipo_balance, 
                                           p_id_auxiliares, 
                                           p_id_cuenta,
                                           p_id_gestion);
      
      --raise notice 'N· a registrar .... %',v_cont_nro_nodo;                                          
      insert  into temp_balance_costos (
                                id_tipo_costo_fk ,
                                id_cuenta ,
                                codigo ,
                                nombre ,
                                monto ,
                                nivel ,
                                tipo ,
                                nro_nodo,
                                codigo_orden	,
                                id_cp_actividad ,
                                id_categoria_programatica 
                           )
      
      SELECT
         p_id_tipo_costo_fk,
         c.id_cuenta,         
         c.nro_cuenta,
         c.nombre_cuenta,
         sum(monto),
         p_nivel_ini,
         'cuenta',
         v_cont_nro_nodo,
         v_codigo_orden_aux,
         t.id_cp_actividad ,
         t.id_categoria_programatica 
         
      FROM    temp_balance_costos  t
      inner join conta.tcuenta c on c.id_cuenta = t.id_cuenta
      WHERE   c.id_cuenta  = p_id_cuenta
             and  tipo = 'auxiliar' 
      group by
         t.id_tipo_costo_fk,
         c.id_cuenta,
         c.nro_cuenta,
         c.nombre_cuenta,
         t.id_cp_actividad ,
         t.id_categoria_programatica;                             
                                               
    RETURN v_cont_nro_nodo_temp + 1;

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