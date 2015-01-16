--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: stock; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE stock WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_AR.UTF-8' LC_CTYPE = 'es_AR.UTF-8';


ALTER DATABASE stock OWNER TO postgres;

\connect stock

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: system; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA system;


ALTER SCHEMA system OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: eliminar_vin(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION eliminar_vin() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	DELETE FROM system.vinculos WHERE id_vinculo = OLD.id_vinculo;
	RETURN OLD;
END $$;


ALTER FUNCTION public.eliminar_vin() OWNER TO postgres;

--
-- Name: eliminar_vin_comp(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION eliminar_vin_comp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	DELETE FROM system.vinculos WHERE id_vinculo = OLD.id_vinculo;
	UPDATE system.vinculos SET id_cpu = 1 where id_cpu = OLD.id_computadora;
	RETURN OLD;
END $$;


ALTER FUNCTION public.eliminar_vin_comp() OWNER TO postgres;

--
-- Name: modificar_area_productos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION modificar_area_productos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	if NEW.area <> OLD.area THEN
		UPDATE system.vinculos SET id_sector=NEW.area WHERE id_usuario=NEW.id_usuario;
	END IF;
	RETURN NEW;
END $$;


ALTER FUNCTION public.modificar_area_productos() OWNER TO postgres;

--
-- Name: registrar_movimientos_delete_areas(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_delete_areas() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (OLD.id_area,'DELETE system.areas:: NOMBRE -> ' || OLD.nombre);
	RETURN OLD;
END $$;


ALTER FUNCTION public.registrar_movimientos_delete_areas() OWNER TO postgres;

--
-- Name: registrar_movimientos_delete_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_delete_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (OLD.id_usuario,'DELETE system.usuarios:: NOMBRE -> '|| OLD.nombre_apellido ||'; SECTOR -> '|| (select nombre from system.areas where id_area = OLD.area));
	RETURN OLD;
END $$;


ALTER FUNCTION public.registrar_movimientos_delete_usuarios() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_areas(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_areas() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_area,'INSERT system.areas:: NOMBRE -> ' || NEW.nombre);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_areas() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_computadora_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_computadora_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'INSERT system.computadora_desc:: ';
	texto := texto ||' MODELO -> '|| NEW.modelo|| ';' ;
	texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
	texto := texto ||' SLOTS -> '|| NEW.slots || ';' ;
	texto := texto ||' MEMORIA MAX -> '|| NEW.mem_max || ';' ;	
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_computadora_desc,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_computadora_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_computadoras(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_computadoras() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_computadora,'INSERT system.computadoras:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.computadora_desc where id_computadora_desc = NEW.id_computadora_desc)) ||'; MODELO -> '|| (select modelo from system.computadora_desc where id_computadora_desc = NEW.id_computadora_desc) || '; SERIE -> ' || NEW.num_serie ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_computadoras() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_disco_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_disco_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_disco_desc,'INSERT system.disco_desc:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.disco_desc where id_disco_desc = NEW.id_disco_desc)));
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_disco_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_discos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_discos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_disco,'INSERT system.discos:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.disco_desc where id_disco_desc = NEW.id_disco_desc)) ||'; CAPACIDAD -> ' || (select capacidad from system.capacidades where id_capacidad = NEW.id_capacidad) ||'; UNIDAD -> ' || (select unidad from system.unidades where id_unidad = NEW.id_unidad) ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_discos() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_impresora_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_impresora_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'INSERT system.impresora_desc:: ';
	texto := texto ||' MODELO -> '|| NEW.modelo|| ';' ;
	texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_impresora_desc,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_impresora_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_impresoras(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_impresoras() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_impresora,'INSERT system.impresoras:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.impresora_desc where id_impresora_desc = NEW.id_impresora_desc)) ||'; MODELO -> '|| (select modelo from system.impresora_desc where id_impresora_desc = NEW.id_impresora_desc) || '; IP -> ' || NEW.ip ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_impresoras() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_marcas(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_marcas() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_marca,'INSERT system.marcas:: NOMBRE -> ' || NEW.nombre);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_marcas() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_memoria_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_memoria_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'INSERT system.memoria_desc:: ';
	texto := texto ||' TIPO -> '|| NEW.tipo || ';' ;
	texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
	texto := texto ||' VELOCIDAD -> '|| NEW.velocidad || 'Mhz;' ;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_memoria_desc,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_memoria_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_memorias(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_memorias() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_memoria,'INSERT system.memorias:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.memoria_desc where id_memoria_desc = NEW.id_memoria_desc)) ||'; CAPACIDAD -> ' || (select capacidad from system.capacidades where id_capacidad = NEW.id_capacidad) ||'; UNIDAD -> ' || (select unidad from system.unidades where id_unidad = NEW.id_unidad) ||'; VELOCIDAD -> '|| (select velocidad from system.memoria_desc where id_memoria_desc = NEW.id_memoria_desc) ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_memorias() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_monitor_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_monitor_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'INSERT system.monitor_desc:: ';
	texto := texto ||' MODELO -> '|| NEW.modelo|| ';' ;
	texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
	if NEW.pulgadas is not NULL then
	texto := texto ||' PULGADAS -> '|| NEW.pulgadas || ';' ;
	end if;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_monitor_desc,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_monitor_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_monitores(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_monitores() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_monitor,'INSERT system.monitores:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.monitor_desc where id_monitor_desc = NEW.id_monitor_desc)) ||'; MODELO -> '|| (select modelo from system.monitor_desc where id_monitor_desc = NEW.id_monitor_desc) || '; SERIE -> ' || NEW.num_serie ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_monitores() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_router_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_router_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
  
BEGIN
  texto := 'INSERT system.router_desc:: ';
  texto := texto ||' MODELO -> '|| NEW.modelo|| ';' ;
  texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_router_desc,texto);
  RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_router_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_routers(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_routers() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_router,'INSERT system.routers:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.router_desc where id_router_desc = NEW.id_router_desc)) ||'; MODELO -> '|| (select modelo from system.router_desc where id_router_desc = NEW.id_router_desc) || '; IP -> ' || NEW.ip ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_routers() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_switch_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_switch_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
  
BEGIN
  texto := 'INSERT system.switch_desc:: ';
  texto := texto ||' MODELO -> '|| NEW.modelo|| ';' ;
  texto := texto ||' MARCA -> '|| (select nombre from system.marcas where id_marca = NEW.id_marca) || ';' ;
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_switch_desc,texto);
  RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_switch_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_switchs(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_switchs() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_switch,'INSERT system.switchs:: MARCA -> ' || (select nombre from system.marcas where id_marca = (select id_marca from system.switch_desc where id_switch_desc = NEW.id_switch_desc)) ||'; MODELO -> '|| (select modelo from system.switch_desc where id_switch_desc = NEW.id_switch_desc) || '; IP -> ' || NEW.ip ||'; VINCULO -> '|| NEW.id_vinculo);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_switchs() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_usuario,'INSERT system.usuarios:: USUARIO -> ' || NEW.usuario ||'; NOMBRE -> '|| NEW.nombre_apellido || '; EMAIL -> ' || NEW.email ||'; SECTOR -> '|| (select nombre from system.areas where id_area = NEW.area) ||'; INTERNO -> '||NEW.interno) ;
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_usuarios() OWNER TO postgres;

--
-- Name: registrar_movimientos_insert_vinculos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_insert_vinculos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_vinculo,'INSERT system.vinculos:: USUARIO -> ' || (select nombre_apellido from system.usuarios where id_usuario = NEW.id_usuario) ||'; ID-SERIE-CPU -> '|| NEW.id_cpu || ' - ' || (select num_serie from system.computadoras where id_computadora = NEW.id_cpu) || '; SECTOR -> '|| (select nombre from system.areas where id_area = NEW.id_sector) || '; PRODUCTO -> '|| (select nombre from system.tipo_productos where id_tipo_producto = NEW.id_tipo_producto) ||'; ID PK PRODUCTO -> ' || NEW.id_pk_producto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_insert_vinculos() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_areas(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_areas() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.areas:: ';
	IF OLD.nombre <> NEW.nombre THEN
	texto := texto ||' NOMBRE -> '|| OLD.nombre || ' => ' || NEW.nombre || ';' ;
	END IF;
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_area,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_areas() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_computadoras(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_computadoras() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.computadoras:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_computadora,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_computadoras() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_disco_desc(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_disco_desc() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.disco_desc:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_disco_desc,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_disco_desc() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_discos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_discos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.discos:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_disco,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_discos() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_impresoras(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_impresoras() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.impresoras:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.descripcion <> OLD.descripcion THEN
	texto := texto ||' DESCRIPCION -> '|| OLD.descripcion  || ' => ' || NEW.descripcion || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_impresora,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_impresoras() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_marcas(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_marcas() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.marcas:: ';
	IF OLD.nombre <> NEW.nombre THEN
	texto := texto ||' NOMBRE -> '|| OLD.nombre || ' => ' || NEW.nombre || ';' ;
	END IF;
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_marca,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_marcas() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_memorias(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_memorias() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.memorias:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_memoria,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_memorias() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_monitores(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_monitores() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.monitores:: ';
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_monitor,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_monitores() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_routers(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_routers() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
  
BEGIN
  texto := 'UPDATE system.routers:: ';
  IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  IF NEW.descripcion <> OLD.descripcion THEN
  texto := texto ||' DESCRIPCION -> '|| OLD.descripcion  || ' => ' || NEW.descripcion || ';' ;
  END IF;
  IF NEW.ip <> OLD.ip THEN
  texto := texto ||' IP -> '|| OLD.ip  || ' => ' || NEW.ip || ';' ;
  END IF;
  
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_router,texto);
  RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_routers() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_switchs(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_switchs() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
  
BEGIN
  texto := 'UPDATE system.switchs:: ';
  IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
  texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
  END IF;
  IF NEW.descripcion <> OLD.descripcion THEN
  texto := texto ||' DESCRIPCION -> '|| OLD.descripcion  || ' => ' || NEW.descripcion || ';' ;
  END IF;
  IF NEW.ip <> OLD.ip THEN
  texto := texto ||' IP -> '|| OLD.ip  || ' => ' || NEW.ip || ';' ;
  END IF;
  
  INSERT INTO system.historial_acciones (id,accion) 
  VALUES (NEW.id_switch,texto);
  RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_switchs() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.usuarios:: ';
	IF OLD.usuario <> NEW.usuario THEN
	texto := texto ||' USUARIO -> '|| OLD.usuario || ' => ' || NEW.usuario || ';' ;
	END IF;
	IF OLD.nombre_apellido <> NEW.nombre_apellido THEN
	texto := texto ||' NOMBRE -> '|| OLD.nombre_apellido || ' => ' || NEW.nombre_apellido || ';' ;
	END IF;
	IF OLD.area <> NEW.area THEN
	texto := texto ||' SECTOR -> '|| (select nombre from system.areas where id_area = OLD.area) || ' => ' || (select nombre from system.areas where id_area = NEW.area) || ';' ;
	END IF;
	IF OLD.permisos <> NEW.permisos THEN
	texto := texto ||' PERMISOS -> '|| (select nombre from system.permisos where tipo_acceso = OLD.permisos) || ' => ' || (select nombre from system.permisos where tipo_acceso = NEW.permisos) || ';' ;
	END IF;
	IF OLD.email <> NEW.email THEN
	texto := texto ||' EMAIL -> '|| OLD.email || ' => ' || NEW.email || ';' ;
	END IF;
	IF OLD.interno <> NEW.interno THEN
	texto := texto ||' INTERNO -> '|| OLD.interno || ' => ' || NEW.interno || ';' ;
	END IF;
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_usuario,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_usuarios() OWNER TO postgres;

--
-- Name: registrar_movimientos_update_vinculos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION registrar_movimientos_update_vinculos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE texto text;
	
BEGIN
	texto := 'UPDATE system.vinculos:: ';
	IF OLD.id_cpu <> NEW.id_cpu THEN
	texto := texto ||' CPU -> '|| (select num_serie from system.computadoras where id_computadora = OLD.id_cpu) || ' => ' || (select num_serie from system.computadoras where id_computadora = NEW.id_cpu) || ';' ;
	END IF;
	IF OLD.id_sector <> NEW.id_sector THEN
	texto := texto ||' SECTOR -> '|| (select nombre from system.areas where id_area = OLD.id_sector) || ' => ' || (select nombre from system.areas where id_area = NEW.id_sector) || ';' ;
	END IF;
	IF OLD.id_usuario <> NEW.id_usuario THEN
	texto := texto ||' USUARIO -> '|| (select nombre_apellido from system.usuarios where id_usuario = OLD.id_usuario) || ' => ' || (select nombre_apellido from system.usuarios where id_usuario = NEW.id_usuario) || ';' ;
	END IF;

	texto := texto ||' PRODUCTO -> '|| (select nombre from system.tipo_productos where id_tipo_producto = NEW.id_tipo_producto) || ';' ;

	IF OLD.id_pk_producto <> NEW.id_pk_producto THEN
	texto := texto ||' PK PRODUCTO -> '|| OLD.id_pk_producto || ' => ' || NEW.id_pk_producto || ';' ;
	END IF;
	IF NEW.estado = 0 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (BAJA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	IF NEW.estado = 1 AND OLD.estado <> NEW.estado THEN
	texto := texto ||' ESTADO (ALTA LOGICA) -> '|| OLD.estado  || ' => ' || NEW.estado  || ';' ;
	END IF;
	
	INSERT INTO system.historial_acciones (id,accion) 
	VALUES (NEW.id_vinculo,texto);
	RETURN NEW;
END $$;


ALTER FUNCTION public.registrar_movimientos_update_vinculos() OWNER TO postgres;

SET search_path = system, pg_catalog;

--
-- Name: baja_logica_producto(integer, integer, character varying, character varying); Type: FUNCTION; Schema: system; Owner: postgres
--

CREATE FUNCTION baja_logica_producto(id_prod integer, id_tipo integer, tabla character varying, nombre_campo_pk character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN

EXECUTE 'UPDATE ' || tabla || ' SET estado = 0  WHERE ' || nombre_campo_pk || ' = ' || id_prod;
EXECUTE 'UPDATE system.vinculos SET estado = 0 WHERE id_tipo_producto = ' || id_tipo || ' AND id_pk_producto = ' || id_prod;
--EXECUTE format('SELECT (EXISTS (UPDATE %s SET estado = 0 WHERE %s = id_prod))::int', tabla, nombre_campo_pk)
END
$$;


ALTER FUNCTION system.baja_logica_producto(id_prod integer, id_tipo integer, tabla character varying, nombre_campo_pk character varying) OWNER TO postgres;

--
-- Name: limpiar_productos_asoc_a_cpu(integer); Type: FUNCTION; Schema: system; Owner: postgres
--

CREATE FUNCTION limpiar_productos_asoc_a_cpu(id_comp integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE system.vinculos SET id_cpu = 1 where id_cpu = id_comp;
END	
$$;


ALTER FUNCTION system.limpiar_productos_asoc_a_cpu(id_comp integer) OWNER TO postgres;

--
-- Name: limpiar_productos_de_usuario(integer); Type: FUNCTION; Schema: system; Owner: postgres
--

CREATE FUNCTION limpiar_productos_de_usuario(id_user integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE system.vinculos SET id_usuario = 1 where id_usuario = id_user;
END	
$$;


ALTER FUNCTION system.limpiar_productos_de_usuario(id_user integer) OWNER TO postgres;

--
-- Name: modificar_area_productos_de_usuario(integer, integer); Type: FUNCTION; Schema: system; Owner: postgres
--

CREATE FUNCTION modificar_area_productos_de_usuario(id_user integer, id_sec integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE system.vinculos SET id_sector = id_sec where id_usuario = id_user;
END	
$$;


ALTER FUNCTION system.modificar_area_productos_de_usuario(id_user integer, id_sec integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: areas; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE areas (
    id_area integer NOT NULL,
    nombre character varying(200) NOT NULL,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.areas OWNER TO postgres;

--
-- Name: area_id_area_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE area_id_area_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.area_id_area_seq OWNER TO postgres;

--
-- Name: area_id_area_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE area_id_area_seq OWNED BY areas.id_area;


--
-- Name: capacidades; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE capacidades (
    id_capacidad integer NOT NULL,
    capacidad integer NOT NULL
);


ALTER TABLE system.capacidades OWNER TO postgres;

--
-- Name: capacidad_id_capacidad_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE capacidad_id_capacidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.capacidad_id_capacidad_seq OWNER TO postgres;

--
-- Name: capacidad_id_capacidad_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE capacidad_id_capacidad_seq OWNED BY capacidades.id_capacidad;


--
-- Name: computadora_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE computadora_desc (
    id_computadora_desc integer NOT NULL,
    id_marca integer,
    modelo character varying(45) NOT NULL,
    estado smallint DEFAULT 1 NOT NULL,
    slots integer,
    mem_max integer
);


ALTER TABLE system.computadora_desc OWNER TO postgres;

--
-- Name: computadora_desc_id_computadora_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE computadora_desc_id_computadora_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.computadora_desc_id_computadora_desc_seq OWNER TO postgres;

--
-- Name: computadora_desc_id_computadora_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE computadora_desc_id_computadora_desc_seq OWNED BY computadora_desc.id_computadora_desc;


--
-- Name: computadoras; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE computadoras (
    id_computadora integer NOT NULL,
    id_computadora_desc integer,
    num_serie character varying(45) NOT NULL,
    descripcion text,
    id_vinculo integer NOT NULL,
    estado smallint DEFAULT 1 NOT NULL,
    clase "char" NOT NULL
);


ALTER TABLE system.computadoras OWNER TO postgres;

--
-- Name: computadoras_id_computadora_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE computadoras_id_computadora_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.computadoras_id_computadora_seq OWNER TO postgres;

--
-- Name: computadoras_id_computadora_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE computadoras_id_computadora_seq OWNED BY computadoras.id_computadora;


--
-- Name: disco_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE disco_desc (
    id_disco_desc integer NOT NULL,
    id_marca integer,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.disco_desc OWNER TO postgres;

--
-- Name: disco_desc_id_disco_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE disco_desc_id_disco_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.disco_desc_id_disco_desc_seq OWNER TO postgres;

--
-- Name: disco_desc_id_disco_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE disco_desc_id_disco_desc_seq OWNED BY disco_desc.id_disco_desc;


--
-- Name: discos; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE discos (
    id_disco integer NOT NULL,
    id_vinculo integer,
    estado smallint DEFAULT 1 NOT NULL,
    descripcion text,
    id_unidad integer NOT NULL,
    id_capacidad integer NOT NULL,
    id_disco_desc integer
);


ALTER TABLE system.discos OWNER TO postgres;

--
-- Name: discos_id_disco_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE discos_id_disco_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.discos_id_disco_seq OWNER TO postgres;

--
-- Name: discos_id_disco_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE discos_id_disco_seq OWNED BY discos.id_disco;


--
-- Name: historial_acciones; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE historial_acciones (
    fecha timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
    id integer,
    accion text
);


ALTER TABLE system.historial_acciones OWNER TO postgres;

--
-- Name: impresora_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE impresora_desc (
    id_impresora_desc integer NOT NULL,
    id_marca integer,
    modelo character varying(30),
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.impresora_desc OWNER TO postgres;

--
-- Name: impresora_desc_id_impresora_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE impresora_desc_id_impresora_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.impresora_desc_id_impresora_desc_seq OWNER TO postgres;

--
-- Name: impresora_desc_id_impresora_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE impresora_desc_id_impresora_desc_seq OWNED BY impresora_desc.id_impresora_desc;


--
-- Name: impresoras; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE impresoras (
    id_impresora integer NOT NULL,
    id_impresora_desc integer,
    num_serie character varying(50) NOT NULL,
    descripcion text,
    estado smallint DEFAULT 1 NOT NULL,
    id_vinculo integer NOT NULL,
    ip character varying(20)
);


ALTER TABLE system.impresoras OWNER TO postgres;

--
-- Name: impresoras_id_impresora_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE impresoras_id_impresora_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.impresoras_id_impresora_seq OWNER TO postgres;

--
-- Name: impresoras_id_impresora_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE impresoras_id_impresora_seq OWNED BY impresoras.id_impresora;


--
-- Name: marcas; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE marcas (
    nombre character varying(50) NOT NULL,
    id_marca integer NOT NULL,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.marcas OWNER TO postgres;

--
-- Name: marcas_id_marca_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE marcas_id_marca_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.marcas_id_marca_seq OWNER TO postgres;

--
-- Name: marcas_id_marca_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE marcas_id_marca_seq OWNED BY marcas.id_marca;


--
-- Name: memoria_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE memoria_desc (
    id_memoria_desc integer NOT NULL,
    tipo character varying(10),
    id_marca integer,
    velocidad integer,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.memoria_desc OWNER TO postgres;

--
-- Name: memorias; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE memorias (
    id_memoria integer NOT NULL,
    id_vinculo integer,
    id_memoria_desc integer,
    id_capacidad integer NOT NULL,
    id_unidad integer NOT NULL,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.memorias OWNER TO postgres;

--
-- Name: memorias_id_memoria_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE memorias_id_memoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.memorias_id_memoria_seq OWNER TO postgres;

--
-- Name: memorias_id_memoria_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE memorias_id_memoria_seq OWNED BY memoria_desc.id_memoria_desc;


--
-- Name: memorias_id_memoria_seq1; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE memorias_id_memoria_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.memorias_id_memoria_seq1 OWNER TO postgres;

--
-- Name: memorias_id_memoria_seq1; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE memorias_id_memoria_seq1 OWNED BY memorias.id_memoria;


--
-- Name: monitor_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE monitor_desc (
    id_monitor_desc integer NOT NULL,
    modelo character varying(45),
    id_marca integer,
    pulgadas character varying(5),
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.monitor_desc OWNER TO postgres;

--
-- Name: monitor_desc_id_monitor_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE monitor_desc_id_monitor_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.monitor_desc_id_monitor_desc_seq OWNER TO postgres;

--
-- Name: monitor_desc_id_monitor_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE monitor_desc_id_monitor_desc_seq OWNED BY monitor_desc.id_monitor_desc;


--
-- Name: monitores; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE monitores (
    id_monitor integer NOT NULL,
    num_serie character varying(45),
    id_vinculo integer,
    id_monitor_desc integer,
    estado smallint DEFAULT 1 NOT NULL,
    descripcion text
);


ALTER TABLE system.monitores OWNER TO postgres;

--
-- Name: monitores_id_monitor_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE monitores_id_monitor_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.monitores_id_monitor_seq OWNER TO postgres;

--
-- Name: monitores_id_monitor_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE monitores_id_monitor_seq OWNED BY monitores.id_monitor;


--
-- Name: permisos; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE permisos (
    nombre character varying(200),
    tipo_acceso integer NOT NULL
);


ALTER TABLE system.permisos OWNER TO postgres;

--
-- Name: router_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE router_desc (
    id_router_desc integer NOT NULL,
    id_marca integer NOT NULL,
    modelo character varying(50),
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.router_desc OWNER TO postgres;

--
-- Name: router_desc_id_router_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE router_desc_id_router_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.router_desc_id_router_desc_seq OWNER TO postgres;

--
-- Name: router_desc_id_router_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE router_desc_id_router_desc_seq OWNED BY router_desc.id_router_desc;


--
-- Name: routers; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE routers (
    id_router integer NOT NULL,
    id_router_desc integer,
    num_serie character varying(50),
    descripcion text,
    estado smallint DEFAULT 1 NOT NULL,
    id_vinculo integer,
    ip character varying(30) NOT NULL
);


ALTER TABLE system.routers OWNER TO postgres;

--
-- Name: routers_id_router_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE routers_id_router_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.routers_id_router_seq OWNER TO postgres;

--
-- Name: routers_id_router_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE routers_id_router_seq OWNED BY routers.id_router;


--
-- Name: stock; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE stock (
    familia integer,
    deposito integer NOT NULL,
    descripcion text,
    nro_serie_pc character varying(50),
    nro_serie_prod character varying(50)
);


ALTER TABLE system.stock OWNER TO postgres;

--
-- Name: switch_desc; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE switch_desc (
    id_switch_desc integer NOT NULL,
    id_marca integer NOT NULL,
    modelo character varying(50) NOT NULL,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.switch_desc OWNER TO postgres;

--
-- Name: switch_desc_id_switch_desc_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE switch_desc_id_switch_desc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.switch_desc_id_switch_desc_seq OWNER TO postgres;

--
-- Name: switch_desc_id_switch_desc_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE switch_desc_id_switch_desc_seq OWNED BY switch_desc.id_switch_desc;


--
-- Name: switchs; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE switchs (
    id_switch integer NOT NULL,
    id_switch_desc integer NOT NULL,
    num_serie character varying(50) NOT NULL,
    descripcion text,
    estado smallint DEFAULT 1 NOT NULL,
    id_vinculo integer,
    ip character varying(30)
);


ALTER TABLE system.switchs OWNER TO postgres;

--
-- Name: switchs_id_switch_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE switchs_id_switch_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.switchs_id_switch_seq OWNER TO postgres;

--
-- Name: switchs_id_switch_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE switchs_id_switch_seq OWNED BY switchs.id_switch;


--
-- Name: tipo_productos; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE tipo_productos (
    nombre character varying(30) NOT NULL,
    id_tipo_producto integer NOT NULL
);


ALTER TABLE system.tipo_productos OWNER TO postgres;

--
-- Name: tipo_productos_id_tipo_producto_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE tipo_productos_id_tipo_producto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.tipo_productos_id_tipo_producto_seq OWNER TO postgres;

--
-- Name: tipo_productos_id_tipo_producto_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE tipo_productos_id_tipo_producto_seq OWNED BY tipo_productos.id_tipo_producto;


--
-- Name: unidades; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE unidades (
    id_unidad integer NOT NULL,
    unidad character varying(2) NOT NULL,
    potencia smallint NOT NULL
);


ALTER TABLE system.unidades OWNER TO postgres;

--
-- Name: unidades_id_unidad_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE unidades_id_unidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.unidades_id_unidad_seq OWNER TO postgres;

--
-- Name: unidades_id_unidad_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE unidades_id_unidad_seq OWNED BY unidades.id_unidad;


--
-- Name: usuarios; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarios (
    nombre_apellido character varying(100) NOT NULL,
    id_usuario integer NOT NULL,
    email character varying(100),
    password character varying(100) NOT NULL,
    permisos integer NOT NULL,
    area integer NOT NULL,
    usuario character varying(30) NOT NULL,
    estado smallint DEFAULT 1 NOT NULL,
    interno integer
);


ALTER TABLE system.usuarios OWNER TO postgres;

--
-- Name: usuarioseq_id_usuario_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE usuarioseq_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.usuarioseq_id_usuario_seq OWNER TO postgres;

--
-- Name: usuarioseq_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE usuarioseq_id_usuario_seq OWNED BY usuarios.id_usuario;


--
-- Name: vinculos; Type: TABLE; Schema: system; Owner: postgres; Tablespace: 
--

CREATE TABLE vinculos (
    id_vinculo integer NOT NULL,
    id_usuario integer NOT NULL,
    id_sector integer NOT NULL,
    id_cpu integer,
    id_tipo_producto integer,
    id_pk_producto integer NOT NULL,
    estado smallint DEFAULT 1 NOT NULL
);


ALTER TABLE system.vinculos OWNER TO postgres;

--
-- Name: vinculos_id_vinculo_seq; Type: SEQUENCE; Schema: system; Owner: postgres
--

CREATE SEQUENCE vinculos_id_vinculo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system.vinculos_id_vinculo_seq OWNER TO postgres;

--
-- Name: vinculos_id_vinculo_seq; Type: SEQUENCE OWNED BY; Schema: system; Owner: postgres
--

ALTER SEQUENCE vinculos_id_vinculo_seq OWNED BY vinculos.id_vinculo;


--
-- Name: id_area; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY areas ALTER COLUMN id_area SET DEFAULT nextval('area_id_area_seq'::regclass);


--
-- Name: id_capacidad; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY capacidades ALTER COLUMN id_capacidad SET DEFAULT nextval('capacidad_id_capacidad_seq'::regclass);


--
-- Name: id_computadora_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY computadora_desc ALTER COLUMN id_computadora_desc SET DEFAULT nextval('computadora_desc_id_computadora_desc_seq'::regclass);


--
-- Name: id_computadora; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY computadoras ALTER COLUMN id_computadora SET DEFAULT nextval('computadoras_id_computadora_seq'::regclass);


--
-- Name: id_disco_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY disco_desc ALTER COLUMN id_disco_desc SET DEFAULT nextval('disco_desc_id_disco_desc_seq'::regclass);


--
-- Name: id_disco; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY discos ALTER COLUMN id_disco SET DEFAULT nextval('discos_id_disco_seq'::regclass);


--
-- Name: id_impresora_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY impresora_desc ALTER COLUMN id_impresora_desc SET DEFAULT nextval('impresora_desc_id_impresora_desc_seq'::regclass);


--
-- Name: id_impresora; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY impresoras ALTER COLUMN id_impresora SET DEFAULT nextval('impresoras_id_impresora_seq'::regclass);


--
-- Name: id_marca; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY marcas ALTER COLUMN id_marca SET DEFAULT nextval('marcas_id_marca_seq'::regclass);


--
-- Name: id_memoria_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memoria_desc ALTER COLUMN id_memoria_desc SET DEFAULT nextval('memorias_id_memoria_seq'::regclass);


--
-- Name: id_memoria; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memorias ALTER COLUMN id_memoria SET DEFAULT nextval('memorias_id_memoria_seq1'::regclass);


--
-- Name: id_monitor_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY monitor_desc ALTER COLUMN id_monitor_desc SET DEFAULT nextval('monitor_desc_id_monitor_desc_seq'::regclass);


--
-- Name: id_monitor; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY monitores ALTER COLUMN id_monitor SET DEFAULT nextval('monitores_id_monitor_seq'::regclass);


--
-- Name: id_router_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY router_desc ALTER COLUMN id_router_desc SET DEFAULT nextval('router_desc_id_router_desc_seq'::regclass);


--
-- Name: id_router; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY routers ALTER COLUMN id_router SET DEFAULT nextval('routers_id_router_seq'::regclass);


--
-- Name: id_switch_desc; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY switch_desc ALTER COLUMN id_switch_desc SET DEFAULT nextval('switch_desc_id_switch_desc_seq'::regclass);


--
-- Name: id_switch; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY switchs ALTER COLUMN id_switch SET DEFAULT nextval('switchs_id_switch_seq'::regclass);


--
-- Name: id_tipo_producto; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY tipo_productos ALTER COLUMN id_tipo_producto SET DEFAULT nextval('tipo_productos_id_tipo_producto_seq'::regclass);


--
-- Name: id_unidad; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY unidades ALTER COLUMN id_unidad SET DEFAULT nextval('unidades_id_unidad_seq'::regclass);


--
-- Name: id_usuario; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY usuarios ALTER COLUMN id_usuario SET DEFAULT nextval('usuarioseq_id_usuario_seq'::regclass);


--
-- Name: id_vinculo; Type: DEFAULT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY vinculos ALTER COLUMN id_vinculo SET DEFAULT nextval('vinculos_id_vinculo_seq'::regclass);


--
-- Name: area_id_area_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('area_id_area_seq', 22, true);


--
-- Data for Name: areas; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY areas (id_area, nombre, estado) FROM stdin;
9	SISTEMAS	1
8	CAPITAS	1
2	REGALO	1
1	STOCK	1
14	RECEPCION BERNARDO	1
15	RECEPCION RIVADAVIA	1
16	COORDINACION	1
3	LEGAL	1
17	PLANIFICACION ESTRATEGICA	1
6	COMUNICACION	1
18	SUPERVISION Y AUDITORIA	1
19	COBERTURA PRESTACIONAL	1
20	ASISTENCIA TECNICA Y CAPACITACION	1
21	ADMINISTRACION	1
22	PLANES ESPECIALES	1
\.


--
-- Name: capacidad_id_capacidad_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('capacidad_id_capacidad_seq', 13, true);


--
-- Data for Name: capacidades; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY capacidades (id_capacidad, capacidad) FROM stdin;
1	1
2	2
3	4
4	8
5	16
6	32
7	64
8	128
9	256
10	512
11	1024
\.


--
-- Data for Name: computadora_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY computadora_desc (id_computadora_desc, id_marca, modelo, estado, slots, mem_max) FROM stdin;
3	4	Optiplex 380	1	2	8
2	4	Optiplex 9020	1	2	16
1	4	Vostro 1520	1	2	8
4	2	ThinkCentre A25	1	4	32
6	4	Optiplex 9030	1	2	16
\.


--
-- Name: computadora_desc_id_computadora_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('computadora_desc_id_computadora_desc_seq', 6, true);


--
-- Data for Name: computadoras; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY computadoras (id_computadora, id_computadora_desc, num_serie, descripcion, id_vinculo, estado, clase) FROM stdin;
1	1	Sin Cpu	DESCONOCIDO	1	1	-
\.


--
-- Name: computadoras_id_computadora_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('computadoras_id_computadora_seq', 25, true);


--
-- Data for Name: disco_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY disco_desc (id_disco_desc, id_marca, estado) FROM stdin;
1	1	1
2	8	1
3	9	1
4	10	1
5	11	1
6	12	1
7	3	1
8	2	1
\.


--
-- Name: disco_desc_id_disco_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('disco_desc_id_disco_desc_seq', 17, true);


--
-- Data for Name: discos; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY discos (id_disco, id_vinculo, estado, descripcion, id_unidad, id_capacidad, id_disco_desc) FROM stdin;
\.


--
-- Name: discos_id_disco_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('discos_id_disco_seq', 20, true);


--
-- Data for Name: historial_acciones; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY historial_acciones (fecha, id, accion) FROM stdin;
2014-12-24 19:11:52.07288	1	CAPITAS => MEDICA
2014-12-24 19:42:13.802549	40	INSERT system.usuarios: ID->40; Usuario->mvleyton@msal.gov.ar; \n\tNombremvleyton@msal.gov.ar; Email->mvleyton@msal.gov.ar; Sector->7
2014-12-24 19:47:07.859431	43	INSERT system.usuarios: ID->43; Usuario->maríavirginialeyton; NombreMaría Virginia Leyton; Email->mvleyton@msal.gov.ar; Sector->8
2014-12-24 19:53:52.924839	47	INSERT system.usuarios: ID->47; Usuario->patriciaderosso; Nombre->Patricia De Rosso; Email->paderosso@gmail.com; Sector->CAPITAS; Interno->301
2014-12-24 20:06:58.260664	47	UPDATE system.usuarios: Usuario->patriciaderosso=>patriciaderosso; Nombre->Patricia De Rosso=>Patricia De Rosso; Email->paderosso@gmail.com=>paderosso@gmail.com; Permisos->2=>2; Sector->CAPITAS=>COMUNICACIONES; Interno->301=>301
2014-12-24 20:49:32.181609	47	UPDATE system.usuarios::  SECTOR -> COMUNICACIONES => TECNICA;
2014-12-24 20:50:41.251601	47	UPDATE system.usuarios::  SECTOR -> TECNICA => MEDICA; INTERNO -> 301 => 302;
2014-12-24 20:51:05.07552	47	UPDATE system.usuarios::  INTERNO -> 302 => 301;
2014-12-24 20:52:15.490372	43	UPDATE system.usuarios::  SECTOR -> CAPITAS => RECEPCION BERNARDO;
2014-12-24 20:52:25.538498	47	UPDATE system.usuarios::  SECTOR -> MEDICA => RECEPCION RIVADAVIA;
2014-12-24 20:57:54.553836	48	INSERT system.usuarios: ID -> 48; Usuario -> javier; Nombre -> Javier Minsky; Email -> javier.minsky@gmail.com; Sector -> SISTEMAS; Interno -> 160
2014-12-24 20:58:37.372115	48	UPDATE system.usuarios::  INTERNO -> 160 => 161;
2014-12-24 21:00:54.86568	48	UPDATE system.usuarios::  INTERNO -> 161 => 162;
2014-12-25 02:02:18.193714	29	UPDATE system.usuarios::  ESTADO (BAJA LOGICA) -> 1 => 0;
2014-12-25 02:04:17.234835	29	UPDATE system.usuarios::  ESTADO (ALTA LOGICA) -> 0 => 1;
2014-12-25 02:25:13.498267	1	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Computadora; ID PK PRODUCTO -> 1
2014-12-25 02:39:43.720498	1	INSERT system.vinculos:: ID VINCULO -> 43; USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2014-12-25 02:39:43.774481	1	UPDATE system.vinculos::  PK PRODUCTO -> 1 => 3;
2014-12-30 01:11:14.094643	1	UPDATE system.discos:: 
2014-12-30 01:11:17.341182	2	UPDATE system.discos:: 
2014-12-30 01:11:18.165201	6	UPDATE system.discos:: 
2014-12-25 03:15:58.67132	47	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2014-12-30 01:11:32.413531	7	UPDATE system.discos:: 
2014-12-30 01:18:01.609359	51	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2014-12-25 03:22:50.408447	49	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2014-12-25 03:22:50.417585	9	INSERT system.impresoras:: MARCA -> HP; MODELO -> LasertJet P3015; IP -> 182.4.5.1; VINCULO -> 49
2014-12-25 03:22:50.428608	49	UPDATE system.vinculos::  PK PRODUCTO -> 1 => 9;
2014-12-25 03:23:51.147167	50	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Monitor; ID PK PRODUCTO -> 1
2014-12-25 03:23:51.162539	30	INSERT system.monitores:: MARCA -> SAMSUNG; MODELO -> JPG-200; SERIE -> NUNASND; VINCULO -> 50
2014-12-25 03:23:51.190114	50	UPDATE system.vinculos::  PK PRODUCTO -> 1 => 30;
2014-12-25 03:41:05.717	25	UPDATE system.vinculos::  SECTOR -> REGALO => TECNICA;
2014-12-25 04:37:20.968998	31	UPDATE system.vinculos::  SECTOR -> TECNICA => SISTEMAS; USUARIO -> Sin usuario => Javier Minsky;
2014-12-25 04:38:02.552757	33	UPDATE system.vinculos::  CPU -> Sin Cpu => 5168413; SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Javier Minsky;
2014-12-25 04:42:25.997134	8	UPDATE system.vinculos::  SECTOR -> STOCK => SISTEMAS; PRODUCTO -> Monitor;
2014-12-25 04:42:35.606305	8	UPDATE system.vinculos::  CPU -> Sin Cpu => 5168413; USUARIO -> Sin usuario => Javier Minsky; PRODUCTO -> Monitor;
2014-12-29 20:28:14.941759	5	INSERT system.computadora_desc::  MODELO -> Nuevo; MARCA -> HP; SLOTS -> 4; MEMORIA MAX -> 32;
2014-12-29 23:39:00.305864	13	INSERT system.marcas:: NOMBRE -> ONEPLUS
2014-12-30 00:25:01.925772	12	INSERT system.memoria_desc::  TIPO -> DDR3; MARCA -> ONEPLUS; VELOCIDAD -> 1600Mhz;
2014-12-30 00:25:22.803494	13	INSERT system.memoria_desc::  TIPO -> DDR3; MARCA -> ONEPLUS; VELOCIDAD -> 2133Mhz;
2014-12-30 01:24:22.040199	51	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2014-12-30 01:24:22.068709	10	INSERT system.discos:: MARCA -> PHILIPS; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 51
2014-12-30 01:24:22.080424	51	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 10;
2014-12-30 09:38:54.402855	8	INSERT system.disco_desc:: MARCA -> LENOVO
2014-12-30 09:41:52.588583	9	INSERT system.disco_desc:: MARCA -> LENOVO
2014-12-30 10:24:29.551931	4	INSERT system.impresora_desc::  MODELO -> Tremendo; MARCA -> LENOVO;
2014-12-30 12:22:16.992695	2	UPDATE system.marcas::  NOMBRE -> LENOVO => LENOVOS;
2014-12-30 12:22:22.600757	2	UPDATE system.marcas::  NOMBRE -> LENOVOS => LENOVO;
2014-12-30 12:59:56.847069	25	UPDATE system.vinculos::  SECTOR -> TECNICA => MEDICA; PRODUCTO -> Monitor;
2014-12-30 13:08:34.647559	25	UPDATE system.vinculos::  SECTOR -> MEDICA => TECNICA; PRODUCTO -> Monitor;
2014-12-30 13:08:45.023934	25	UPDATE system.vinculos::  SECTOR -> TECNICA => MEDICA; PRODUCTO -> Monitor;
2014-12-30 13:09:13.896119	25	UPDATE system.vinculos::  SECTOR -> MEDICA => LEGALES; PRODUCTO -> Monitor;
2014-12-30 13:10:41.535995	25	UPDATE system.vinculos::  SECTOR -> LEGALES => MEDICA; PRODUCTO -> Monitor;
2014-12-30 13:11:00.927647	25	UPDATE system.vinculos::  SECTOR -> MEDICA => LEGALES; PRODUCTO -> Monitor;
2014-12-30 13:14:00.52021	25	UPDATE system.vinculos::  SECTOR -> LEGALES => MEDICA; PRODUCTO -> Monitor;
2014-12-30 13:14:29.858981	25	UPDATE system.vinculos::  SECTOR -> MEDICA => CARDIOPATIAS CONGENITAS; PRODUCTO -> Monitor;
2014-12-30 13:15:11.456777	25	UPDATE system.vinculos::  SECTOR -> CARDIOPATIAS CONGENITAS => LEGALES; PRODUCTO -> Monitor;
2014-12-30 13:16:18.69678	25	UPDATE system.vinculos::  SECTOR -> LEGALES => MEDICA; PRODUCTO -> Monitor;
2014-12-30 13:27:40.94864	25	UPDATE system.vinculos::  SECTOR -> MEDICA => CARDIOPATIAS CONGENITAS; PRODUCTO -> Monitor;
2014-12-30 13:28:41.680566	25	UPDATE system.vinculos::  SECTOR -> CARDIOPATIAS CONGENITAS => LEGALES; PRODUCTO -> Monitor;
2014-12-30 13:29:59.385234	25	UPDATE system.vinculos::  SECTOR -> LEGALES => TECNICA; PRODUCTO -> Monitor;
2014-12-30 13:31:23.73395	25	UPDATE system.vinculos::  SECTOR -> TECNICA => CAPITAS; PRODUCTO -> Monitor;
2014-12-30 13:37:27.260751	50	UPDATE system.vinculos::  CPU -> Sin Cpu => OPTIP21; PRODUCTO -> Monitor;
2014-12-30 13:39:43.977095	22	UPDATE system.vinculos::  CPU -> Sin Cpu => H5NF2L1; PRODUCTO -> Monitor;
2014-12-30 13:40:03.411202	22	UPDATE system.vinculos::  CPU -> H5NF2L1 => Sin Cpu; PRODUCTO -> Monitor;
2015-01-12 17:11:37.950431	49	UPDATE system.usuarios:: 
2014-12-30 13:40:18.196338	50	UPDATE system.vinculos::  CPU -> OPTIP21 => Sin Cpu; PRODUCTO -> Monitor;
2014-12-30 13:49:52.079445	30	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Memoria;
2014-12-30 14:09:20.152977	30	UPDATE system.vinculos::  SECTOR -> SISTEMAS => MEDICA; PRODUCTO -> Memoria;
2014-12-30 14:10:31.648554	30	UPDATE system.vinculos::  SECTOR -> MEDICA => STOCK; PRODUCTO -> Memoria;
2014-12-30 14:11:13.132808	30	UPDATE system.vinculos::  SECTOR -> STOCK => TECNICA; PRODUCTO -> Memoria;
2014-12-30 14:13:28.976038	30	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Memoria;
2014-12-30 14:17:48.602323	30	UPDATE system.vinculos::  SECTOR -> STOCK => LEGALES; PRODUCTO -> Memoria;
2014-12-30 14:19:40.480097	30	UPDATE system.vinculos::  SECTOR -> LEGALES => TECNICA; PRODUCTO -> Memoria;
2014-12-30 14:23:50.521309	30	UPDATE system.vinculos::  SECTOR -> TECNICA => SISTEMAS; PRODUCTO -> Memoria;
2014-12-30 14:33:20.94384	30	UPDATE system.vinculos::  SECTOR -> SISTEMAS => LEGALES; PRODUCTO -> Memoria;
2014-12-30 14:34:21.056438	30	UPDATE system.vinculos::  SECTOR -> LEGALES => MEDICA; PRODUCTO -> Memoria;
2014-12-30 14:42:04.617223	30	UPDATE system.vinculos::  SECTOR -> MEDICA => LEGALES; PRODUCTO -> Memoria;
2015-01-05 12:01:52.591449	25	UPDATE system.vinculos::  SECTOR -> CAPITAS => TECNICA; PRODUCTO -> Monitor;
2015-01-05 12:02:01.044873	25	UPDATE system.vinculos::  SECTOR -> TECNICA => LEGALES; PRODUCTO -> Monitor;
2015-01-05 12:02:17.863042	25	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; SECTOR -> LEGALES => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 12:14:57.385984	51	UPDATE system.vinculos::  SECTOR -> STOCK => MEDICA; PRODUCTO -> Disco;
2015-01-05 12:15:12.783268	51	UPDATE system.vinculos::  SECTOR -> MEDICA => STOCK; PRODUCTO -> Disco;
2015-01-05 12:23:19.054486	5	UPDATE system.usuarios::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-05 12:30:40.45764	32	UPDATE system.vinculos::  SECTOR -> STOCK => MEDICA; PRODUCTO -> Computadora;
2015-01-05 12:30:49.300015	32	UPDATE system.vinculos::  SECTOR -> MEDICA => STOCK; PRODUCTO -> Computadora;
2015-01-05 13:50:49.322596	24	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-05 13:54:12.097052	24	UPDATE system.vinculos::  PRODUCTO -> Computadora;
2015-01-05 13:57:02.261652	9	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 13:57:02.261652	10	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 13:57:02.261652	29	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Memoria;
2015-01-05 13:57:02.261652	36	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Disco;
2015-01-05 13:57:02.261652	25	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 13:59:01.591264	24	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-05 13:59:01.615157	9	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 13:59:01.615157	10	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 13:59:01.615157	29	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Memoria;
2015-01-05 13:59:01.615157	36	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Disco;
2015-01-05 13:59:01.615157	25	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 13:59:31.024755	24	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-05 13:59:31.070659	9	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 13:59:31.070659	10	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 13:59:31.070659	29	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Memoria;
2015-01-05 13:59:31.070659	36	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Disco;
2015-01-05 13:59:31.070659	25	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Monitor;
2015-01-05 14:03:00.269812	24	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-05 14:03:00.285235	9	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 14:03:00.285235	10	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 14:03:00.285235	29	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Memoria;
2015-01-05 14:03:00.285235	36	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Disco;
2015-01-05 14:03:00.285235	25	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-05 14:03:18.165772	9	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-05 14:03:18.165772	10	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-05 14:03:18.165772	29	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Memoria;
2015-01-05 14:03:18.165772	36	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Disco;
2015-01-05 14:03:18.165772	25	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-05 14:03:18.192347	24	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-05 14:03:52.192917	24	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-05 14:03:59.4493	9	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; PRODUCTO -> Monitor;
2015-01-05 14:04:05.426508	10	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; PRODUCTO -> Monitor;
2015-01-05 14:04:13.061043	25	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; PRODUCTO -> Monitor;
2015-01-05 14:04:32.495304	2	UPDATE system.computadoras:: 
2015-01-05 14:04:47.88454	29	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; PRODUCTO -> Memoria;
2015-01-05 14:13:03.654863	43	UPDATE system.vinculos::  SECTOR -> STOCK => CARDIOPATIAS CONGENITAS; PRODUCTO -> Impresora;
2015-01-05 14:13:10.848737	43	UPDATE system.vinculos::  SECTOR -> CARDIOPATIAS CONGENITAS => STOCK; PRODUCTO -> Impresora;
2015-01-05 14:13:25.337457	3	UPDATE system.impresoras:: 
2015-01-05 14:13:36.522403	3	UPDATE system.impresoras:: 
2015-01-07 16:00:57.701496	3	UPDATE system.impresoras:: 
2015-01-07 16:01:05.211675	3	UPDATE system.impresoras:: 
2015-01-07 16:07:17.074651	15	UPDATE system.computadoras:: 
2015-01-07 16:10:02.185058	30	UPDATE system.vinculos::  SECTOR -> LEGALES => TECNICA; PRODUCTO -> Memoria;
2015-01-07 16:10:08.708432	30	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Memoria;
2015-01-07 16:16:28.48512	18	UPDATE system.vinculos::  CPU -> Sin Cpu => OPTIP21; PRODUCTO -> Monitor;
2015-01-07 16:16:35.052223	22	UPDATE system.vinculos::  CPU -> Sin Cpu => OPTIP21; PRODUCTO -> Monitor;
2015-01-07 16:18:40.017878	18	UPDATE system.vinculos::  CPU -> OPTIP21 => Sin Cpu; PRODUCTO -> Monitor;
2015-01-07 16:18:53.860769	22	UPDATE system.vinculos::  CPU -> OPTIP21 => Sin Cpu; PRODUCTO -> Monitor;
2015-01-07 16:21:35.532473	2	UPDATE system.computadoras:: 
2015-01-08 10:30:51.928748	14	INSERT system.marcas:: NOMBRE -> Cisco
2015-01-08 10:38:13.513389	14	UPDATE system.marcas::  NOMBRE -> Cisco => CISCO;
2015-01-08 10:44:01.459802	1	INSERT system.router_desc::  MODELO -> E2000; MARCA -> CISCO;
2015-01-08 10:47:56.917535	5	INSERT system.impresora_desc::  MODELO -> Laser; MARCA -> HP;
2015-01-08 11:11:36.434818	15	INSERT system.marcas:: NOMBRE -> TP-LINK
2015-01-08 11:11:36.464416	1	INSERT system.switch_desc::  MODELO -> TL-SG1008D; MARCA -> TP-LINK;
2015-01-08 11:20:45.525087	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:22:34.67182	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:28:09.011942	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:29:37.275951	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:30:27.208925	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:31:44.282456	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:33:57.103961	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:41:28.423439	52	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-08 11:41:28.459814	1	INSERT system.routers:: MARCA -> CISCO; MODELO -> E2000; IP -> ; VINCULO -> 52
2015-01-08 11:41:28.475035	52	UPDATE system.vinculos::  PRODUCTO -> Router;
2015-01-08 11:48:02.428489	52	UPDATE system.vinculos::  SECTOR -> STOCK => LEGALES; PRODUCTO -> Router;
2015-01-08 11:48:09.283272	52	UPDATE system.vinculos::  SECTOR -> LEGALES => STOCK; PRODUCTO -> Router;
2015-01-08 11:50:36.372028	1	UPDATE system.routers:: 
2015-01-08 11:50:40.978958	1	UPDATE system.routers:: 
2015-01-08 11:53:00.199821	1	UPDATE system.routers:: 
2015-01-08 11:53:00.212959	1	UPDATE system.routers::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 11:53:00.212959	52	UPDATE system.vinculos::  PRODUCTO -> Router; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 11:53:28.700819	1	UPDATE system.routers::  ESTADO (ALTA LOGICA) -> 0 => 1;
2015-01-08 11:53:49.332129	52	UPDATE system.vinculos::  PRODUCTO -> Router; ESTADO (ALTA LOGICA) -> 0 => 1;
2015-01-08 11:54:00.632308	1	UPDATE system.routers:: 
2015-01-08 12:16:16.243315	1	UPDATE system.routers:: 
2015-01-08 12:16:30.293964	1	UPDATE system.routers:: 
2015-01-08 12:27:13.942969	52	UPDATE system.vinculos::  SECTOR -> STOCK => MEDICA; PRODUCTO -> Router;
2015-01-08 12:27:24.443625	52	UPDATE system.vinculos::  SECTOR -> MEDICA => STOCK; PRODUCTO -> Router;
2015-01-08 12:27:32.719151	1	UPDATE system.routers:: 
2015-01-08 12:27:37.257229	1	UPDATE system.routers:: 
2015-01-08 12:29:03.968004	1	UPDATE system.routers:: 
2015-01-08 12:29:15.703668	1	UPDATE system.routers:: 
2015-01-08 12:29:21.456482	1	UPDATE system.routers:: 
2015-01-08 12:29:27.546038	1	UPDATE system.routers:: 
2015-01-08 12:29:33.273676	1	UPDATE system.routers:: 
2015-01-08 12:33:26.046955	1	UPDATE system.routers:: 
2015-01-08 12:34:25.922437	3	UPDATE system.impresoras:: 
2015-01-08 12:34:36.644949	3	UPDATE system.impresoras:: 
2015-01-08 12:36:40.482015	51	UPDATE system.vinculos::  SECTOR -> STOCK => TECNICA; PRODUCTO -> Disco;
2015-01-08 12:36:50.397451	51	UPDATE system.vinculos::  PRODUCTO -> Disco;
2015-01-08 12:37:03.049162	51	UPDATE system.vinculos::  PRODUCTO -> Disco;
2015-01-08 12:37:13.084007	51	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Disco;
2015-01-08 12:43:11.567359	1	UPDATE system.routers:: 
2015-01-08 12:55:31.76797	1	UPDATE system.routers:: 
2015-01-08 13:02:56.353345	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:14:30.624034	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:18:36.07833	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:25:49.224598	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:26:49.437999	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:27:52.522897	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:31:50.906162	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:32:43.283515	53	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 13:32:43.309993	1	INSERT system.switchs:: MARCA -> TP-LINK; MODELO -> TL-SG1008D; IP -> ; VINCULO -> 53
2015-01-08 13:32:43.329117	53	UPDATE system.vinculos::  PRODUCTO -> Switch;
2015-01-08 13:44:30.912269	53	UPDATE system.vinculos::  SECTOR -> STOCK => TECNICA; PRODUCTO -> Switch;
2015-01-08 13:44:36.144544	53	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Switch;
2015-01-08 13:44:40.918565	1	UPDATE system.switchs:: 
2015-01-08 13:44:47.202341	1	UPDATE system.switchs:: 
2015-01-08 13:53:51.221359	1	UPDATE system.routers::  IP ->  => 123132;
2015-01-08 13:54:36.399715	1	UPDATE system.switchs::  DESCRIPCION ->  => hola;
2015-01-08 13:56:33.584946	1	UPDATE system.switchs::  DESCRIPCION -> hola => ;
2015-01-08 13:56:40.161769	1	UPDATE system.switchs::  IP ->  => 456456;
2015-01-08 13:56:44.152978	1	UPDATE system.switchs::  IP -> 456456 => ;
2015-01-08 13:56:51.346651	1	UPDATE system.routers::  IP -> 123132 => ;
2015-01-08 14:03:06.474013	53	UPDATE system.vinculos::  SECTOR -> STOCK => TECNICA; PRODUCTO -> Switch;
2015-01-08 14:03:13.652493	53	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Switch;
2015-01-08 14:05:21.780685	53	UPDATE system.vinculos::  SECTOR -> STOCK => TECNICA; PRODUCTO -> Switch;
2015-01-08 14:05:28.465743	53	UPDATE system.vinculos::  SECTOR -> TECNICA => STOCK; PRODUCTO -> Switch;
2015-01-08 14:10:25.866949	16	INSERT system.marcas:: NOMBRE -> 3COM
2015-01-08 14:10:25.896814	2	INSERT system.switch_desc::  MODELO -> 3500; MARCA -> 3COM;
2015-01-08 14:10:41.97117	54	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-08 14:10:42.013877	2	INSERT system.switchs:: MARCA -> 3COM; MODELO -> 3500; IP -> ; VINCULO -> 54
2015-01-08 14:10:42.026326	54	UPDATE system.vinculos::  PRODUCTO -> Switch; PK PRODUCTO -> 1 => 2;
2015-01-08 14:13:45.77414	17	UPDATE system.computadoras:: 
2015-01-08 14:13:45.802957	17	UPDATE system.computadoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:13:45.802957	31	UPDATE system.vinculos::  PRODUCTO -> Computadora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:13:45.810286	33	UPDATE system.vinculos::  CPU -> 5168413 => Sin Cpu; PRODUCTO -> Memoria;
2015-01-08 14:13:45.810286	8	UPDATE system.vinculos::  CPU -> 5168413 => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:16.706441	15	UPDATE system.computadoras:: 
2015-01-08 14:14:16.733618	15	UPDATE system.computadoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:16.733618	24	UPDATE system.vinculos::  PRODUCTO -> Computadora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:16.74185	9	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:16.74185	10	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:16.74185	25	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:16.74185	29	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; PRODUCTO -> Memoria;
2015-01-08 14:14:20.053016	14	UPDATE system.computadoras:: 
2015-01-08 14:14:20.074177	14	UPDATE system.computadoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:20.074177	23	UPDATE system.vinculos::  PRODUCTO -> Computadora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:20.082347	3	UPDATE system.vinculos::  CPU -> JAJAJA => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:20.082347	5	UPDATE system.vinculos::  CPU -> JAJAJA => Sin Cpu; PRODUCTO -> Monitor;
2015-01-08 14:14:20.082347	28	UPDATE system.vinculos::  CPU -> JAJAJA => Sin Cpu; PRODUCTO -> Memoria;
2015-01-08 14:14:23.126429	8	UPDATE system.computadoras:: 
2015-01-08 14:14:23.157686	8	UPDATE system.computadoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:23.157686	14	UPDATE system.vinculos::  PRODUCTO -> Computadora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:26.298789	2	UPDATE system.computadoras:: 
2015-01-08 14:14:26.340519	2	UPDATE system.computadoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:26.340519	26	UPDATE system.vinculos::  PRODUCTO -> Computadora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:38.920762	3	UPDATE system.impresoras:: 
2015-01-08 14:14:38.939168	3	UPDATE system.impresoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:38.939168	43	UPDATE system.vinculos::  PRODUCTO -> Impresora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:41.7449	1	UPDATE system.impresoras:: 
2015-01-08 14:14:41.773411	1	UPDATE system.impresoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:41.773411	37	UPDATE system.vinculos::  PRODUCTO -> Impresora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:44.531171	9	UPDATE system.impresoras:: 
2015-01-08 14:14:44.62239	9	UPDATE system.impresoras::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:44.62239	49	UPDATE system.vinculos::  PRODUCTO -> Impresora; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:57.200231	8	UPDATE system.monitores:: 
2015-01-08 14:14:57.229915	8	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:57.229915	4	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:59.962355	10	UPDATE system.monitores:: 
2015-01-08 14:14:59.970648	10	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:14:59.970648	6	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:02.363983	7	UPDATE system.monitores:: 
2015-01-08 14:15:02.396516	7	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:02.396516	3	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:05.160254	12	UPDATE system.monitores:: 
2015-01-08 14:15:05.187006	12	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:05.187006	8	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:07.773036	13	UPDATE system.monitores:: 
2015-01-08 14:15:07.79559	13	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:07.79559	9	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:10.437899	14	UPDATE system.monitores:: 
2015-01-08 14:15:10.444524	14	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:10.444524	10	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:13.083925	9	UPDATE system.monitores:: 
2015-01-08 14:15:13.120006	9	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:13.120006	5	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:15.75099	28	UPDATE system.monitores:: 
2015-01-08 14:15:15.778274	28	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:15.778274	25	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:18.271517	24	UPDATE system.monitores:: 
2015-01-08 14:15:18.302426	24	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:18.302426	19	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:20.518151	23	UPDATE system.monitores:: 
2015-01-08 14:15:20.544257	23	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:20.544257	18	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:23.146961	25	UPDATE system.monitores:: 
2015-01-08 14:15:23.152007	25	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:23.152007	20	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:25.511	27	UPDATE system.monitores:: 
2015-01-08 14:15:25.518122	27	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:25.518122	22	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:28.55386	26	UPDATE system.monitores:: 
2015-01-08 14:15:28.576926	26	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:28.576926	21	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:34.155312	30	UPDATE system.monitores:: 
2015-01-08 14:15:34.179664	30	UPDATE system.monitores::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:34.179664	50	UPDATE system.vinculos::  PRODUCTO -> Monitor; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:42.60568	2	UPDATE system.memorias::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:42.60568	28	UPDATE system.vinculos::  PRODUCTO -> Memoria; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:45.074543	3	UPDATE system.memorias::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:45.074543	29	UPDATE system.vinculos::  PRODUCTO -> Memoria; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:47.355592	4	UPDATE system.memorias::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:47.355592	30	UPDATE system.vinculos::  PRODUCTO -> Memoria; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:49.459811	5	UPDATE system.memorias::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:15:49.459811	33	UPDATE system.vinculos::  PRODUCTO -> Memoria; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:20.429529	2	UPDATE system.switchs:: 
2015-01-08 14:17:20.453928	2	UPDATE system.switchs::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:20.453928	54	UPDATE system.vinculos::  PRODUCTO -> Switch; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:31.611587	10	UPDATE system.discos:: 
2015-01-08 14:17:31.627414	10	UPDATE system.discos::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:31.627414	51	UPDATE system.vinculos::  PRODUCTO -> Disco; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:34.390843	1	UPDATE system.discos:: 
2015-01-08 14:17:34.410684	1	UPDATE system.discos::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:36.827079	2	UPDATE system.discos:: 
2015-01-08 14:17:36.851615	2	UPDATE system.discos::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:36.851615	34	UPDATE system.vinculos::  PRODUCTO -> Disco; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:39.631866	6	UPDATE system.discos:: 
2015-01-08 14:17:39.734901	6	UPDATE system.discos::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:39.734901	35	UPDATE system.vinculos::  PRODUCTO -> Disco; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:43.858373	7	UPDATE system.discos:: 
2015-01-08 14:17:43.88595	7	UPDATE system.discos::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:17:43.88595	36	UPDATE system.vinculos::  PRODUCTO -> Disco; ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-08 14:19:11.502392	55	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Computadora; ID PK PRODUCTO -> 1
2015-01-08 14:19:11.52977	22	INSERT system.computadoras:: MARCA -> LENOVO; MODELO -> ThinkCentre A25; SERIE -> 513235; VINCULO -> 55
2015-01-08 14:19:11.543213	55	UPDATE system.vinculos::  PRODUCTO -> Computadora; PK PRODUCTO -> 1 => 22;
2015-01-08 14:19:37.140993	55	UPDATE system.vinculos::  SECTOR -> STOCK => REGALO; PRODUCTO -> Computadora;
2015-01-08 14:19:56.083	55	UPDATE system.vinculos::  SECTOR -> REGALO => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-08 14:25:04.583323	55	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-08 14:25:12.691268	55	UPDATE system.vinculos::  SECTOR -> SISTEMAS => REGALO; PRODUCTO -> Computadora;
2015-01-08 14:25:48.297603	55	UPDATE system.vinculos::  PRODUCTO -> Computadora;
2015-01-08 14:26:05.48719	55	UPDATE system.vinculos::  PRODUCTO -> Computadora;
2015-01-08 14:26:46.640563	55	UPDATE system.vinculos::  SECTOR -> REGALO => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-08 14:35:29.4411	55	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-08 14:35:43.103965	55	UPDATE system.vinculos::  SECTOR -> SISTEMAS => REGALO; PRODUCTO -> Computadora;
2015-01-08 14:35:56.123409	55	UPDATE system.vinculos::  USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-08 14:36:18.022097	55	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-08 14:36:24.695545	55	UPDATE system.vinculos::  SECTOR -> REGALO => TECNICA; PRODUCTO -> Computadora;
2015-01-08 14:36:35.620645	55	UPDATE system.vinculos::  SECTOR -> TECNICA => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-08 14:39:13.734304	55	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-08 14:49:27.444185	56	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Monitor; ID PK PRODUCTO -> 1
2015-01-08 14:49:27.47494	31	INSERT system.monitores:: MARCA -> LENOVO; MODELO -> L197; SERIE -> 5613; VINCULO -> 56
2015-01-08 14:49:27.489669	56	UPDATE system.vinculos::  PRODUCTO -> Monitor; PK PRODUCTO -> 1 => 31;
2015-01-08 14:58:06.423767	57	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-08 14:58:06.53014	11	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 57
2015-01-08 14:58:06.543908	57	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 11;
2015-01-08 14:59:51.058269	57	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Disco;
2015-01-08 14:59:55.11123	57	UPDATE system.vinculos::  CPU -> HELLO => Sin Cpu; USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Disco;
2015-01-08 15:02:48.241084	58	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-08 15:08:52.617299	58	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-08 15:15:42.243185	58	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-08 15:17:07.616827	59	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-08 15:22:52.082658	60	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-08 15:22:52.113393	10	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 60
2015-01-08 15:22:52.128852	60	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 10;
2015-01-09 09:57:11.015463	61	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Monitor; ID PK PRODUCTO -> 1
2015-01-09 09:57:11.272508	32	INSERT system.monitores:: MARCA -> SAMSUNG; MODELO -> JPG-200; SERIE -> 54313; VINCULO -> 61
2015-01-09 09:57:11.333633	61	UPDATE system.vinculos::  PRODUCTO -> Monitor; PK PRODUCTO -> 1 => 32;
2015-01-09 09:57:29.827324	61	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-09 09:58:38.099188	9	INSERT system.monitor_desc::  MODELO -> CACA; MARCA -> HP;
2015-01-09 09:59:18.349246	60	UPDATE system.vinculos::  CPU -> Sin Cpu => HELLO; SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Memoria;
2015-01-09 09:59:45.206337	60	UPDATE system.vinculos::  PRODUCTO -> Memoria;
2015-01-09 10:00:26.168418	62	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 10:00:26.20091	12	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 1; UNIDAD -> TB; VINCULO -> 62
2015-01-09 10:00:26.241055	62	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 12;
2015-01-09 10:00:26.250013	63	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 10:00:26.265887	13	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 1; UNIDAD -> TB; VINCULO -> 63
2015-01-09 10:00:26.282059	63	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 13;
2015-01-09 10:00:26.290432	64	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 10:00:26.307509	14	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 1; UNIDAD -> TB; VINCULO -> 64
2015-01-09 10:00:26.324148	64	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 14;
2015-01-09 10:02:28.818716	57	UPDATE system.vinculos::  CPU -> Sin Cpu => JAJAJA; SECTOR -> SISTEMAS => COMUNICACIONES; USUARIO -> Sin usuario => Damian; PRODUCTO -> Disco;
2015-01-09 10:03:29.968352	9	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Monitor;
2015-01-09 10:03:29.968352	10	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Monitor;
2015-01-09 10:03:29.968352	25	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Monitor;
2015-01-09 10:03:29.968352	29	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Memoria;
2015-01-09 10:03:29.968352	36	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Disco;
2015-01-09 10:03:29.968352	24	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Computadora;
2015-01-09 10:03:29.968352	61	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Monitor;
2015-01-09 10:03:29.968352	60	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Memoria;
2015-01-09 10:03:29.982859	34	UPDATE system.usuarios::  SECTOR -> SISTEMAS => CAPITAS;
2015-01-09 10:09:32.675802	57	UPDATE system.vinculos::  CPU -> JAJAJA => Sin Cpu; USUARIO -> Damian => Sin usuario; PRODUCTO -> Disco;
2015-01-09 10:09:47.338282	57	UPDATE system.vinculos::  SECTOR -> COMUNICACIONES => SISTEMAS; USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Disco;
2015-01-09 10:12:08.169174	22	UPDATE system.computadoras:: 
2015-01-09 10:16:57.907877	32	UPDATE system.usuarios:: 
2015-01-09 12:55:55.830377	65	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2015-01-09 12:55:55.861906	10	INSERT system.impresoras:: MARCA -> HP; MODELO -> LaserJet P3015; IP -> 192.6.0.5; VINCULO -> 65
2015-01-09 12:55:55.909243	65	UPDATE system.vinculos::  PRODUCTO -> Impresora; PK PRODUCTO -> 1 => 10;
2015-01-09 14:50:46.340127	26	UPDATE system.usuarios:: 
2015-01-09 15:18:09.35994	61	\N
2015-01-09 15:18:09.35994	60	\N
2015-01-09 15:22:33.697427	40	DELETE system.usuarios:: NOMBRE -> mvleyton@msal.gov.ar; SECTOR -> CARDIOPATIAS CONGENITAS
2015-01-09 15:22:33.725128	36	DELETE system.usuarios:: NOMBRE -> Gustavo; SECTOR -> SISTEMAS
2015-01-09 15:22:33.758499	32	DELETE system.usuarios:: NOMBRE -> asdasd; SECTOR -> REGALO
2015-01-09 15:22:33.800408	31	DELETE system.usuarios:: NOMBRE -> Damiano; SECTOR -> TECNICA
2015-01-09 15:22:33.82529	29	DELETE system.usuarios:: NOMBRE -> Roberto Carlos; SECTOR -> LEGALES
2015-01-09 15:22:33.904113	28	DELETE system.usuarios:: NOMBRE -> Roberto Carlitos; SECTOR -> TECNICA
2015-01-09 15:22:33.916954	27	DELETE system.usuarios:: NOMBRE -> Damian Posta; SECTOR -> SISTEMAS
2015-01-09 15:22:33.925123	26	DELETE system.usuarios:: NOMBRE -> Damian; SECTOR -> COMUNICACIONES
2015-01-09 15:22:33.933438	25	DELETE system.usuarios:: NOMBRE -> Juana; SECTOR -> MEDICA
2015-01-09 15:24:04.048156	1	UPDATE system.vinculos::  PRODUCTO -> Computadora;
2015-01-09 15:26:23.116805	66	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Monitor; ID PK PRODUCTO -> 1
2015-01-09 15:26:23.148534	33	INSERT system.monitores:: MARCA -> LENOVO; MODELO -> L197; SERIE -> 888999111; VINCULO -> 66
2015-01-09 15:26:23.163307	66	UPDATE system.vinculos::  PRODUCTO -> Monitor; PK PRODUCTO -> 1 => 33;
2015-01-09 15:26:34.372079	67	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Computadora; ID PK PRODUCTO -> 1
2015-01-09 15:26:34.515807	23	INSERT system.computadoras:: MARCA -> LENOVO; MODELO -> ThinkCentre A25; SERIE -> 222333111; VINCULO -> 67
2015-01-09 15:26:34.529122	67	UPDATE system.vinculos::  PRODUCTO -> Computadora; PK PRODUCTO -> 1 => 23;
2015-01-09 15:26:45.065425	68	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-09 15:26:45.098152	11	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 8; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 68
2015-01-09 15:26:45.144715	68	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 11;
2015-01-09 15:26:45.153087	69	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-09 15:26:45.171185	12	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 8; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 69
2015-01-09 15:26:45.186262	69	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 12;
2015-01-09 15:26:45.195219	70	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-09 15:26:45.212087	13	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 8; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 70
2015-01-09 15:26:45.228712	70	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 13;
2015-01-09 15:26:55.209209	71	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 15:26:55.236837	15	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 71
2015-01-09 15:26:55.252253	71	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 15;
2015-01-09 15:26:55.260636	72	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 15:26:55.278003	16	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 72
2015-01-09 15:26:55.294381	72	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 16;
2015-01-09 15:26:55.302588	73	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 15:26:55.319512	17	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 73
2015-01-09 15:26:55.336295	73	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 17;
2015-01-09 15:26:55.344108	74	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-09 15:26:55.361293	18	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 2; UNIDAD -> TB; VINCULO -> 74
2015-01-09 15:26:55.377255	74	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 18;
2015-01-09 15:27:09.435189	75	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2015-01-09 15:27:09.469059	11	INSERT system.impresoras:: MARCA -> HP; MODELO -> LaserJet P3015; IP -> 192.6.0.111; VINCULO -> 75
2015-01-09 15:27:09.526111	75	UPDATE system.vinculos::  PRODUCTO -> Impresora; PK PRODUCTO -> 1 => 11;
2015-01-09 15:27:17.863942	76	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-09 15:27:17.902071	2	INSERT system.routers:: MARCA -> CISCO; MODELO -> E2000; IP -> ; VINCULO -> 76
2015-01-09 15:27:17.950402	76	UPDATE system.vinculos::  PRODUCTO -> Router; PK PRODUCTO -> 1 => 2;
2015-01-09 15:30:08.13193	77	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-09 15:30:08.162311	3	INSERT system.routers:: MARCA -> CISCO; MODELO -> E2000; IP -> 192.6.3.54; VINCULO -> 77
2015-01-09 15:30:08.176988	77	UPDATE system.vinculos::  PRODUCTO -> Router; PK PRODUCTO -> 1 => 3;
2015-01-09 15:30:20.216832	78	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-09 15:30:20.244229	3	INSERT system.switchs:: MARCA -> TP-LINK; MODELO -> TL-SG1008D; IP -> ; VINCULO -> 78
2015-01-09 15:30:20.267486	78	UPDATE system.vinculos::  PRODUCTO -> Switch; PK PRODUCTO -> 1 => 3;
2015-01-09 15:31:00.169044	79	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-09 15:31:00.19971	4	INSERT system.switchs:: MARCA -> TP-LINK; MODELO -> TL-SG1008D; IP -> 192.3.6.54; VINCULO -> 79
2015-01-09 15:31:00.214036	79	UPDATE system.vinculos::  PRODUCTO -> Switch; PK PRODUCTO -> 1 => 4;
2015-01-09 15:31:30.194995	66	UPDATE system.vinculos::  SECTOR -> STOCK => CAPITAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Monitor;
2015-01-09 15:31:43.581818	66	UPDATE system.vinculos::  SECTOR -> CAPITAS => SISTEMAS; PRODUCTO -> Monitor;
2015-01-09 15:31:43.601771	34	UPDATE system.usuarios::  SECTOR -> CAPITAS => SISTEMAS;
2015-01-09 15:42:54.176295	80	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Impresora; ID PK PRODUCTO -> 1
2015-01-09 15:42:54.210078	12	INSERT system.impresoras:: MARCA -> HP; MODELO -> LaserJet P3015; IP -> 192.6.0.1; VINCULO -> 80
2015-01-09 15:42:54.225572	80	UPDATE system.vinculos::  PRODUCTO -> Impresora; PK PRODUCTO -> 1 => 12;
2015-01-09 15:43:09.543044	12	UPDATE system.impresoras:: 
2015-01-09 15:43:20.483682	12	UPDATE system.impresoras:: 
2015-01-12 12:45:32.368444	16	INSERT system.areas:: NOMBRE -> COORDINACION
2015-01-12 12:46:26.01914	49	INSERT system.usuarios:: USUARIO -> martínsabignoso; NOMBRE -> Martín Sabignoso; EMAIL -> msabignoso@msal.gov.ar; SECTOR -> COORDINACION; INTERNO -> 290
2015-01-12 12:47:00.578594	50	INSERT system.usuarios:: USUARIO -> carlabonahora; NOMBRE -> Carla Bonahora; EMAIL -> clbonahora@msal.gov.ar; SECTOR -> COORDINACION; INTERNO -> 294
2015-01-12 12:47:30.619958	51	INSERT system.usuarios:: USUARIO -> sofíagiorgiutti; NOMBRE -> Sofía Giorgiutti; EMAIL -> sofi.giorgiutti@gmail.com; SECTOR -> COORDINACION; INTERNO -> 298
2015-01-12 12:48:05.215623	52	INSERT system.usuarios:: USUARIO -> lorenasantiago; NOMBRE -> Lorena Santiago; EMAIL -> ljsantiago@msal.gov.ar; SECTOR -> COORDINACION; INTERNO -> 287
2015-01-12 12:48:30.078211	3	UPDATE system.areas::  NOMBRE -> LEGALES => LEGAL;
2015-01-12 12:49:07.657759	53	INSERT system.usuarios:: USUARIO -> martínpiazza; NOMBRE -> Martín Piazza; EMAIL -> mpiazza@msal.gov.ar; SECTOR -> LEGAL; INTERNO -> 303
2015-01-12 12:49:39.641402	54	INSERT system.usuarios:: USUARIO -> marianacarbo; NOMBRE -> Mariana Carbo; EMAIL -> mcarbo.ps@msal.gov.ar; SECTOR -> LEGAL; INTERNO -> 352
2015-01-12 12:50:10.52073	55	INSERT system.usuarios:: USUARIO -> lourdestomasi; NOMBRE -> Lourdes Tomasi; EMAIL -> arealegalsumar@gmail.com; SECTOR -> LEGAL; INTERNO -> 350
2015-01-12 12:50:52.06905	56	INSERT system.usuarios:: USUARIO -> agostinafinielli; NOMBRE -> Agostina Finielli; EMAIL -> afinielli@msal.gov.ar; SECTOR -> LEGAL; INTERNO -> 354
2015-01-12 12:52:15.802057	57	INSERT system.usuarios:: USUARIO -> yanelgoya; NOMBRE -> Yanel Goya; EMAIL -> yanelgoya@gmail.com; SECTOR -> LEGAL; INTERNO -> 351
2015-01-12 13:47:33.117112	17	INSERT system.areas:: NOMBRE -> PLANIFICACION ESTRATEGICA
2015-01-12 13:48:02.68631	58	INSERT system.usuarios:: USUARIO -> analíagonzalez; NOMBRE -> Analía Gonzalez; EMAIL -> analiagonzalez22@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 156
2015-01-12 13:55:15.490789	58	UPDATE system.usuarios::  USUARIO -> analíagonzalez => analíagonzaleza;
2015-01-12 13:56:46.295742	59	\N
2015-01-12 13:59:50.664604	59	UPDATE system.usuarios::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-12 14:20:00.104785	53	UPDATE system.usuarios::  USUARIO -> martínpiazza => martinpiazza;
2015-01-12 14:20:44.145144	53	UPDATE system.usuarios::  USUARIO -> martinpiazza => martínpiazza;
2015-01-12 16:53:58.908085	81	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-12 16:53:58.962326	19	INSERT system.discos:: MARCA -> HITACHI; CAPACIDAD -> 1; UNIDAD -> TB; VINCULO -> 81
2015-01-12 16:53:58.99236	81	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 19;
2015-01-12 16:54:07.555775	81	UPDATE system.vinculos::  SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Disco;
2015-01-12 17:08:00.157551	43	UPDATE system.usuarios::  USUARIO -> maríavirginialeyton => mariavirginialeyto;
2015-01-12 17:08:15.42126	43	UPDATE system.usuarios::  USUARIO -> mariavirginialeyto => mariavirginialeyton;
2015-01-12 17:09:35.469428	43	UPDATE system.usuarios:: 
2015-01-12 17:10:11.687444	62	\N
2015-01-12 17:10:31.154451	62	UPDATE system.usuarios::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-12 17:11:03.492136	49	UPDATE system.usuarios::  USUARIO -> martínsabignoso => martinsabignoso;
2015-01-12 17:12:02.947056	51	UPDATE system.usuarios::  USUARIO -> sofíagiorgiutti => sofiagiorgiutti;
2015-01-12 17:13:07.972826	58	UPDATE system.usuarios::  USUARIO -> analíagonzaleza => analiagonzaleza;
2015-01-12 17:35:04.817188	34	UPDATE system.usuarios::  USUARIO -> alejandroc => alejandro;
2015-01-13 09:27:34.003165	6	UPDATE system.areas::  NOMBRE -> COMUNICACIONES => COMUNICACION;
2015-01-13 09:28:07.836115	63	INSERT system.usuarios:: USUARIO -> julietacarreras; NOMBRE -> Julieta Carreras; EMAIL -> julietacarreras@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 617
2015-01-13 09:28:49.415053	64	INSERT system.usuarios:: USUARIO -> valeriaterzian; NOMBRE -> Valeria Terzian; EMAIL -> valeriaterzian@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 322
2015-01-13 09:29:16.681176	65	INSERT system.usuarios:: USUARIO -> patriciopolitei; NOMBRE -> Patricio Politei; EMAIL -> patriciopolitei@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 321
2015-01-13 09:29:42.822703	66	\N
2015-01-13 09:30:10.407371	67	INSERT system.usuarios:: USUARIO -> enzosamela; NOMBRE -> Enzo Samela; EMAIL -> enzosamela@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 323
2015-01-13 09:30:33.893816	68	INSERT system.usuarios:: USUARIO -> joaquinvitali; NOMBRE -> Joaquín Vitali; EMAIL -> pydnaceruec@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 324
2015-01-13 09:31:09.373729	69	\N
2015-01-13 09:31:34.902071	70	INSERT system.usuarios:: USUARIO -> gabrielacancellaro; NOMBRE -> Gabriela Cancellaro; EMAIL -> gcancellaro@gmail.com; SECTOR -> COMUNICACION; INTERNO -> 325
2015-01-13 09:35:25.784922	71	INSERT system.usuarios:: USUARIO -> humbertosilva; NOMBRE -> Humberto Silva; EMAIL -> hsilva@msal.gov.ar; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 150
2015-01-13 09:35:50.479507	72	INSERT system.usuarios:: USUARIO -> alejandrosinland; NOMBRE -> Alejandro Sinland; EMAIL -> alejandro.sinland2@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 154
2015-01-13 09:36:14.693704	73	INSERT system.usuarios:: USUARIO -> pablomoccio; NOMBRE -> Pablo Moccio; EMAIL -> moccio.p@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 154
2015-01-13 09:36:38.41687	74	INSERT system.usuarios:: USUARIO -> carlosvallejos; NOMBRE -> Carlos Vallejos; EMAIL -> cvallejos@msal.gov.ar; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 151
2015-01-13 09:37:13.654389	75	INSERT system.usuarios:: USUARIO -> analiagonzalez; NOMBRE -> Analía Gonzalez; EMAIL -> analiagonzalez22@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 156
2015-01-13 09:37:34.86894	75	UPDATE system.usuarios::  ESTADO (BAJA LOGICA) -> 1 => 0;
2015-01-13 09:40:47.713858	58	DELETE system.usuarios:: NOMBRE -> Analía Gonzalez; SECTOR -> PLANIFICACION ESTRATEGICA
2015-01-13 09:41:20.139044	62	DELETE system.usuarios:: NOMBRE -> martínpiazza; SECTOR -> LEGAL
2015-01-13 09:41:20.165181	59	DELETE system.usuarios:: NOMBRE -> Júana; SECTOR -> CARDIOPATIAS CONGENITAS
2015-01-13 09:41:25.243077	75	DELETE system.usuarios:: NOMBRE -> Analía Gonzalez; SECTOR -> PLANIFICACION ESTRATEGICA
2015-01-13 09:41:37.819098	5	DELETE system.usuarios:: NOMBRE -> Administrador; SECTOR -> SISTEMAS
2015-01-13 09:42:33.536369	76	INSERT system.usuarios:: USUARIO -> analiagonzalez; NOMBRE -> Analía Gonzalez; EMAIL -> analiagonzalez22@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 156
2015-01-13 09:43:03.250341	77	INSERT system.usuarios:: USUARIO -> clarazerbino; NOMBRE -> Clara Zerbino; EMAIL -> clarazerbino@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 149
2015-01-13 09:43:37.065931	78	INSERT system.usuarios:: USUARIO -> fernandobazantorres; NOMBRE -> Fernando Bazan Torres; EMAIL -> fernando.bazan20@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 157
2015-01-13 09:44:06.084284	79	INSERT system.usuarios:: USUARIO -> lucianopezzuchi; NOMBRE -> Luciano Pezzuchi; EMAIL -> lucianopezzuchi@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 152
2015-01-13 09:44:32.735986	80	INSERT system.usuarios:: USUARIO -> mariaflorenciaconte; NOMBRE -> María Florencia Conte; EMAIL -> mfconte86@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 156
2015-01-13 09:45:17.915144	81	INSERT system.usuarios:: USUARIO -> lucilabelsanti; NOMBRE -> Lucila Belsanti; EMAIL -> lucilabelsanti@yahoo.com.ar; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 149
2015-01-13 09:45:42.77116	82	INSERT system.usuarios:: USUARIO -> edinsonfiayo; NOMBRE -> Edinson Fiayo; EMAIL -> edinson.fiayo@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 155
2015-01-13 09:46:07.039062	83	INSERT system.usuarios:: USUARIO -> gabrielquiroga; NOMBRE -> Gabriel Quiroga; EMAIL -> gabrielfquiroga@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 153
2015-01-13 09:46:38.478079	84	INSERT system.usuarios:: USUARIO -> damiandiazuberman; NOMBRE -> Damián Diaz Uberman; EMAIL -> ddiazsumar@hotmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 153
2015-01-13 09:47:09.399417	85	INSERT system.usuarios:: USUARIO -> maiamagnetto; NOMBRE -> Maia Magnetto; EMAIL -> maia.magnetto@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 149
2015-01-13 09:47:31.709171	86	INSERT system.usuarios:: USUARIO -> jesicaazar; NOMBRE -> Jésica Ázar; EMAIL -> jazar.sumar@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 155
2015-01-13 09:47:56.558011	87	INSERT system.usuarios:: USUARIO -> eliogrillo; NOMBRE -> Elio Grillo; EMAIL -> grillo.elio@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 151
2015-01-13 09:48:24.266659	88	INSERT system.usuarios:: USUARIO -> cesarnacucchio; NOMBRE -> Cesar Nacucchio; EMAIL -> cesar.nacucchio@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 149
2015-01-13 09:48:46.154536	89	INSERT system.usuarios:: USUARIO -> matiasitalia; NOMBRE -> Matías Italia; EMAIL -> mitalia.sumar@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 157
2015-01-13 09:49:15.747134	90	INSERT system.usuarios:: USUARIO -> lucianostrucchi; NOMBRE -> Luciano Strucchi; EMAIL -> lucianostrucchi@gmail.com; SECTOR -> PLANIFICACION ESTRATEGICA; INTERNO -> 152
2015-01-13 09:49:53.59626	18	INSERT system.areas:: NOMBRE -> SUPERVISION Y AUDITORIA
2015-01-13 09:50:36.347886	91	INSERT system.usuarios:: USUARIO -> ricardoizquierdo; NOMBRE -> Ricardo Izquierdo; EMAIL -> rizquierdo@msal.gov.ar; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 302
2015-01-13 09:51:08.831808	92	INSERT system.usuarios:: USUARIO -> lauraandreacarballal; NOMBRE -> Laura Andrea Carballal; EMAIL -> lauracarballal@hotmail.com; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 328
2015-01-13 09:51:32.007606	93	INSERT system.usuarios:: USUARIO -> mariaesterdominguez; NOMBRE -> María Ester Dominguez; EMAIL -> mdominguez@msal.gov.ar; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 336
2015-01-13 09:53:48.353833	94	INSERT system.usuarios:: USUARIO -> adrianafusco; NOMBRE -> Adriana Fusco; EMAIL -> afusco@msal.gov.ar; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 333
2015-01-13 09:54:12.821915	95	INSERT system.usuarios:: USUARIO -> claudioibanez; NOMBRE -> Claudio Ibañez; EMAIL -> cibanezsupervision@gmail.com; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 330
2015-01-13 09:54:44.697843	96	INSERT system.usuarios:: USUARIO -> carlosfarji; NOMBRE -> Carlos Farji; EMAIL -> cfarji@msal.gov.ar; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 332
2015-01-13 09:55:07.755242	97	INSERT system.usuarios:: USUARIO -> fabianalderete; NOMBRE -> Fabian Alderete; EMAIL -> faalderete@msal.gov.ar; SECTOR -> SUPERVISION Y AUDITORIA; INTERNO -> 331
2015-01-13 09:55:26.892034	19	INSERT system.areas:: NOMBRE -> COBERTURA PRESTACIONAL
2015-01-13 09:56:00.687892	98	INSERT system.usuarios:: USUARIO -> anamariasala; NOMBRE -> Ana María Sala; EMAIL -> asala@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 180
2015-01-13 09:56:22.50879	99	INSERT system.usuarios:: USUARIO -> mathiasfernandez; NOMBRE -> Mathias Fernandez; EMAIL -> mafernandez@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 186
2015-01-13 09:56:46.012767	100	INSERT system.usuarios:: USUARIO -> nataliavazquez; NOMBRE -> Natalia Vazquez; EMAIL -> nvazquez@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 181
2015-01-13 09:57:20.515491	101	INSERT system.usuarios:: USUARIO -> rominapons; NOMBRE -> Romina Pons; EMAIL -> rpons@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 176
2015-01-13 10:02:47.882678	102	INSERT system.usuarios:: USUARIO -> claudiamartinelli; NOMBRE -> Claudia Martinelli; EMAIL -> camartinelli@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 185
2015-01-13 10:03:17.92523	103	INSERT system.usuarios:: USUARIO -> arielmontero; NOMBRE -> Ariel Montero; EMAIL -> arielbio@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 173
2015-01-13 10:03:41.534816	104	INSERT system.usuarios:: USUARIO -> cecilialorencvalcarce; NOMBRE -> Cecilia Lorenc Valcarce; EMAIL -> mclorenc@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 186
2015-01-13 10:05:15.116764	105	INSERT system.usuarios:: USUARIO -> pedroantenucci; NOMBRE -> Pedro Antenucci; EMAIL -> pantenucci@msal.gov.ar; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 182
2015-01-13 10:05:42.585495	106	INSERT system.usuarios:: USUARIO -> joseluisalvesdeazevedo; NOMBRE -> Jose Luís Alves de Azevedo; EMAIL -> josealdeaz@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 173
2015-01-13 10:06:07.694522	107	INSERT system.usuarios:: USUARIO -> javierfurman; NOMBRE -> Javier Furman; EMAIL -> javierfurman1971@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 185
2015-01-13 10:06:28.62235	108	INSERT system.usuarios:: USUARIO -> carolinarebon; NOMBRE -> Carolina Rebón; EMAIL -> carolinarebon@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 175
2015-01-13 10:06:48.993123	109	INSERT system.usuarios:: USUARIO -> ayelenvanegas; NOMBRE -> Ayelén Vanegas; EMAIL -> ayevanegas@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 171
2015-01-13 10:07:16.308522	110	INSERT system.usuarios:: USUARIO -> gabrielaconcetti; NOMBRE -> Gabriela Concetti; EMAIL -> gabrielaconcetti@hotmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 172
2015-01-13 10:07:38.641255	111	INSERT system.usuarios:: USUARIO -> joseeduardorocha; NOMBRE -> José Eduardo Rocha; EMAIL -> rochajoseduardo@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 182
2015-01-13 10:07:59.152138	112	INSERT system.usuarios:: USUARIO -> estefaniadileo; NOMBRE -> Estefanía Di Leo; EMAIL -> estefaniadileo@gmail.com; SECTOR -> COBERTURA PRESTACIONAL; INTERNO -> 171
2015-01-13 10:10:18.365267	20	INSERT system.areas:: NOMBRE -> ASISTENCIA TECNICA Y CAPACITACION
2015-01-13 10:15:02.067529	113	INSERT system.usuarios:: USUARIO -> marcelaorsini; NOMBRE -> Marcela Orsini; EMAIL -> macheorsini@yahoo.com.ar; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 191
2015-01-13 10:18:26.095068	82	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Computadora; ID PK PRODUCTO -> 1
2015-01-13 10:18:26.130002	24	INSERT system.computadoras:: MARCA -> LENOVO; MODELO -> ThinkCentre A25; SERIE -> 15654564; VINCULO -> 82
2015-01-13 10:18:26.167535	82	UPDATE system.vinculos::  PRODUCTO -> Computadora; PK PRODUCTO -> 1 => 24;
2015-01-13 10:19:17.285143	81	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Disco;
2015-01-13 10:20:15.569953	82	UPDATE system.vinculos::  SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Computadora;
2015-01-13 10:20:32.685119	81	UPDATE system.vinculos::  CPU -> Sin Cpu => 15654564; USUARIO -> Sin usuario => Alejandro Crapanzano; PRODUCTO -> Disco;
2015-01-13 10:22:13.435632	81	UPDATE system.vinculos::  PRODUCTO -> Disco;
2015-01-13 10:23:51.23733	82	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Computadora;
2015-01-13 10:23:51.23733	81	UPDATE system.vinculos::  SECTOR -> SISTEMAS => CAPITAS; PRODUCTO -> Disco;
2015-01-13 10:23:51.264348	34	UPDATE system.usuarios::  SECTOR -> SISTEMAS => CAPITAS;
2015-01-13 10:27:32.728211	83	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:32.762108	14	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 83
2015-01-13 10:27:32.818832	83	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 14;
2015-01-13 10:27:32.826955	84	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:32.845633	15	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 84
2015-01-13 10:27:32.860635	84	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 15;
2015-01-13 10:27:32.869074	85	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:32.886193	16	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 85
2015-01-13 10:27:32.902763	85	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 16;
2015-01-13 10:27:32.911058	86	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:32.927558	17	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 86
2015-01-13 10:27:32.944599	86	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 17;
2015-01-13 10:27:32.952519	87	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:32.970901	18	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 87
2015-01-13 10:27:32.986014	87	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 18;
2015-01-13 10:27:32.994364	88	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:33.012113	19	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 88
2015-01-13 10:27:33.027695	88	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 19;
2015-01-13 10:27:33.035387	89	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:33.053144	20	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 89
2015-01-13 10:27:33.068917	89	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 20;
2015-01-13 10:27:33.07778	90	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:33.094859	21	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 90
2015-01-13 10:27:33.11061	90	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 21;
2015-01-13 10:27:33.119044	91	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:33.13732	22	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 91
2015-01-13 10:27:33.152325	91	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 22;
2015-01-13 10:27:33.160742	92	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Memoria; ID PK PRODUCTO -> 1
2015-01-13 10:27:33.178489	23	INSERT system.memorias:: MARCA -> SAMSUNG; CAPACIDAD -> 4; UNIDAD -> GB; VELOCIDAD -> 1333; VINCULO -> 92
2015-01-13 10:27:33.194146	92	UPDATE system.vinculos::  PRODUCTO -> Memoria; PK PRODUCTO -> 1 => 23;
2015-01-13 10:28:56.901851	81	UPDATE system.vinculos::  CPU -> 15654564 => Sin Cpu; PRODUCTO -> Disco;
2015-01-13 10:28:56.929112	82	UPDATE system.vinculos::  USUARIO -> Alejandro Crapanzano => Sin usuario; PRODUCTO -> Computadora;
2015-01-13 10:29:12.19335	82	UPDATE system.vinculos::  SECTOR -> CAPITAS => STOCK; PRODUCTO -> Computadora;
2015-01-13 10:29:30.476362	83	UPDATE system.vinculos::  CPU -> Sin Cpu => 15654564; PRODUCTO -> Memoria;
2015-01-13 10:34:29.310704	93	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Monitor; ID PK PRODUCTO -> 1
2015-01-13 10:34:29.333542	34	INSERT system.monitores:: MARCA -> SAMSUNG; MODELO -> JPG-200; SERIE -> 1113333; VINCULO -> 93
2015-01-13 10:34:29.374384	93	UPDATE system.vinculos::  PRODUCTO -> Monitor; PK PRODUCTO -> 1 => 34;
2015-01-13 10:34:36.597066	93	UPDATE system.vinculos::  CPU -> Sin Cpu => 15654564; PRODUCTO -> Monitor;
2015-01-13 10:34:41.015	93	UPDATE system.vinculos::  CPU -> 15654564 => Sin Cpu; PRODUCTO -> Monitor;
2015-01-13 10:34:48.775141	93	UPDATE system.vinculos::  SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Monitor;
2015-01-13 10:35:26.562962	94	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Disco; ID PK PRODUCTO -> 1
2015-01-13 10:35:26.594702	20	INSERT system.discos:: MARCA -> WESTERN DIGITAL; CAPACIDAD -> 1; UNIDAD -> TB; VINCULO -> 94
2015-01-13 10:35:26.635333	94	UPDATE system.vinculos::  PRODUCTO -> Disco; PK PRODUCTO -> 1 => 20;
2015-01-13 10:35:34.088895	94	UPDATE system.vinculos::  SECTOR -> STOCK => SISTEMAS; USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Disco;
2015-01-13 10:40:27.677092	84	UPDATE system.vinculos::  SECTOR -> STOCK => REGALO; PRODUCTO -> Memoria;
2015-01-13 10:43:41.366899	82	UPDATE system.vinculos::  SECTOR -> STOCK => REGALO; PRODUCTO -> Computadora;
2015-01-13 10:43:41.39358	83	UPDATE system.vinculos::  SECTOR -> STOCK => REGALO; PRODUCTO -> Memoria;
2015-01-13 10:43:55.085092	82	UPDATE system.vinculos::  USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Computadora;
2015-01-13 10:43:55.10687	83	UPDATE system.vinculos::  USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Memoria;
2015-01-13 11:11:34.956413	6	INSERT system.computadora_desc::  MODELO -> Optiplex 9030; MARCA -> DELL; SLOTS -> 2; MEMORIA MAX -> 16;
2015-01-13 11:11:50.75661	95	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Computadora; ID PK PRODUCTO -> 1
2015-01-13 11:11:50.786776	25	INSERT system.computadoras:: MARCA -> DELL; MODELO -> Optiplex 9030; SERIE -> 111333111; VINCULO -> 95
2015-01-13 11:11:50.799031	95	UPDATE system.vinculos::  PRODUCTO -> Computadora; PK PRODUCTO -> 1 => 25;
2015-01-13 11:12:48.880022	114	INSERT system.usuarios:: USUARIO -> pablopippo; NOMBRE -> Pablo Pippo; EMAIL -> pippopablod@gmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 194
2015-01-13 11:13:16.338478	115	INSERT system.usuarios:: USUARIO -> belenpoquet; NOMBRE -> Belen Poquet; EMAIL -> mbpoquet@msal.gov.ar; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 192
2015-01-13 11:13:40.860336	116	INSERT system.usuarios:: USUARIO -> silvanabrizio; NOMBRE -> Silvana Brizio; EMAIL -> sbrizio@msal.gov.ar; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 195
2015-01-13 11:14:11.532179	117	INSERT system.usuarios:: USUARIO -> paolacondina; NOMBRE -> Paola Condina; EMAIL -> pacondina@msal.gov.ar; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 195
2015-01-13 11:14:31.814317	118	INSERT system.usuarios:: USUARIO -> alejandraroses; NOMBRE -> Alejandra Roses; EMAIL -> aroses@msal.gov.ar; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 192
2015-01-13 11:22:27.339101	121	INSERT system.usuarios:: USUARIO -> nataliagennero; NOMBRE -> Natalia Gennero; EMAIL -> nataliagennero@gmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 174
2015-01-13 11:22:46.906461	122	INSERT system.usuarios:: USUARIO -> marianaalvesdasilva; NOMBRE -> Mariana Alves da Silva; EMAIL -> marianadasilva22@gmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 193
2015-01-13 11:23:10.930645	123	INSERT system.usuarios:: USUARIO -> barbaratrzenko; NOMBRE -> Bárbara Trzenko; EMAIL -> trzenko.sumarindigena@gmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 192
2015-01-13 11:23:32.514388	124	INSERT system.usuarios:: USUARIO -> emanuelaferreyra; NOMBRE -> Emanuela Ferreyra; EMAIL -> emanuela_ferreyra@hotmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 194
2015-01-13 11:23:51.685956	125	INSERT system.usuarios:: USUARIO -> anagabrielabisignano; NOMBRE -> Ana Gabriela Bisignano; EMAIL -> anabisignano@gmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 193
2015-01-13 11:24:21.353628	126	INSERT system.usuarios:: USUARIO -> sandraferral; NOMBRE -> Sandra Ferral; EMAIL -> sandraferral@hotmail.com; SECTOR -> ASISTENCIA TECNICA Y CAPACITACION; INTERNO -> 174
2015-01-13 11:24:53.325224	127	INSERT system.usuarios:: USUARIO -> alvaroocariz; NOMBRE -> Alvaro Ocariz; EMAIL -> aocariz@msal.gov.ar; SECTOR -> CAPITAS; INTERNO -> 130
2015-01-13 11:25:29.58318	128	INSERT system.usuarios:: USUARIO -> maximilianoreyes; NOMBRE -> Maximiliano Reyes; EMAIL -> mrgutierrez@msal.gov.ar; SECTOR -> CAPITAS; INTERNO -> 131
2015-01-13 11:25:56.138043	129	INSERT system.usuarios:: USUARIO -> erikalucena; NOMBRE -> Erika Lucena; EMAIL -> edlucena@msal.gov.ar; SECTOR -> CAPITAS; INTERNO -> 132
2015-01-13 11:27:42.820293	130	INSERT system.usuarios:: USUARIO -> marielfucci; NOMBRE -> Mariel Fucci; EMAIL -> mfucci@msal.gov.ar; SECTOR -> CAPITAS; INTERNO -> 132
2015-01-13 11:28:19.380304	21	INSERT system.areas:: NOMBRE -> ADMINISTRACION
2015-01-13 11:28:57.299297	131	INSERT system.usuarios:: USUARIO -> hernandiazvera; NOMBRE -> Hernán Díaz Vera; EMAIL -> hdiaz@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 265
2015-01-13 11:29:25.868985	132	INSERT system.usuarios:: USUARIO -> julietamerino; NOMBRE -> Julieta Meriño; EMAIL -> jmerino@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 286
2015-01-13 11:45:16.782236	96	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Router; ID PK PRODUCTO -> 1
2015-01-13 11:45:16.813617	4	INSERT system.routers:: MARCA -> CISCO; MODELO -> E2000; IP -> ; VINCULO -> 96
2015-01-13 11:45:16.85445	96	UPDATE system.vinculos::  PRODUCTO -> Router; PK PRODUCTO -> 1 => 4;
2015-01-13 11:47:15.435738	97	INSERT system.vinculos:: USUARIO -> Sin usuario; ID-SERIE-CPU -> 1 - Sin Cpu; SECTOR -> STOCK; PRODUCTO -> Switch; ID PK PRODUCTO -> 1
2015-01-13 11:47:15.461756	5	INSERT system.switchs:: MARCA -> TP-LINK; MODELO -> TL-SG1008D; IP -> ; VINCULO -> 97
2015-01-13 11:47:15.493362	97	UPDATE system.vinculos::  PRODUCTO -> Switch; PK PRODUCTO -> 1 => 5;
2015-01-13 12:25:00.863917	83	UPDATE system.vinculos::  USUARIO -> Rodrigo Cadaval => Sin usuario; PRODUCTO -> Memoria;
2015-01-13 12:25:00.883378	82	UPDATE system.vinculos::  USUARIO -> Rodrigo Cadaval => Sin usuario; PRODUCTO -> Computadora;
2015-01-13 12:25:31.029303	82	UPDATE system.vinculos::  SECTOR -> REGALO => SISTEMAS; PRODUCTO -> Computadora;
2015-01-13 12:25:31.05583	83	UPDATE system.vinculos::  SECTOR -> REGALO => SISTEMAS; PRODUCTO -> Memoria;
2015-01-13 12:26:43.225252	82	UPDATE system.vinculos::  USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Computadora;
2015-01-13 12:26:43.249587	83	UPDATE system.vinculos::  USUARIO -> Sin usuario => Rodrigo Cadaval; PRODUCTO -> Memoria;
2015-01-13 12:41:54.941757	133	INSERT system.usuarios:: USUARIO -> sabrinaortega; NOMBRE -> Sabrina Ortega; EMAIL -> sortega@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 295
2015-01-13 12:42:21.633268	134	INSERT system.usuarios:: USUARIO -> mauriciofabbricatore; NOMBRE -> Mauricio Fabbricatore; EMAIL -> mfabbricatore@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 293
2015-01-13 12:42:48.291021	135	INSERT system.usuarios:: USUARIO -> paolatinto; NOMBRE -> Paola Tinto; EMAIL -> ptinto@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 267
2015-01-13 12:43:13.33231	136	INSERT system.usuarios:: USUARIO -> normacarballal; NOMBRE -> Norma Carballal; EMAIL -> ngcarballal@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 228
2015-01-13 12:43:46.414982	137	INSERT system.usuarios:: USUARIO -> damianpalmeyro; NOMBRE -> Damián Palmeyro; EMAIL -> dpalmeyro@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 257
2015-01-13 12:44:09.502396	138	INSERT system.usuarios:: USUARIO -> yesicavallejos; NOMBRE -> Yesica Vallejos; EMAIL -> yrvallejos@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 280
2015-01-13 12:44:30.810502	139	INSERT system.usuarios:: USUARIO -> monicariposati; NOMBRE -> Monica Riposati; EMAIL -> mriposati@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 239
2015-01-13 12:44:53.750341	140	INSERT system.usuarios:: USUARIO -> ramiroestrada; NOMBRE -> Ramiro Estrada; EMAIL -> rjestrada@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 288
2015-01-13 12:45:13.774343	141	INSERT system.usuarios:: USUARIO -> luciabelenspiaggi; NOMBRE -> Lucía Belen Spiaggi; EMAIL -> lbspiaggi@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 277
2015-01-13 12:45:36.239634	142	INSERT system.usuarios:: USUARIO -> nadiaojeda; NOMBRE -> Nadia Ojeda; EMAIL -> nojeda@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 228
2015-01-13 12:45:56.220313	143	INSERT system.usuarios:: USUARIO -> juanpablopujol; NOMBRE -> Juan Pablo Pujol; EMAIL -> jppujol@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 255
2015-01-13 12:46:18.715502	144	INSERT system.usuarios:: USUARIO -> carolinacheylat; NOMBRE -> Carolina Cheylat; EMAIL -> ccheylat@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 296
2015-01-13 12:46:39.575856	145	INSERT system.usuarios:: USUARIO -> solmorales; NOMBRE -> Sol Morales; EMAIL -> msmorales@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 297
2015-01-13 12:47:09.00553	146	INSERT system.usuarios:: USUARIO -> danieladominguez; NOMBRE -> Daniela Dominguez; EMAIL -> ddominguez.ps@msal.gov.ar; SECTOR -> ADMINISTRACION; INTERNO -> 282
2015-01-13 12:48:10.262028	4	UPDATE system.areas:: 
2015-01-13 12:49:47.780424	4	DELETE system.areas:: NOMBRE -> MEDICA
2015-01-13 12:50:58.023968	22	INSERT system.areas:: NOMBRE -> PLANES ESPECIALES
2015-01-13 12:51:42.274696	147	INSERT system.usuarios:: USUARIO -> santiagocirio; NOMBRE -> Santiago Cirio; EMAIL -> scirio@msal.gov.ar; SECTOR -> PLANES ESPECIALES; INTERNO -> 349
2015-01-13 12:52:11.589883	148	INSERT system.usuarios:: USUARIO -> antoniaprocida; NOMBRE -> Antonia Procida; EMAIL -> aprocida@msal.gov.ar; SECTOR -> PLANES ESPECIALES; INTERNO -> 345
2015-01-13 12:52:36.186856	149	INSERT system.usuarios:: USUARIO -> carlosalbertozorzoli; NOMBRE -> Carlos Alberto Zorzoli; EMAIL -> cazorzoli@gmail.com; SECTOR -> PLANES ESPECIALES; INTERNO -> 345
2015-01-13 12:53:01.743193	150	INSERT system.usuarios:: USUARIO -> nicolasredondo; NOMBRE -> Nicolás Redondo; EMAIL -> nicolasredo@gmail.com; SECTOR -> PLANES ESPECIALES; INTERNO -> 345
2015-01-13 12:53:23.393943	151	INSERT system.usuarios:: USUARIO -> nicolasbogino; NOMBRE -> Nicolás Bogino; EMAIL -> nmbogino@msal.gov.ar; SECTOR -> PLANES ESPECIALES; INTERNO -> 347
2015-01-13 12:53:46.011403	152	INSERT system.usuarios:: USUARIO -> jesicaalderete; NOMBRE -> Jesica Alderete; EMAIL -> alderetejesi@gmail.com; SECTOR -> PLANES ESPECIALES; INTERNO -> 347
2015-01-13 12:54:19.62394	24	UPDATE system.usuarios:: 
2015-01-13 12:55:09.15033	34	UPDATE system.usuarios:: 
2015-01-13 12:55:24.185962	81	UPDATE system.vinculos::  SECTOR -> CAPITAS => SISTEMAS; PRODUCTO -> Disco;
2015-01-13 12:55:24.205401	34	UPDATE system.usuarios::  SECTOR -> CAPITAS => SISTEMAS;
2015-01-13 12:56:06.587298	35	UPDATE system.usuarios:: 
2015-01-13 12:56:37.251686	33	UPDATE system.usuarios:: 
2015-01-13 12:57:08.031144	8	UPDATE system.usuarios::  EMAIL -> rodri.cadaval@gmail.com => rodrigoplansumar@gmail.com;
2015-01-13 12:58:34.441822	5	DELETE system.areas:: NOMBRE -> TECNICA
2015-01-13 12:59:06.986822	7	DELETE system.areas:: NOMBRE -> CARDIOPATIAS CONGENITAS
2015-01-13 13:41:17.25083	83	\N
\.


--
-- Data for Name: impresora_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY impresora_desc (id_impresora_desc, id_marca, modelo, estado) FROM stdin;
1	3	LaserJet P3015	1
\.


--
-- Name: impresora_desc_id_impresora_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('impresora_desc_id_impresora_desc_seq', 5, true);


--
-- Data for Name: impresoras; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY impresoras (id_impresora, id_impresora_desc, num_serie, descripcion, estado, id_vinculo, ip) FROM stdin;
\.


--
-- Name: impresoras_id_impresora_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('impresoras_id_impresora_seq', 12, true);


--
-- Data for Name: marcas; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY marcas (nombre, id_marca, estado) FROM stdin;
SAMSUNG	1	1
HP	3	1
DELL	4	1
KINGSTON	5	1
ELPIDA	6	1
NOVATECH	7	1
WESTERN DIGITAL	8	1
SEAGATE	9	1
TOSHIBA	10	1
HITACHI	11	1
PHILIPS	12	1
ONEPLUS	13	1
LENOVO	2	1
CISCO	14	1
TP-LINK	15	1
3COM	16	1
\.


--
-- Name: marcas_id_marca_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('marcas_id_marca_seq', 16, true);


--
-- Data for Name: memoria_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY memoria_desc (id_memoria_desc, tipo, id_marca, velocidad, estado) FROM stdin;
3	DDR3	6	1600	1
6	DDR3	7	1600	1
7	DDR3	7	1333	1
8	DDR3	6	1333	1
9	DDR3	1	1333	1
10	DDR3	1	1600	1
2	DDR2	5	800	1
1	DDR3	5	1333	1
11	DDR3	5	1600	1
12	DDR3	13	1600	1
13	DDR3	13	2133	1
\.


--
-- Data for Name: memorias; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY memorias (id_memoria, id_vinculo, id_memoria_desc, id_capacidad, id_unidad, estado) FROM stdin;
\.


--
-- Name: memorias_id_memoria_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('memorias_id_memoria_seq', 13, true);


--
-- Name: memorias_id_memoria_seq1; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('memorias_id_memoria_seq1', 23, true);


--
-- Data for Name: monitor_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY monitor_desc (id_monitor_desc, modelo, id_marca, pulgadas, estado) FROM stdin;
1	JPG-200	1	15	1
5	1909WF	4	\N	1
6	L197	2	\N	1
7	LE1851W	3	\N	1
8	L1706	3	\N	1
9	CACA	3	\N	1
\.


--
-- Name: monitor_desc_id_monitor_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('monitor_desc_id_monitor_desc_seq', 9, true);


--
-- Data for Name: monitores; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY monitores (id_monitor, num_serie, id_vinculo, id_monitor_desc, estado, descripcion) FROM stdin;
\.


--
-- Name: monitores_id_monitor_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('monitores_id_monitor_seq', 34, true);


--
-- Data for Name: permisos; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY permisos (nombre, tipo_acceso) FROM stdin;
ADMINISTRADOR	1
CONSULTOR	2
CREADOR	3
\.


--
-- Data for Name: router_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY router_desc (id_router_desc, id_marca, modelo, estado) FROM stdin;
1	14	E2000	1
\.


--
-- Name: router_desc_id_router_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('router_desc_id_router_desc_seq', 1, true);


--
-- Data for Name: routers; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY routers (id_router, id_router_desc, num_serie, descripcion, estado, id_vinculo, ip) FROM stdin;
\.


--
-- Name: routers_id_router_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('routers_id_router_seq', 4, true);


--
-- Data for Name: stock; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY stock (familia, deposito, descripcion, nro_serie_pc, nro_serie_prod) FROM stdin;
1	3	una Descripcion	DPGH33WJ	\N
2	2	Descripcion del insumo		\N
\.


--
-- Data for Name: switch_desc; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY switch_desc (id_switch_desc, id_marca, modelo, estado) FROM stdin;
1	15	TL-SG1008D	1
2	16	3500	1
\.


--
-- Name: switch_desc_id_switch_desc_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('switch_desc_id_switch_desc_seq', 2, true);


--
-- Data for Name: switchs; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY switchs (id_switch, id_switch_desc, num_serie, descripcion, estado, id_vinculo, ip) FROM stdin;
\.


--
-- Name: switchs_id_switch_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('switchs_id_switch_seq', 5, true);


--
-- Data for Name: tipo_productos; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY tipo_productos (nombre, id_tipo_producto) FROM stdin;
Monitor	1
Memoria	2
Disco	3
Computadora	4
Teclado	5
Mouse	6
Impresora	7
Servidor	8
Router	9
Switch	10
\.


--
-- Name: tipo_productos_id_tipo_producto_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('tipo_productos_id_tipo_producto_seq', 11, true);


--
-- Data for Name: unidades; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY unidades (id_unidad, unidad, potencia) FROM stdin;
1	MB	0
2	GB	1
3	TB	2
\.


--
-- Name: unidades_id_unidad_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('unidades_id_unidad_seq', 3, true);


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY usuarios (nombre_apellido, id_usuario, email, password, permisos, area, usuario, estado, interno) FROM stdin;
Sin usuario	1	\N	nada	2	1	-	1	\N
Patricia De Rosso	47	paderosso@gmail.com	WTBkR01HTnRiR3BoVjBacldsaEtkbU16VG5ZPQ==	2	15	patriciaderosso	1	301
Javier Minsky	48	javier.minsky@gmail.com	WVcxR01tRlhWbms9	1	9	javier	1	162
Ricardo Izquierdo	91	rizquierdo@msal.gov.ar	WTIxc2FsbFlTbXRpTW13MlkxaFdjRnBZU210aWR6MDk=	2	18	ricardoizquierdo	1	302
Mara Spasiuk	69	maraspasiuk.mss@gmail.com	WWxkR2VWbFlUbmRaV0U1d1pGZHpQUT09	2	6	maraspasiuk	1	\N
Gabriela Cancellaro	70	gcancellaro@gmail.com	V2pKR2FXTnRiR3hpUjBacVdWYzFhbHBYZUhOWldFcDI=	2	6	gabrielacancellaro	1	325
Humberto Silva	71	hsilva@msal.gov.ar	WVVoV2RGbHRWbmxrUnpsNllWZDRNbGxSUFQwPQ==	2	17	humbertosilva	1	150
Alejandro Sinland	72	alejandro.sinland2@gmail.com	V1ZkNGJHRnRSblZhU0VwMll6SnNkV0pIUm5WYVFUMDk=	2	17	alejandrosinland	1	154
Carla Bonahora	50	clbonahora@msal.gov.ar	V1RKR2VXSkhSbWxpTWpWb1lVYzVlVmxSUFQwPQ==	2	16	carlabonahora	1	294
Lorena Santiago	52	ljsantiago@msal.gov.ar	WWtjNWVWcFhOV2hqTWtaMVpFZHNhRm95T0QwPQ==	2	16	lorenasantiago	1	287
Mariana Carbo	54	mcarbo.ps@msal.gov.ar	WWxkR2VXRlhSblZaVjA1b1kyMUtkZz09	2	3	marianacarbo	1	352
Lourdes Tomasi	55	arealegalsumar@gmail.com	WWtjNU1XTnRVbXhqTTFKMllsZEdlbUZSUFQwPQ==	2	3	lourdestomasi	1	350
Agostina Finielli	56	afinielli@msal.gov.ar	V1Zka2RtTXpVbkJpYlVadFlWYzFjRnBYZUhOaFVUMDk=	2	3	agostinafinielli	1	354
Yanel Goya	57	yanelgoya@gmail.com	WlZkR2RWcFhlRzVpTTJ4bw==	2	3	yanelgoya	1	351
Laura Andrea Carballal	92	lauracarballal@hotmail.com	WWtkR01XTnRSbWhpYlZKNVdsZEdhbGxZU21sWlYzaHpXVmQzUFE9PQ==	2	18	lauraandreacarballal	1	328
Martín Piazza	53	mpiazza@msal.gov.ar	WWxkR2VXUk5UM1JpYmtKd1dWaHdObGxSUFQwPQ==	2	3	martínpiazza	1	303
María Virginia Leyton	43	mvleyton@msal.gov.ar	WWxkR2VXRlhSakpoV0VwdVlWYzFjRmxYZUd4bFdGSjJZbWM5UFE9PQ==	2	14	mariavirginialeyton	1	101
María Ester Dominguez	93	mdominguez@msal.gov.ar	WWxkR2VXRlhSbXhqTTFKc1kyMVNkbUpYYkhWYU0xWnNaV2M5UFE9PQ==	2	18	mariaesterdominguez	1	336
Martín Sabignoso	49	msabignoso@msal.gov.ar	WWxkR2VXUkhiSFZqTWtacFlWZGtkV0l6VG5ZPQ==	2	16	martinsabignoso	1	290
Sofía Giorgiutti	51	sofi.giorgiutti@gmail.com	WXpJNWJXRlhSbTVoVnpsNVdqSnNNV1JJVW5BPQ==	2	16	sofiagiorgiutti	1	298
Julieta Carreras	63	julietacarreras@gmail.com	WVc1V2MyRlhWakJaVjA1b1kyNUtiR050Um5vPQ==	2	6	julietacarreras	1	617
Valeria Terzian	64	valeriaterzian@gmail.com	WkcxR2MxcFlTbkJaV0ZKc1kyNXdjRmxYTkQwPQ==	2	6	valeriaterzian	1	322
Patricio Politei	65	patriciopolitei@gmail.com	WTBkR01HTnRiR3BoVnpsM1lqSjRjR1JIVm5BPQ==	2	6	patriciopolitei	1	321
Florencia Sisti	66	diseno.msal@gmail.com	V20xNGRtTnRWblZaTW14b1l6SnNlbVJIYXowPQ==	2	6	florenciasisti	1	\N
Enzo Samela	67	enzosamela@gmail.com	V2xjMU5tSXpUbWhpVjFaeldWRTlQUT09	2	6	enzosamela	1	323
Joaquín Vitali	68	pydnaceruec@gmail.com	WVcwNWFHTllWbkJpYmxwd1pFZEdjMkZSUFQwPQ==	2	6	joaquinvitali	1	324
Pablo Moccio	73	moccio.p@gmail.com	WTBkR2FXSkhPWFJpTWs1cVlWYzRQUT09	2	17	pablomoccio	1	154
Carlos Vallejos	74	cvallejos@msal.gov.ar	V1RKR2VXSkhPWHBrYlVaellrZFdjV0l6VFQwPQ==	2	17	carlosvallejos	1	151
Adriana Fusco	94	afusco@msal.gov.ar	V1ZkU2VXRlhSblZaVjFveFl6Sk9kZz09	2	18	adrianafusco	1	333
Analía Gonzalez	76	analiagonzalez22@hotmail.com	V1ZjMWFHSkhiR2hhTWpsMVpXMUdjMXBZYnowPQ==	2	17	analiagonzalez	1	156
Clara Zerbino	77	clarazerbino@gmail.com	V1RKNGFHTnRSalphV0VwcFlWYzFkZz09	2	17	clarazerbino	1	149
Fernando Bazan Torres	78	fernando.bazan20@gmail.com	V20xV2VXSnRSblZhUnpscFdWaHdhR0p1VW5aamJrcHNZM2M5UFE9PQ==	2	17	fernandobazantorres	1	157
Luciano Pezzuchi	79	lucianopezzuchi@hotmail.com	WWtoV2FtRlhSblZpTTBKc1pXNXdNVmt5YUhBPQ==	2	17	lucianopezzuchi	1	152
María Florencia Conte	80	mfconte86@gmail.com	WWxkR2VXRlhSbTFpUnpsNVdsYzFhbUZYUm1waU1qVXdXbEU5UFE9PQ==	2	17	mariaflorenciaconte	1	156
Lucila Belsanti	81	lucilabelsanti@yahoo.com.ar	WWtoV2FtRlhlR2haYlZaell6SkdkV1JIYXowPQ==	2	17	lucilabelsanti	1	149
Edinson Fiayo	82	edinson.fiayo@hotmail.com	V2xkU2NHSnVUblppYlZwd1dWaHNkZz09	2	17	edinsonfiayo	1	155
Gabriel Quiroga	83	gabrielfquiroga@hotmail.com	V2pKR2FXTnRiR3hpU0VZeFlWaEtkbG95UlQwPQ==	2	17	gabrielquiroga	1	153
Damián Diaz Uberman	84	ddiazsumar@hotmail.com	V2tkR2RHRlhSblZhUjJ4b1pXNVdhVnBZU25SWlZ6UTk=	2	17	damiandiazuberman	1	153
Maia Magnetto	85	maia.magnetto@gmail.com	WWxkR2NGbFhNV2hhTWpWc1pFaFNkZz09	2	17	maiamagnetto	1	149
Jésica Ázar	86	jazar.sumar@gmail.com	WVcxV2VtRlhUbWhaV0hCb1kyYzlQUT09	2	17	jesicaazar	1	155
Elio Grillo	87	grillo.elio@gmail.com	V2xkNGNHSXlaSGxoVjNoelluYzlQUT09	2	17	eliogrillo	1	151
Cesar Nacucchio	88	cesar.nacucchio@gmail.com	V1RKV2VsbFlTblZaVjA0eFdUSk9iMkZYT0QwPQ==	2	17	cesarnacucchio	1	149
Matías Italia	89	mitalia.sumar@gmail.com	WWxkR01HRlhSbnBoV0ZKb1lrZHNhQT09	2	17	matiasitalia	1	157
Luciano Strucchi	90	lucianostrucchi@gmail.com	WWtoV2FtRlhSblZpTTA0d1kyNVdhbGt5YUhBPQ==	2	17	lucianostrucchi	1	152
Claudio Ibañez	95	cibanezsupervision@gmail.com	V1RKNGFHUlhVbkJpTW14cFdWYzFiR1ZuUFQwPQ==	2	18	claudioibanez	1	330
Carlos Farji	96	cfarji@msal.gov.ar	V1RKR2VXSkhPWHBhYlVaNVlXMXJQUT09	2	18	carlosfarji	1	332
Fabian Alderete	97	faalderete@msal.gov.ar	V20xR2FXRlhSblZaVjNocldsaEtiR1JIVlQwPQ==	2	18	fabianalderete	1	331
Ana María Sala	98	asala@msal.gov.ar	V1ZjMWFHSlhSbmxoVjBaNldWZDRhQT09	2	19	anamariasala	1	180
Mathias Fernandez	99	mafernandez@msal.gov.ar	WWxkR01HRkhiR2hqTWxwc1kyMDFhR0p0VW14bFp6MDk=	2	19	mathiasfernandez	1	186
Natalia Vazquez	100	nvazquez@msal.gov.ar	WW0xR01GbFhlSEJaV0Zwb1pXNUdNVnBZYnowPQ==	2	19	nataliavazquez	1	181
Romina Pons	101	rpons@msal.gov.ar	WTIwNWRHRlhOV2hqUnpsMVkzYzlQUT09	2	19	rominapons	1	176
Ariel Judkovsky	24	arielplan@gmail.com	V1ZoS2NGcFhkejA9	1	9	ariel	1	161
Gustavo Hekell	35	gdhekel@msal.gov.ar	V2pOV2VtUkhSakppTW1oc1lUSldjMkpCUFQwPQ==	2	9	gustavohekell	1	162
Guillermo Maekaneku	33	gavacca@msal.gov.ar	V2pOV2NHSkhlR3c9	1	9	guille	1	142
Rodrigo Cadaval	8	rodrigoplansumar@gmail.com	WVVjNWMxbFJQVDA9	3	9	admin	1	162
Claudia Martinelli	102	camartinelli@msal.gov.ar	V1RKNGFHUlhVbkJaVnpGb1kyNVNjR0p0Vm5OaVIyczk=	2	19	claudiamartinelli	1	185
Ariel Montero	103	arielbio@gmail.com	V1ZoS2NGcFhlSFJpTWpVd1dsaEtkZz09	2	19	arielmontero	1	173
Cecilia Lorenc Valcarce	104	mclorenc@msal.gov.ar	V1RKV2FtRlhlSEJaVjNoMlkyMVdkVmt6V21oaVIwNW9ZMjFPYkE9PQ==	2	19	cecilialorencvalcarce	1	186
Pedro Antenucci	105	pantenucci@msal.gov.ar	WTBkV2EyTnRPV2hpYmxKc1ltNVdhbGt5YXowPQ==	2	19	pedroantenucci	1	182
Jose Luís Alves de Azevedo	106	josealdeaz@gmail.com	WVcwNWVscFhlREZoV0U1b1lraGFiR015VW14WldIQnNaRzFXYTJKM1BUMD0=	2	19	joseluisalvesdeazevedo	1	173
Javier Furman	107	javierfurman1971@gmail.com	WVcxR01tRlhWbmxhYmxaNVlsZEdkUT09	2	19	javierfurman	1	185
Carolina Rebón	108	carolinarebon@gmail.com	V1RKR2VXSXllSEJpYlVaNVdsZEtkbUpuUFQwPQ==	2	19	carolinarebon	1	175
Ayelén Vanegas	109	ayevanegas@gmail.com	V1Zoc2JHSkhWblZrYlVaMVdsZGthR04zUFQwPQ==	2	19	ayelenvanegas	1	171
Gabriela Concetti	110	gabrielaconcetti@hotmail.com	V2pKR2FXTnRiR3hpUjBacVlqSTFhbHBZVWpCaFVUMDk=	2	19	gabrielaconcetti	1	172
José Eduardo Rocha	111	rochajoseduardo@gmail.com	WVcwNWVscFhWbXRrVjBaNVdrYzVlV0l5VG05WlVUMDk=	2	19	joseeduardorocha	1	182
Estefanía Di Leo	112	estefaniadileo@gmail.com	V2xoT01GcFhXbWhpYld4b1drZHNjMXBYT0QwPQ==	2	19	estefaniadileo	1	171
Marcela Orsini	113	macheorsini@yahoo.com.ar	WWxkR2VWa3lWbk5aVnpsNVl6SnNkV0ZSUFQwPQ==	2	20	marcelaorsini	1	191
Pablo Pippo	114	pippopablod@gmail.com	WTBkR2FXSkhPWGRoV0VKM1luYzlQUT09	2	20	pablopippo	1	194
Belen Poquet	115	mbpoquet@msal.gov.ar	V1cxV2MxcFhOWGRpTTBZeFdsaFJQUT09	2	20	belenpoquet	1	192
Silvana Brizio	116	sbrizio@msal.gov.ar	WXpKc2MyUnRSblZaVjBwNVlWaHdjR0ozUFQwPQ==	2	20	silvanabrizio	1	195
Paola Condina	117	pacondina@msal.gov.ar	WTBkR2RtSkhSbXBpTWpWcllWYzFhQT09	2	20	paolacondina	1	195
Alejandra Roses	118	aroses@msal.gov.ar	V1ZkNGJHRnRSblZhU0Vwb1kyMDVlbHBZVFQwPQ==	2	20	alejandraroses	1	192
Natalia Gennero	121	nataliagennero@gmail.com	WW0xR01GbFhlSEJaVjJSc1ltMDFiR050T0QwPQ==	2	20	nataliagennero	1	174
Mariana Alves da Silva	122	marianadasilva22@gmail.com	WWxkR2VXRlhSblZaVjBaelpHMVdlbHBIUm5waFYzZ3lXVkU5UFE9PQ==	2	20	marianaalvesdasilva	1	193
Bárbara Trzenko	123	trzenko.sumarindigena@gmail.com	V1cxR2VWbHRSbmxaV0ZKNVpXMVdkV0V5T0QwPQ==	2	20	barbaratrzenko	1	192
Emanuela Ferreyra	124	emanuela_ferreyra@hotmail.com	V2xjeGFHSnVWbXhpUjBadFdsaEtlVnBZYkhsWlVUMDk=	2	20	emanuelaferreyra	1	194
Ana Gabriela Bisignano	125	anabisignano@gmail.com	V1ZjMWFGb3lSbWxqYld4c1lrZEdhV0ZZVG5CYU1qVm9ZbTA0UFE9PQ==	2	20	anagabrielabisignano	1	193
Sandra Ferral	126	sandraferral@hotmail.com	WXpKR2RWcElTbWhhYlZaNVkyMUdjdz09	2	20	sandraferral	1	174
Alvaro Ocariz	127	aocariz@msal.gov.ar	V1ZkNE1sbFlTblppTWs1b1kyMXNOZz09	2	8	alvaroocariz	1	130
Maximiliano Reyes	128	mrgutierrez@msal.gov.ar	WWxkR05HRlhNWEJpUjJ4b1ltMDVlVnBZYkd4amR6MDk=	2	8	maximilianoreyes	1	131
Erika Lucena	129	edlucena@msal.gov.ar	V2xoS2NHRXlSbk5rVjA1c1ltMUZQUT09	2	8	erikalucena	1	132
Mariel Fucci	130	mfucci@msal.gov.ar	WWxkR2VXRlhWbk5hYmxacVdUSnJQUT09	2	8	marielfucci	1	132
Hernán Díaz Vera	131	hdiaz@msal.gov.ar	WVVkV2VXSnRSblZhUjJ4b1pXNWFiR050UlQwPQ==	2	21	hernandiazvera	1	265
Julieta Meriño	132	jmerino@msal.gov.ar	WVc1V2MyRlhWakJaVnpGc1kyMXNkV0ozUFQwPQ==	2	21	julietamerino	1	286
Sabrina Ortega	133	sortega@msal.gov.ar	WXpKR2FXTnRiSFZaVnpsNVpFZFdibGxSUFQwPQ==	2	21	sabrinaortega	1	295
Mauricio Fabbricatore	134	mfabbricatore@msal.gov.ar	WWxkR01XTnRiR3BoVnpsdFdWZEthV050YkdwWldGSjJZMjFWUFE9PQ==	2	21	mauriciofabbricatore	1	293
Paola Tinto	135	ptinto@msal.gov.ar	WTBkR2RtSkhSakJoVnpVd1luYzlQUT09	2	21	paolatinto	1	267
Norma Carballal	136	ngcarballal@msal.gov.ar	WW0wNWVXSlhSbXBaV0VwcFdWZDRjMWxYZHowPQ==	2	21	normacarballal	1	228
Damián Palmeyro	137	dpalmeyro@msal.gov.ar	V2tkR2RHRlhSblZqUjBaellsZFdOV050T0QwPQ==	2	21	damianpalmeyro	1	257
Yesica Vallejos	138	yrvallejos@msal.gov.ar	WlZkV2VtRlhUbWhrYlVaellrZFdjV0l6VFQwPQ==	2	21	yesicavallejos	1	280
Monica Riposati	139	mriposati@msal.gov.ar	WWxjNWRXRlhUbWhqYld4M1lqTk9hR1JIYXowPQ==	2	21	monicariposati	1	239
Ramiro Estrada	140	rjestrada@msal.gov.ar	WTIxR2RHRllTblphV0U0d1kyMUdhMWxSUFQwPQ==	2	21	ramiroestrada	1	288
Lucía Belen Spiaggi	141	lbspiaggi@msal.gov.ar	WWtoV2FtRlhSbWxhVjNoc1ltNU9kMkZYUm01YU1tczk=	2	21	luciabelenspiaggi	1	277
Nadia Ojeda	142	nojeda@msal.gov.ar	WW0xR2EyRlhSblpoYlZacldWRTlQUT09	2	21	nadiaojeda	1	228
Juan Pablo Pujol	143	jppujol@msal.gov.ar	WVc1V2FHSnVRbWhaYlhoMlkwaFdjV0l5ZHowPQ==	2	21	juanpablopujol	1	255
Carolina Cheylat	144	ccheylat@msal.gov.ar	V1RKR2VXSXllSEJpYlVacVlVZFdOV0pIUmpBPQ==	2	21	carolinacheylat	1	296
Sol Morales	145	msmorales@msal.gov.ar	WXpJNWMySlhPWGxaVjNoc1kzYzlQUT09	2	21	solmorales	1	297
Daniela Dominguez	146	ddominguez.ps@msal.gov.ar	V2tkR2RXRlhWbk5aVjFKMllsZHNkVm96Vm14bFp6MDk=	2	21	danieladominguez	1	282
Santiago Cirio	147	scirio@msal.gov.ar	WXpKR2RXUkhiR2hhTWpscVlWaEtjR0ozUFQwPQ==	2	22	santiagocirio	1	349
Antonia Procida	148	aprocida@msal.gov.ar	V1ZjMU1HSXlOWEJaV0VKNVlqSk9jRnBIUlQwPQ==	2	22	antoniaprocida	1	345
Carlos Alberto Zorzoli	149	cazorzoli@gmail.com	V1RKR2VXSkhPWHBaVjNocFdsaEtNR0l6Y0haamJuQjJZa2RyUFE9PQ==	2	22	carlosalbertozorzoli	1	345
Nicolás Redondo	150	nicolasredo@gmail.com	WW0xc2FtSXllR2hqTTBwc1drYzVkVnBIT0QwPQ==	2	22	nicolasredondo	1	345
Nicolás Bogino	151	nmbogino@msal.gov.ar	WW0xc2FtSXllR2hqTWtwMldqSnNkV0ozUFQwPQ==	2	22	nicolasbogino	1	347
Jesica Alderete	152	alderetejesi@gmail.com	WVcxV2VtRlhUbWhaVjNocldsaEtiR1JIVlQwPQ==	2	22	jesicaalderete	1	347
Alejandro Crapanzano	34	alejandroplan@gmail.com	V1ZkNGJHRnRSblZhU0VwMg==	1	9	alejandro	1	161
\.


--
-- Name: usuarioseq_id_usuario_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('usuarioseq_id_usuario_seq', 152, true);


--
-- Data for Name: vinculos; Type: TABLE DATA; Schema: system; Owner: postgres
--

COPY vinculos (id_vinculo, id_usuario, id_sector, id_cpu, id_tipo_producto, id_pk_producto, estado) FROM stdin;
1	1	1	1	4	1	1
\.


--
-- Name: vinculos_id_vinculo_seq; Type: SEQUENCE SET; Schema: system; Owner: postgres
--

SELECT pg_catalog.setval('vinculos_id_vinculo_seq', 97, true);


--
-- Name: area_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY areas
    ADD CONSTRAINT area_pkey PRIMARY KEY (id_area);


--
-- Name: capacidad_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY capacidades
    ADD CONSTRAINT capacidad_pkey PRIMARY KEY (id_capacidad);


--
-- Name: computadora_desc_modelo_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY computadora_desc
    ADD CONSTRAINT computadora_desc_modelo_key UNIQUE (modelo);


--
-- Name: computadora_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY computadora_desc
    ADD CONSTRAINT computadora_desc_pkey PRIMARY KEY (id_computadora_desc);


--
-- Name: computadoras_id_vinculo_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY computadoras
    ADD CONSTRAINT computadoras_id_vinculo_key UNIQUE (id_vinculo);


--
-- Name: comutadoras_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY computadoras
    ADD CONSTRAINT comutadoras_pkey PRIMARY KEY (id_computadora);


--
-- Name: disco_desc_id_marca_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY disco_desc
    ADD CONSTRAINT disco_desc_id_marca_key UNIQUE (id_marca);


--
-- Name: disco_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY disco_desc
    ADD CONSTRAINT disco_desc_pkey PRIMARY KEY (id_disco_desc);


--
-- Name: discos_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY discos
    ADD CONSTRAINT discos_pkey PRIMARY KEY (id_disco);


--
-- Name: impresora_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY impresora_desc
    ADD CONSTRAINT impresora_desc_pkey PRIMARY KEY (id_impresora_desc);


--
-- Name: impresoras_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY impresoras
    ADD CONSTRAINT impresoras_pkey PRIMARY KEY (id_impresora);


--
-- Name: marcas_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY marcas
    ADD CONSTRAINT marcas_pkey PRIMARY KEY (id_marca);


--
-- Name: memorias_id_vinculo_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_id_vinculo_key UNIQUE (id_vinculo);


--
-- Name: memorias_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY memoria_desc
    ADD CONSTRAINT memorias_pkey PRIMARY KEY (id_memoria_desc);


--
-- Name: memorias_pkey1; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_pkey1 PRIMARY KEY (id_memoria);


--
-- Name: monitor_desc_modelo_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monitor_desc
    ADD CONSTRAINT monitor_desc_modelo_key UNIQUE (modelo);


--
-- Name: monitor_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monitor_desc
    ADD CONSTRAINT monitor_desc_pkey PRIMARY KEY (id_monitor_desc);


--
-- Name: monitores_num_serie_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monitores
    ADD CONSTRAINT monitores_num_serie_key UNIQUE (num_serie);


--
-- Name: monitores_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monitores
    ADD CONSTRAINT monitores_pkey PRIMARY KEY (id_monitor);


--
-- Name: permiso_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT permiso_pkey PRIMARY KEY (tipo_acceso);


--
-- Name: router_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY router_desc
    ADD CONSTRAINT router_desc_pkey PRIMARY KEY (id_router_desc);


--
-- Name: routers_num_serie_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY routers
    ADD CONSTRAINT routers_num_serie_key UNIQUE (num_serie);


--
-- Name: routers_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY routers
    ADD CONSTRAINT routers_pkey PRIMARY KEY (id_router);


--
-- Name: switch_desc_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY switch_desc
    ADD CONSTRAINT switch_desc_pkey PRIMARY KEY (id_switch_desc);


--
-- Name: switchs_num_serie_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY switchs
    ADD CONSTRAINT switchs_num_serie_key UNIQUE (num_serie);


--
-- Name: switchs_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY switchs
    ADD CONSTRAINT switchs_pkey PRIMARY KEY (id_switch);


--
-- Name: tipo_productos_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipo_productos
    ADD CONSTRAINT tipo_productos_pkey PRIMARY KEY (id_tipo_producto);


--
-- Name: unidades_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY unidades
    ADD CONSTRAINT unidades_pkey PRIMARY KEY (id_unidad);


--
-- Name: usuarios_usuario_key; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_usuario_key UNIQUE (usuario);


--
-- Name: usuarioseq_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarioseq_pkey PRIMARY KEY (id_usuario);


--
-- Name: vinculos_pkey; Type: CONSTRAINT; Schema: system; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY vinculos
    ADD CONSTRAINT vinculos_pkey PRIMARY KEY (id_vinculo);


--
-- Name: registrar_movimientos_insert_marcas; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER registrar_movimientos_insert_marcas AFTER INSERT ON marcas FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_marcas();


--
-- Name: trigger_eliminar_vinc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_eliminar_vinc AFTER DELETE ON memorias FOR EACH ROW EXECUTE PROCEDURE public.eliminar_vin();


--
-- Name: trigger_eliminar_vinc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_eliminar_vinc AFTER DELETE ON monitores FOR EACH ROW EXECUTE PROCEDURE public.eliminar_vin();


--
-- Name: trigger_eliminar_vinc_y_asoc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_eliminar_vinc_y_asoc AFTER DELETE ON computadoras FOR EACH ROW EXECUTE PROCEDURE public.eliminar_vin_comp();


--
-- Name: trigger_registrar_movimientos_delete_areas; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_delete_areas AFTER DELETE ON areas FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_delete_areas();


--
-- Name: trigger_registrar_movimientos_delete_usuarios; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_delete_usuarios AFTER DELETE ON usuarios FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_delete_usuarios();


--
-- Name: trigger_registrar_movimientos_insert_areas; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_areas AFTER INSERT ON areas FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_areas();


--
-- Name: trigger_registrar_movimientos_insert_computadora_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_computadora_desc AFTER INSERT ON computadora_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_computadora_desc();


--
-- Name: trigger_registrar_movimientos_insert_computadoras; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_computadoras AFTER INSERT ON computadoras FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_computadoras();


--
-- Name: trigger_registrar_movimientos_insert_disco_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_disco_desc AFTER INSERT ON disco_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_disco_desc();


--
-- Name: trigger_registrar_movimientos_insert_discos; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_discos AFTER INSERT ON discos FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_discos();


--
-- Name: trigger_registrar_movimientos_insert_impresora_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_impresora_desc AFTER INSERT ON impresora_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_impresora_desc();


--
-- Name: trigger_registrar_movimientos_insert_impresoras; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_impresoras AFTER INSERT ON impresoras FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_impresoras();


--
-- Name: trigger_registrar_movimientos_insert_memoria_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_memoria_desc AFTER INSERT ON memoria_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_memoria_desc();


--
-- Name: trigger_registrar_movimientos_insert_memorias; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_memorias AFTER INSERT ON memorias FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_memorias();


--
-- Name: trigger_registrar_movimientos_insert_monitor_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_monitor_desc AFTER INSERT ON monitor_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_monitor_desc();


--
-- Name: trigger_registrar_movimientos_insert_monitores; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_monitores AFTER INSERT ON monitores FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_monitores();


--
-- Name: trigger_registrar_movimientos_insert_router_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_router_desc AFTER INSERT ON router_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_router_desc();


--
-- Name: trigger_registrar_movimientos_insert_routers; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_routers AFTER INSERT ON routers FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_routers();


--
-- Name: trigger_registrar_movimientos_insert_switch_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_switch_desc AFTER INSERT ON switch_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_switch_desc();


--
-- Name: trigger_registrar_movimientos_insert_switchs; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_switchs AFTER INSERT ON switchs FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_switchs();


--
-- Name: trigger_registrar_movimientos_insert_usuarios; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_usuarios AFTER INSERT ON usuarios FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_usuarios();


--
-- Name: trigger_registrar_movimientos_insert_vinculos; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_insert_vinculos AFTER INSERT ON vinculos FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_insert_vinculos();


--
-- Name: trigger_registrar_movimientos_update_areas; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_areas AFTER UPDATE ON areas FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_areas();


--
-- Name: trigger_registrar_movimientos_update_computadoras; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_computadoras AFTER UPDATE ON computadoras FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_computadoras();


--
-- Name: trigger_registrar_movimientos_update_disco_desc; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_disco_desc AFTER UPDATE ON disco_desc FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_disco_desc();


--
-- Name: trigger_registrar_movimientos_update_discos; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_discos AFTER UPDATE ON discos FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_discos();


--
-- Name: trigger_registrar_movimientos_update_impresoras; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_impresoras AFTER UPDATE ON impresoras FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_impresoras();


--
-- Name: trigger_registrar_movimientos_update_marcas; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_marcas AFTER UPDATE ON marcas FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_marcas();


--
-- Name: trigger_registrar_movimientos_update_memorias; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_memorias AFTER UPDATE ON memorias FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_memorias();


--
-- Name: trigger_registrar_movimientos_update_monitores; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_monitores AFTER UPDATE ON monitores FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_monitores();


--
-- Name: trigger_registrar_movimientos_update_routers; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_routers AFTER UPDATE ON routers FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_routers();


--
-- Name: trigger_registrar_movimientos_update_switchs; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_switchs AFTER UPDATE ON switchs FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_switchs();


--
-- Name: trigger_registrar_movimientos_update_usuarios; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_usuarios AFTER UPDATE ON usuarios FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_usuarios();


--
-- Name: trigger_registrar_movimientos_update_vinculos; Type: TRIGGER; Schema: system; Owner: postgres
--

CREATE TRIGGER trigger_registrar_movimientos_update_vinculos AFTER UPDATE ON vinculos FOR EACH ROW EXECUTE PROCEDURE public.registrar_movimientos_update_vinculos();


--
-- Name: comutadoras_id_computadora_desc_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY computadoras
    ADD CONSTRAINT comutadoras_id_computadora_desc_fkey FOREIGN KEY (id_computadora_desc) REFERENCES computadora_desc(id_computadora_desc);


--
-- Name: disco_desc_id_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY disco_desc
    ADD CONSTRAINT disco_desc_id_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: discos_id_capacidad_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY discos
    ADD CONSTRAINT discos_id_capacidad_fkey FOREIGN KEY (id_capacidad) REFERENCES capacidades(id_capacidad);


--
-- Name: discos_id_disco_desc_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY discos
    ADD CONSTRAINT discos_id_disco_desc_fkey FOREIGN KEY (id_disco_desc) REFERENCES disco_desc(id_disco_desc);


--
-- Name: discos_id_unidad_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY discos
    ADD CONSTRAINT discos_id_unidad_fkey FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad);


--
-- Name: discos_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY discos
    ADD CONSTRAINT discos_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo);


--
-- Name: impresora_desc_id_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY impresora_desc
    ADD CONSTRAINT impresora_desc_id_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: impresoras_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY impresoras
    ADD CONSTRAINT impresoras_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo);


--
-- Name: memorias_id_capacidad_fkey1; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_id_capacidad_fkey1 FOREIGN KEY (id_capacidad) REFERENCES capacidades(id_capacidad);


--
-- Name: memorias_id_memoria_desc_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_id_memoria_desc_fkey FOREIGN KEY (id_memoria_desc) REFERENCES memoria_desc(id_memoria_desc);


--
-- Name: memorias_id_unidad_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_id_unidad_fkey FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad);


--
-- Name: memorias_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memorias
    ADD CONSTRAINT memorias_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo);


--
-- Name: memorias_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY memoria_desc
    ADD CONSTRAINT memorias_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: monitor_desc_id_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY monitor_desc
    ADD CONSTRAINT monitor_desc_id_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: monitores_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY monitores
    ADD CONSTRAINT monitores_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo) ON DELETE CASCADE;


--
-- Name: router_desc_id_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY router_desc
    ADD CONSTRAINT router_desc_id_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: routers_id_router_desc_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY routers
    ADD CONSTRAINT routers_id_router_desc_fkey FOREIGN KEY (id_router_desc) REFERENCES router_desc(id_router_desc);


--
-- Name: routers_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY routers
    ADD CONSTRAINT routers_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo);


--
-- Name: switch_desc_id_marca_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY switch_desc
    ADD CONSTRAINT switch_desc_id_marca_fkey FOREIGN KEY (id_marca) REFERENCES marcas(id_marca);


--
-- Name: switchs_id_vinculo_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY switchs
    ADD CONSTRAINT switchs_id_vinculo_fkey FOREIGN KEY (id_vinculo) REFERENCES vinculos(id_vinculo);


--
-- Name: usuarioseq_area_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarioseq_area_fkey FOREIGN KEY (area) REFERENCES areas(id_area);


--
-- Name: usuarioseq_permisos_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarioseq_permisos_fkey FOREIGN KEY (permisos) REFERENCES permisos(tipo_acceso);


--
-- Name: vinculos_id_sector_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY vinculos
    ADD CONSTRAINT vinculos_id_sector_fkey FOREIGN KEY (id_sector) REFERENCES areas(id_area);


--
-- Name: vinculos_id_usuario_fkey; Type: FK CONSTRAINT; Schema: system; Owner: postgres
--

ALTER TABLE ONLY vinculos
    ADD CONSTRAINT vinculos_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

