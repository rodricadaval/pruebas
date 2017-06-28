--SECUENCIA TONER

CREATE SEQUENCE system.toners_id_toner_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE system.toners_id_toner_seq
  OWNER TO postgres;

--TABLA TONER

CREATE TABLE system.toners
(
  id_toner integer NOT NULL DEFAULT nextval('system.toners_id_toner_seq'::regclass),
  id_impresora_desc integer NOT NULL,
  id_area integer NOT NULL,
  descripcion text,
  estado smallint NOT NULL DEFAULT 1,
  CONSTRAINT toners_pkey1 PRIMARY KEY (id_toner),
  CONSTRAINT toners_id_impresora_desc_fkey1 FOREIGN KEY (id_impresora_desc)
  REFERENCES system.impresora_desc (id_impresora_desc) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT toners_id_area_fkey1 FOREIGN KEY (id_area)
  REFERENCES system.areas (id_area) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE system.toners
OWNER TO postgres;

--TRIGGER TONER

CREATE OR REPLACE FUNCTION public.registrar_movimientos_insert_toners()
  RETURNS trigger AS
  $BODY$
  DECLARE texto text;
BEGIN
  texto := texto || 'INSERT system.toners::';
  texto := texto || ' AREA -> ' || (select a.nombre from system.areas a join system.toners t on t.id_area = a.id_area where t.id_toner = NEW.id_toner ) || ';' ;
  texto := texto || ' IMPRESORA -> ' || (select concat(m.nombre,' ',d.modelo) from system.impresora_desc d join system.marcas m on m.id_marca = d.id_marca where d.id_impresora_desc = NEW.id_impresora_desc) || ';';
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_toner,texto);
  RETURN NEW;
END $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION public.registrar_movimientos_insert_toners()
  OWNER TO postgres;

CREATE OR REPLACE FUNCTION public.registrar_movimientos_update_toners()
  RETURNS trigger AS
$BODY$
DECLARE texto text;
  
BEGIN
  texto := 'UPDATE system.toners:: ';
  IF OLD.id_impresora_desc <> NEW.id_impresora_desc THEN
  texto := texto ||' IMPRESORA -> '|| OLD.id_impresora_desc || ' => ' || NEW.id_impresora_desc || ';' ;
  END IF;
  IF OLD.id_area <> NEW.id_area THEN
  texto := texto ||' AREA -> '|| OLD.id_area || ' => ' || NEW.id_area || ';' ;
  END IF;
  IF OLD.descripcion <> NEW.descripcion THEN
  texto := texto ||' DESCRIPCION -> '|| OLD.descripcion || ' => ' || NEW.descripcion || ';' ;
  END IF;
  IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_toner,texto);
  RETURN NEW;
END $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION public.registrar_movimientos_update_toners()
  OWNER TO postgres;

CREATE TRIGGER trigger_registrar_movimientos_insert_toners
AFTER INSERT
ON system.toners
FOR EACH ROW
EXECUTE PROCEDURE public.registrar_movimientos_insert_toners();

CREATE TRIGGER trigger_registrar_movimientos_update_toners
AFTER UPDATE
ON system.toners
FOR EACH ROW
EXECUTE PROCEDURE public.registrar_movimientos_update_toners();