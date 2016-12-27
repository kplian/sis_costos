/***********************************I-SCP-RAC-COSTOS-0-27/01/2016****************************************/

--------------- SQL ---------------

CREATE TABLE cos.ttipo_costo (
  id_tipo_costo SERIAL NOT NULL,
  codigo VARCHAR(200) NOT NULL UNIQUE,
  nombre VARCHAR NOT NULL,
  descripcion VARCHAR NOT NULL,
  sw_trans VARCHAR(10) DEFAULT 'no' NOT NULL,
  id_tipo_costo_fk INTEGER,
  PRIMARY KEY(id_tipo_costo)
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE cos.ttipo_costo
  ALTER COLUMN id_tipo_costo_fk SET STATISTICS 0;

COMMENT ON COLUMN cos.ttipo_costo.sw_trans
IS 'si es transaccional o no, solo los nodos hojas son transaccionales';

COMMENT ON COLUMN cos.ttipo_costo.id_tipo_costo_fk
IS 'identifica el tipo de costo padre';


		--------------- SQL ---------------

COMMENT ON COLUMN cos.ttipo_costo.sw_trans
IS 'movimiento o titular, si es transaccional o no, solo los nodos hojas son movimiento';

ALTER TABLE cos.ttipo_costo
  ALTER COLUMN sw_trans SET DEFAULT 'movimiento'::character varying;


/***********************************F-SCP-RAC-COSTOS-0-27/01/2016****************************************/
