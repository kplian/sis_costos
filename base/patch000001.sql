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



--------------- SQL ---------------

CREATE TABLE cos.ttipo_costo_cuenta (
  id_tipo_costo_cuenta SERIAL NOT NULL,
  codigo_cuenta VARCHAR NOT NULL,
  id_auxiliares INTEGER[],
  PRIMARY KEY(id_tipo_costo_cuenta)
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE cos.ttipo_costo_cuenta
  ALTER COLUMN codigo_cuenta SET STATISTICS 0;

COMMENT ON COLUMN cos.ttipo_costo_cuenta.codigo_cuenta
IS 'codigo de cuenta contable';

COMMENT ON COLUMN cos.ttipo_costo_cuenta.id_auxiliares
IS 'define que auxilares tener en cuenta al hacer los balances';


--------------- SQL ---------------

ALTER TABLE cos.ttipo_costo_cuenta
  ADD COLUMN id_tipo_costo INTEGER;

/***********************************F-SCP-RAC-COSTOS-0-27/01/2016****************************************/


/***********************************I-SCP-FEA-COSTOS-0-29/08/2017****************************************/
CREATE TABLE cos.tprorrateo_cos (
  id_prorrateo INTEGER DEFAULT nextval('cos.tprorrateo_id_prorrateo_seq'::regclass) NOT NULL,
  codigo VARCHAR(20),
  nombre_prorrateo VARCHAR(200),
  tipo_calculo VARCHAR(100),
  id_gestion INTEGER,
  CONSTRAINT tprorrateo_pkey PRIMARY KEY(id_prorrateo),
  CONSTRAINT tprorrateo_cos_fk FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE cos.tprorrateo_cos
  ALTER COLUMN id_prorrateo SET STATISTICS 0;

ALTER TABLE cos.tprorrateo_cos
  ALTER COLUMN codigo SET STATISTICS 0;

ALTER TABLE cos.tprorrateo_cos
  ALTER COLUMN nombre_prorrateo SET STATISTICS 0;

ALTER TABLE cos.tprorrateo_cos
  ALTER COLUMN tipo_calculo SET STATISTICS 0;


CREATE TABLE cos.tprorrateo_cos_det (
  id_prorrateo_det INTEGER DEFAULT nextval('cos.tprorrateo_det_id_prorrateo_det_seq'::regclass) NOT NULL,
  id_tipo_costo INTEGER,
  id_cuenta INTEGER,
  id_auxiliar INTEGER,
  id_prorrateo INTEGER,
  CONSTRAINT tprorrateo_det_pkey PRIMARY KEY(id_prorrateo_det),
  CONSTRAINT tprorrateo_cos_det_fk FOREIGN KEY (id_cuenta)
    REFERENCES conta.tcuenta(id_cuenta)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tprorrateo_cos_det_fk1 FOREIGN KEY (id_auxiliar)
    REFERENCES conta.tauxiliar(id_auxiliar)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tprorrateo_tipo_cos_fk FOREIGN KEY (id_tipo_costo)
    REFERENCES cos.ttipo_costo(id_tipo_costo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE cos.tprorrateo_cos_det
  ALTER COLUMN id_prorrateo_det SET STATISTICS 0;
/***********************************F-SCP-FEA-COSTOS-0-29/08/2017****************************************/