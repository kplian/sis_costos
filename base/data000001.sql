/***********************************I-DAT-RAC-COSTOS-0-27/12/2016*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'COS', E'Sistemas de Costos', E'2016-12-27', E'COS', E'activo', E'costos', NULL);

select pxp.f_insert_tgui ('SISTEMAS DE COSTOS', '', 'COS', 'si', 1, '', 1, '', '', 'COS');
select pxp.f_insert_tgui ('Configuración', 'Configuración', 'CONCOS', 'si', 1, '', 2, '', '', 'COS');
select pxp.f_insert_tgui ('Clasificación de Costos', 'Tipos de Costos', 'TIPCOS', 'si', 1, 'sis_costos/vista/tipo_costo/TipoCosto.php', 3, '', 'TipoCosto', 'COS');

/***********************************F-DAT-RAC-COSTOS-0-27/12/2016*****************************************/



/***********************************I-DAT-RAC-COSTOS-0-27/08/2017*****************************************/

select pxp.f_insert_tgui ('Matriz de Costos', 'Reporte Matriz de Costos', 'MATCOS', 'si', 2, 'sis_costos/vista/tipo_costo/FormFiltroBalanceCostos.php', 2, '', 'FormFiltroBalanceCostos', 'COS');
select pxp.f_insert_testructura_gui ('MATCOS', 'COSTO');


/***********************************F-DAT-RAC-COSTOS-0-27/08/2017*****************************************/
