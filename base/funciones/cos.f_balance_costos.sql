--------------- SQL ---------------

CREATE OR REPLACE FUNCTION cos.f_balance_costos (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS SETOF record AS
$body$
DECLARE


v_parametros  		record;
v_nombre_funcion   	text;
v_resp				varchar;
v_sw 				integer;
v_sw2 				integer;
v_count 			integer;
v_consulta 			varchar;
v_registros  		record;  -- PARA ALMACENAR EL CONJUNTO DE DATOS RESULTADO DEL SELECT
v_i 				integer;
v_nivel_inicial		integer;
va_total 			numeric[];
v_total 			numeric;
v_total_mt 			numeric;
v_tipo		        varchar;
v_incluir_cierre	varchar;
v_incluir_sinmov	varchar;
v_cont_nro_nodo		integer;
va_id_tipo_cc 		integer[];
v_nro_nodo			integer;
v_id_moneda_base	integer;
v_id_moneda_tri		integer;
v_id_gestion		integer;
v_total_nodos		integer;
va_id_deptos		integer[];
 

BEGIN
     
     v_nombre_funcion = 'cos.f_balance_costos';
     v_parametros = pxp.f_get_record(p_tabla);
    
    
    /*********************************   
     #TRANSACCION:    'COS_BALCOS_SEL'
     #DESCRIPCION:     Listado de Costos y balances sgun configuracion
     #AUTOR:           rensi arteaga copari  kplian
     #FECHA:            03-08-2017
    ***********************************/

	IF(p_transaccion='COS_BALCOS_SEL')then
    
  
    
        if pxp.f_existe_parametro(p_tabla,'tipo') then
          v_tipo = v_parametros.tipo;
        end if;
        
        if pxp.f_existe_parametro(p_tabla,'incluir_cierre') then
          v_incluir_cierre = v_parametros.incluir_cierre;
        end if;
        
        
        v_incluir_sinmov = 'no';
        if pxp.f_existe_parametro(p_tabla,'incluir_sinmov') then
          v_incluir_sinmov = v_parametros.incluir_sinmov;
        end if;
        
        v_id_moneda_tri  =  param.f_get_moneda_triangulacion();
        v_id_moneda_base =  param.f_get_moneda_base();
        
        
      -- 1) Crea una tabla temporal con los datos que se utilizaran 
       CREATE TEMPORARY TABLE temp_balance_costos (
                                      id_tipo_costo integer,
                                      id_tipo_costo_fk integer,
                                      id_cuenta integer,
                                      id_auxiliar integer,
                                      id_periodo integer,
                                      codigo varchar,
                                      nombre varchar,
                                      periodo  integer,
                                      monto numeric,
                                      nivel integer,
                                      tipo varchar,
                                      nro_nodo	integer,
                                      codigo_orden   varchar
                                      
                                 ) ON COMMIT DROP;
    
          
      --llamada recusiva para llenar la tabla
      v_nivel_inicial = 1;
      
        --obtener gestion para la fecha inicial
    select
       p.id_gestion
    into
      v_id_gestion
    from param.tperiodo p
    where 
       v_parametros.desde::Date BETWEEN p.fecha_ini::Date and p.fecha_fin::date 
    
    and p.estado_reg = 'activo';
    
    
    IF v_id_gestion is null THEN
       raise exception 'no se encontro gestion para la fecha %',v_parametros.desde;
    END IF;
    
    
    va_id_deptos = string_to_array(v_parametros.id_deptos,',')::INTEGER[];
    
     
      
      v_total_nodos =  cos.f_balance_costos_recursivo(
                                                  v_parametros.desde, 
                                                  v_parametros.hasta, 
                                                  va_id_deptos,
                                                  1,--nro de nodo 
                                                  '', --codigo_orden  
                                                  1,  --  nivel                                           
                                                  NULL::integer,  --  id_tipo_cc INICIAL  
                                                  v_tipo,
                                                  v_incluir_cierre,
                                                  v_parametros.tipo_balance,
                                                  v_id_gestion
                                                  );
                                                  
                                    
    
       
      raise notice '------------------------------------------------> total %', v_total_nodos;
      
      
      
      v_consulta = 'SELECT                                   
                                t.id_tipo_costo ,
                                t.id_tipo_costo_fk ,
                                t.id_cuenta ,
                                t.id_auxiliar ,
                                t.id_periodo ,
                                t.codigo ,
                                t.nombre ,
                                p.periodo  ,
                                t.monto ,
                                t.nivel ,
                                t.tipo ,
                                t.nro_nodo	,
                                codigo_orden
                        FROM temp_balance_costos t
                        inner join param.tperiodo p on p.id_periodo =  t.id_periodo ';
                        
       
      
                                           
                       
       IF v_incluir_sinmov != 'no' THEN                          
          --v_consulta = v_consulta|| ' WHERE (monto != 0 and monto_mt !=0)';
       END IF; 
       
       
       v_consulta = v_consulta|| ' order by  nro_nodo, periodo';                  
         
       
       raise notice 'notice.. %',v_consulta;
       
       FOR v_registros in EXECUTE(v_consulta) LOOP
                   RETURN NEXT v_registros;
       END LOOP;
       
     
       
ELSE
   raise exception  'no se encontro el códidigo  de transacción:  %',p_transaccion;      

END IF;

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
COST 100 ROWS 1000;