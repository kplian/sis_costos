/***********************************I-DEP-RAC-COSTOS-0-27/12/2016*****************************************/


select pxp.f_insert_testructura_gui ('COS', 'SISTEMA');
select pxp.f_insert_testructura_gui ('CONCOS', 'COS');
select pxp.f_insert_testructura_gui ('TIPCOS', 'CONCOS');

--------------- SQL ---------------

ALTER TABLE cos.ttipo_costo
  ADD CONSTRAINT ttipo_costo__id_tipo_costo_fk FOREIGN KEY (id_tipo_costo_fk)
    REFERENCES cos.ttipo_costo(id_tipo_costo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;


/***********************************F-DEP-RAC-COSTOS-0-27/12/2016*****************************************/
