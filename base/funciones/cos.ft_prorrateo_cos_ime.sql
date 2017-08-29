CREATE OR REPLACE FUNCTION cos.ft_prorrateo_cos_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		cos.ft_prorrateo_cos_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cos.tprorrateo_cos'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        25-08-2017 19:34:27
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_prorrateo			integer;
    v_record_m				record;
    v_record_d				record;
    v_contador				integer;
    v_bandera				boolean;
    v_record_aux			record;
    v_cont_m				integer;
    v_cont_d				integer;
    v_record_aux2			record;
    v_cont_aux				integer;

BEGIN

    v_nombre_funcion = 'cos.ft_prorrateo_cos_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'COS_PRO_COS_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	if(p_transaccion='COS_PRO_COS_INS')then

        begin
           		raise exception 'v_parametros %',v_parametros;
                --Sentencia de la insercion
                insert into cos.tprorrateo_cos(
                codigo,
                nombre_prorrateo,
                tipo_calculo,
                estado_reg,
                id_usuario_ai,
                usuario_ai,
                fecha_reg,
                id_usuario_reg,
                id_usuario_mod,
                fecha_mod,
                id_gestion
                ) values(
                upper(trim(v_parametros.codigo)),
        		upper(trim(v_parametros.nombre_prorrateo)),
                upper(trim(v_parametros.tipo_calculo)),
                'activo',
                v_parametros._id_usuario_ai,
                v_parametros._nombre_usuario_ai,
                now(),
                p_id_usuario,
                null,
                NULL,
                v_parametros.id_gestion
                )RETURNING id_prorrateo into v_id_prorrateo;

                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCostos almacenado(a) con exito (id_prorrateo'||v_id_prorrateo||')');
                v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo',v_id_prorrateo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PRO_COS_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PRO_COS_MOD')then

		begin
			--Sentencia de la modificacion
			update cos.tprorrateo_cos set
			codigo = upper(trim(v_parametros.codigo)),
			nombre_prorrateo = upper(trim(v_parametros.nombre_prorrateo)),
			tipo_calculo = upper(trim(v_parametros.tipo_calculo)),
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_gestion = v_parametros.id_gestion
			where id_prorrateo=v_parametros.id_prorrateo;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCostos modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo',v_parametros.id_prorrateo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PRO_COS_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PRO_COS_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from cos.tprorrateo_cos
            where id_prorrateo=v_parametros.id_prorrateo;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCostos eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo',v_parametros.id_prorrateo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'COS_PRO_COS_CLONAR'
 	#DESCRIPCION:	Clonar registros registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PRO_COS_CLONAR')then

		begin
        	/*
            *	v_parametros.id_gestion_maestro: gestion de la que se quiere copiar (origen).
            *	v_parametros.id_gestion: gestion a la que se quiere copiar (destino)
            */


			FOR v_record_m IN (SELECT tpc.id_prorrateo,tpc.codigo,tpc.nombre_prorrateo, tpc.tipo_calculo, tpc.id_gestion
            				   FROM cos.tprorrateo_cos tpc
            				   WHERE tpc.id_gestion = v_parametros.id_gestion_maestro)LOOP

                --validar prorrateo
                SELECT count(tpc.id_prorrateo) AS contador, tpc.id_prorrateo
                INTO v_record_aux
                FROM cos.tprorrateo_cos tpc
                WHERE (tpc.codigo = v_record_m.codigo AND
                tpc.nombre_prorrateo = v_record_m.nombre_prorrateo AND
                tpc.tipo_calculo = v_record_m.tipo_calculo) AND
                tpc.id_gestion = v_parametros.id_gestion
                GROUP BY tpc.id_prorrateo;

                --confirmamos si ya existe un prorrateo para la gestión en la que se esta copiando.
                IF(v_record_aux.contador >= 1)THEN
                	--verificamos si tiene mas detalles
                	SELECT count(tpcd.id_prorrateo_det)
                    INTO v_cont_m
                    FROM cos.tprorrateo_cos_det tpcd
                    WHERE tpcd.id_prorrateo =  v_record_m.id_prorrateo;

                    --verificamos cuantos detalles tiene en la gestión que se ha copiado
                    SELECT count(tpcd.id_prorrateo_det)
                    INTO v_cont_d
                    FROM cos.tprorrateo_cos_det tpcd
                    WHERE tpcd.id_prorrateo = v_record_aux.id_prorrateo;

                    	FOR v_record_d  IN (SELECT tpcd.id_prorrateo_det, tpcd.id_prorrateo,
              							 	tpcd.id_tipo_costo, tpcd.id_cuenta, tpcd.id_auxiliar
              					  			FROM cos.tprorrateo_cos_det tpcd
                               	  			WHERE tpcd.id_prorrateo = v_record_m.id_prorrateo)LOOP

                            SELECT count(tpcd.id_prorrateo_det)
                            INTO v_cont_aux
              				FROM cos.tprorrateo_cos_det tpcd
                            WHERE (tpcd.id_tipo_costo = v_record_d.id_tipo_costo AND
                					tpcd.id_cuenta = v_record_d.id_cuenta AND
                					tpcd.id_auxiliar = v_record_d.id_auxiliar) AND
                                    tpcd.id_prorrateo = v_record_aux.id_prorrateo;

                            	IF(v_cont_aux >= 1)THEN
                          			RAISE NOTICE 'YA EXISTE EL REGISTRO';
                                ELSE
                                  INSERT INTO cos.tprorrateo_cos_det(
                                  id_prorrateo,
                                  id_tipo_costo,
                                  id_cuenta,
                                  id_auxiliar,
                                  estado_reg,
                                  id_usuario_ai,
                                  usuario_ai,
                                  fecha_reg,
                                  id_usuario_reg,
                                  fecha_mod,
                                  id_usuario_mod
                                  ) values(
                                  v_record_aux.id_prorrateo,
                                  v_record_d.id_tipo_costo,
                                  v_record_d.id_cuenta,
                                  v_record_d.id_auxiliar,
                                  'activo',
                                  v_parametros._id_usuario_ai,
                                  v_parametros._nombre_usuario_ai,
                                  now(),
                                  p_id_usuario,
                                  null,
                                  null
                                  );
                                END IF;
                        END LOOP;
                ELSE

                  insert into cos.tprorrateo_cos(
                  codigo,
                  nombre_prorrateo,
                  tipo_calculo,
                  estado_reg,
                  id_usuario_ai,
                  usuario_ai,
                  fecha_reg,
                  id_usuario_reg,
                  id_usuario_mod,
                  fecha_mod,
                  id_gestion
                  ) values(
                  v_record_m.codigo,
                  v_record_m.nombre_prorrateo,
                  v_record_m.tipo_calculo,
                  'activo',
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  now(),
                  p_id_usuario,
                  null,
                  null,
                  v_parametros.id_gestion
                  ) RETURNING id_prorrateo into v_id_prorrateo;

                  FOR v_record_d  IN (SELECT tpcd.id_prorrateo_det, tpcd.id_prorrateo,
              						  tpcd.id_tipo_costo, tpcd.id_cuenta, tpcd.id_auxiliar
              						  FROM cos.tprorrateo_cos_det tpcd
                               	  	  WHERE tpcd.id_prorrateo = v_record_m.id_prorrateo)LOOP
                    insert into cos.tprorrateo_cos_det(
                    id_prorrateo,
                    id_tipo_costo,
                    id_cuenta,
                    id_auxiliar,
                    estado_reg,
                    id_usuario_ai,
                    usuario_ai,
                    fecha_reg,
                    id_usuario_reg,
                    fecha_mod,
                    id_usuario_mod
                    ) values(
                    v_id_prorrateo,
                    v_record_d.id_tipo_costo,
                    v_record_d.id_cuenta,
                    v_record_d.id_auxiliar,
                    'activo',
                    v_parametros._id_usuario_ai,
                    v_parametros._nombre_usuario_ai,
                    now(),
                    p_id_usuario,
                    null,
                    null
                    );
                  END LOOP;
                END IF;
               --Definicion de la respuesta
            	v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se ha clonado exitosamente el Prorrateo de Costos con id '||v_id_prorrateo::varchar);
            END LOOP;


            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se ha clonado exitosamente el Prorrateo de Costos con id '||v_id_prorrateo::varchar);


            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'COS_PRO_COS_VALIDAR'
 	#DESCRIPCION:	Validamos el prorrateo de costos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PRO_COS_VALIDAR')then

		begin
			--validar prorrateo
            SELECT count(tpc.id_prorrateo)
            INTO v_contador
            FROM cos.tprorrateo_cos tpc
            WHERE (upper(tpc.codigo) = upper(trim(v_parametros.codigo)) AND
            upper(tpc.nombre_prorrateo) = upper(trim(v_parametros.nombre_prorrateo))) AND
            tpc.id_gestion = v_parametros.id_gestion;

            IF(v_contador>=1)THEN
        		v_bandera = true;
            ELSE
            	v_bandera = false;
			END IF;

            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','El ProrrateoCostos que esta tratando de  insertar, ya se cuenta con un registro de las mismas caracteristicas para esta gestión');
            v_resp = pxp.f_agrega_clave(v_resp,'v_bandera',v_bandera::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

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