-- Ejecutar una vez en bases ya creadas (añade enlace virtual / reunión en línea).
ALTER TABLE TAB_ACTIVIDADES_EDUCACION
    ADD COLUMN ENLACE VARCHAR(500) NULL DEFAULT NULL AFTER LUGAR;
