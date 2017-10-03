--------------- SQL ---------------

CREATE OR REPLACE FUNCTION cos.f_balance_costos_recursivo (
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
v_cont_nro_nodo_temp		integer;
v_cont_nro_nodo_aux		integer;
v_codigo_orden_aux		varchar;
 

BEGIN

    v_nombre_funcion = 'cos.f_balance_costos_recursivo';
   
    v_sw_force = FALSE;
    va_tipo = string_to_array(p_tipo,',');
    
    
    
    
    
    --arma array de tipos de cuenta
    va_tipo_cuenta = string_to_array(p_tipo,',');
    
    -- incremetmaos el nivel
    v_nivel = p_nivel_ini +1;
    
    v_cont_nro_nodo = p_nro_nodo ;
   
                
     -- FOR listado de cuenta basicas de la gestion 
    FOR  v_registros in (
                                select    
                                           c.id_tipo_costo,
                                           c.codigo,
                                           c.nombre,                                         
                                           c.id_tipo_costo_fk,
                                           c.sw_trans
                                  from cos.ttipo_costo  c
                                  where   CASE 
                                           WHEN p_id_tipo_costo_fk is null THEN
                                               c.id_tipo_costo_fk is null
                                            ELSE
                                                c.id_tipo_costo_fk = p_id_tipo_costo_fk 
                                            END  
                                        and c.estado_reg = 'activo')   LOOP
                       
                         -- llamada recursiva del balance general
                         v_cont_nro_nodo_aux = v_cont_nro_nodo;
                         
                         v_codigo_orden_aux = p_codigo_orden||'.'||v_registros.codigo;
                             
                         IF  v_registros.sw_trans = 'movimiento' THEN
                         
                              
                              -- listar todas las cuentas relacioandas
                              FOR v_reg_ctc in (
                              					select
                                                     cue.id_cuenta,
                                                     tct.id_auxiliares,
                                                     cue.nro_cuenta,
                                                     cue.nombre_cuenta
                                                from cos.ttipo_costo_cuenta tct
                                                inner join conta.tcuenta cue 
                                                                on      cue.nro_cuenta = tct.codigo_cuenta 
                                                                   and cue.id_gestion = p_id_gestion 
                                                                   and cue.estado_reg = 'activo'
                                                where tct.id_tipo_costo = v_registros.id_tipo_costo) LOOP
                              
                              
                                   v_cont_nro_nodo_aux = v_cont_nro_nodo_aux + 1;
                                   
                                   
                                   
                                   v_cont_nro_nodo_temp =  cos.f_balance_costos_cuenta(
                                      											p_desde, 
                                      											p_hasta, 
                                                                                pa_id_deptos, 
                                                                                v_cont_nro_nodo_aux +1,
                                                                                v_codigo_orden_aux||'.'||v_reg_ctc.id_cuenta::varchar, 
                                                                                v_nivel, 
                                                                                v_registros.id_tipo_costo, 
                                                                                p_tipo, 
                                                                                p_incluir_cierre, 
                                                                                p_tipo_balance, 
                                                                                v_reg_ctc.id_auxiliares, 
                                                                                v_reg_ctc.id_cuenta, 
                                                                                p_id_gestion); 
                                                                                
                                                                                
                                  v_cont_nro_nodo_aux = v_cont_nro_nodo_temp;
                                 
                              END LOOP;
                              
                              
                                     
                        
                          ELSE 
                          
                             v_cont_nro_nodo_aux  =  cos.f_balance_costos_recursivo(
                                                    p_desde, 
                                                    p_hasta, 
                                                    pa_id_deptos,
                                                    v_cont_nro_nodo_aux + 1, --nro de nodo
                                                    v_codigo_orden_aux,  
                                                    v_nivel,  --  nivel                                           
                                                    v_registros.id_tipo_costo,  --  id_tipo_costo INICIAL  
                                                    p_tipo,
                                                    p_incluir_cierre,
                                                    p_tipo_balance,
                                                    p_id_gestion
                                                    );
                                                               
                        
                                            
                                                                   
                          END IF;
                          
                         
                      
                         insert  into temp_balance_costos (
                                id_tipo_costo ,
                                id_tipo_costo_fk ,
                                id_cuenta ,
                                id_auxiliar ,
                                id_periodo ,
                                codigo ,
                                nombre ,
                                periodo  ,
                                monto ,
                                nivel ,
                                tipo ,
                                nro_nodo,
                                codigo_orden
                           )
      
                          SELECT
                             v_registros.id_tipo_costo,-- id_tipo_costo
                             p_id_tipo_costo_fk,--id_tipo_costo_fk
                             NULL, --id_cuenta
                             NULL, --id_auxiliar
                             t.id_periodo, --id_periodo
                             v_registros.codigo, --codigo
                             v_registros.nombre, --nombre
                             t.periodo, --periodo
                             sum(monto), -- monto
                             p_nivel_ini, --tipo
                             'tipo_centro', --nro_nodo
                             v_cont_nro_nodo,
                             v_codigo_orden_aux
                          FROM    temp_balance_costos  t
                          WHERE   id_tipo_costo_fk  = v_registros.id_tipo_costo
                                 and  tipo in ('tipo_centro','cuenta') 
                          group by
                             t.id_tipo_costo_fk,
                             t.id_periodo,
                             t.periodo  ;     
                          
                          
                        v_cont_nro_nodo = v_cont_nro_nodo_aux + 1;
                             
     END LOOP;
   
  
   RETURN v_cont_nro_nodo  + 1;


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