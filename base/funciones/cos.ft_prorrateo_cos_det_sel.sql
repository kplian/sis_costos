CREATE OR REPLACE FUNCTION cos.ft_prorrateo_cos_det_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		cos.ft_prorrateo_cos_det_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cos.tprorrateo_cos_det'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        25-08-2017 19:35:31
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

    v_id_gestion 		integer;

    va_id_aux			integer[];
    v_id_aux			varchar;

BEGIN

	v_nombre_funcion = 'cos.ft_prorrateo_cos_det_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'COS_PROCOSDE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	if(p_transaccion='COS_PROCOSDE_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
            			procosde.id_prorrateo_det,
            			procosde.id_prorrateo,
						procosde.id_tipo_costo,
						procosde.id_cuenta,
						procosde.id_auxiliar,
						procosde.estado_reg,
						procosde.id_usuario_ai,
						procosde.usuario_ai,
						procosde.fecha_reg,
						procosde.id_usuario_reg,
						procosde.fecha_mod,
						procosde.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        tc.nombre AS desc_tipo_costo,
                        tc.codigo,
                        c.nombre_cuenta AS desc_cuenta,
                        c.nro_cuenta,
                        aux.nombre_auxiliar AS desc_auxiliar,
                        aux.codigo_auxiliar
						from cos.tprorrateo_cos_det procosde
						inner join segu.tusuario usu1 on usu1.id_usuario = procosde.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = procosde.id_usuario_mod
                        inner join cos.ttipo_costo tc ON tc.id_tipo_costo = procosde.id_tipo_costo
                        inner join conta.tcuenta c ON c.id_cuenta = procosde.id_cuenta
                        inner join conta.tauxiliar aux ON aux.id_auxiliar = procosde.id_auxiliar

				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PROCOSDE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	elsif(p_transaccion='COS_PROCOSDE_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_prorrateo_det)
					    from cos.tprorrateo_cos_det procosde
					    inner join segu.tusuario usu1 on usu1.id_usuario = procosde.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = procosde.id_usuario_mod
                        inner join cos.ttipo_costo tc ON tc.id_tipo_costo = procosde.id_tipo_costo
                        inner join conta.tcuenta c ON c.id_cuenta = procosde.id_cuenta
                        inner join conta.tauxiliar aux ON aux.id_auxiliar = procosde.id_auxiliar
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'COS_TCMOVIMIENTO_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	ELSIF(p_transaccion='COS_TCMOVIMIENTO_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						tco.id_tipo_costo,
						tco.codigo,
						tco.nombre,
						tco.sw_trans,
						tco.descripcion,
						tco.id_tipo_costo_fk,
						tco.estado_reg,
						tco.id_usuario_ai,
						tco.usuario_ai,
						tco.fecha_reg,
						tco.id_usuario_reg,
						tco.id_usuario_mod,
						tco.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from cos.ttipo_costo tco
						inner join segu.tusuario usu1 on usu1.id_usuario = tco.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = tco.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'COS_PROCUE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

    ELSIF(p_transaccion='COS_PROCUE_SEL')then

            begin
            	select g.id_gestion
                into v_id_gestion
                from param.tgestion g
                where g.gestion = EXTRACT(YEAR FROM current_date);
                --Sentencia de la consulta
                v_consulta:='select
                                id_tipo_costo_cuenta,
                                coc.estado_reg,
                                c.id_cuenta,
                                c.nro_cuenta,
                                c.nombre_cuenta,
                                array_to_string( coc.id_auxiliares,'','',''null'')::varchar,
                                (select list(a.codigo_auxiliar) from conta.tauxiliar a where a.id_auxiliar =ANY(coc.id_auxiliares))::varchar as codigo_auxiliares,
                                (select list(a.nombre_auxiliar) from conta.tauxiliar a where a.id_auxiliar =ANY(coc.id_auxiliares))::varchar as auxiliares,
                                coc.id_usuario_reg,
                                coc.fecha_reg,
                                coc.id_usuario_ai,
                                coc.usuario_ai,
                                coc.id_usuario_mod,
                                coc.fecha_mod,
                                usu1.cuenta as usr_reg,
                                usu2.cuenta as usr_mod,
                                coc.id_tipo_costo
                            from cos.ttipo_costo_cuenta coc
                            inner join conta.tcuenta c on c.nro_cuenta = coc.codigo_cuenta and c.id_gestion = '||v_id_gestion||'
                            inner join segu.tusuario usu1 on usu1.id_usuario = coc.id_usuario_reg
                            left join segu.tusuario usu2 on usu2.id_usuario = coc.id_usuario_mod
                            where  ';

                --Definicion de la respuesta
                v_consulta:=v_consulta||v_parametros.filtro;
                v_consulta:=v_consulta||' GROUP BY
                					coc.id_tipo_costo_cuenta,
                                    coc.estado_reg,
                                    c.id_cuenta,
                                	c.nro_cuenta,
                                    coc.id_usuario_reg,
                                    coc.fecha_reg,
                                    coc.id_usuario_ai,
                                    coc.usuario_ai,
                                    coc.id_usuario_mod,
                                    coc.fecha_mod,
                                    usu1.cuenta,
                                    usu2.cuenta,
                                    coc.id_tipo_costo,
                                     c.nombre_cuenta order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            raise notice 'v_consulta: %', v_consulta;
            --Devuelve la respuesta
            return v_consulta;

        end;
    /*********************************
 	#TRANSACCION:  'COS_PROAUX_SEL'
 	#DESCRIPCION:	listado  de auxiliares
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

    ELSEIF(p_transaccion='COS_PROAUX_SEL')then

        begin

            select
               tct.id_auxiliares
            into
               va_id_aux
            from cos.ttipo_costo_cuenta tct
            where tct.id_tipo_costo = v_parametros.id_tipo_costo::integer and tct.codigo_cuenta = v_parametros.nro_cuenta::varchar;

            v_id_aux = array_to_string(va_id_aux,',');

            --Sentencia de la consulta
            v_consulta:='
                            SELECT
                              aux.id_auxiliar,
                              aux.codigo_auxiliar,
                              aux.nombre_auxiliar
                            from conta.tauxiliar aux
                            where aux.id_auxiliar in ('||COALESCE(v_id_aux,'0')||')  AND ';

            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            raise notice 'v_consulta: %',v_consulta;
            --Devuelve la respuesta
            return v_consulta;

        end;

	else

		raise exception 'Transaccion inexistente';

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