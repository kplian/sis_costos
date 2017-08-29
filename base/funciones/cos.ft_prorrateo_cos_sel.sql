CREATE OR REPLACE FUNCTION cos.ft_prorrateo_cos_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		cos.ft_prorrateo_cos_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cos.tprorrateo_cos'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

BEGIN

	v_nombre_funcion = 'cos.ft_prorrateo_cos_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'COS_PRO_COS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	if(p_transaccion='COS_PRO_COS_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						pro_cos.id_prorrateo,
						pro_cos.codigo,
						pro_cos.nombre_prorrateo,
						pro_cos.tipo_calculo,
						pro_cos.estado_reg,
						pro_cos.id_usuario_ai,
						pro_cos.usuario_ai,
						pro_cos.fecha_reg,
						pro_cos.id_usuario_reg,
						pro_cos.id_usuario_mod,
						pro_cos.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        pro_cos.id_gestion,
                        tg.gestion
						from cos.tprorrateo_cos pro_cos
						inner join segu.tusuario usu1 on usu1.id_usuario = pro_cos.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = pro_cos.id_usuario_mod
                        inner join param.tgestion tg ON tg.id_gestion = pro_cos.id_gestion
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PRO_COS_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PRO_COS_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_prorrateo)
					    from cos.tprorrateo_cos pro_cos
					    inner join segu.tusuario usu1 on usu1.id_usuario = pro_cos.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = pro_cos.id_usuario_mod
                        inner join param.tgestion tg ON tg.id_gestion = pro_cos.id_gestion
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

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