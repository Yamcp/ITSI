-- =============================================================
-- Migración: TAB_DETALLES_CONVENIOS - Carrera y Plazas
-- Ejecutar en MySQL (phpMyAdmin o consola) sobre la base ITSI.
-- Si aparece "Unknown column 'dc.ID_CARRERA'" ejecuta este script.
-- Si alguna línea da "Duplicate column name", omítela (ya existe).
-- =============================================================

-- 1. Agregar columna ID_CARRERA (convenio destinado a una carrera)
ALTER TABLE TAB_DETALLES_CONVENIOS
ADD COLUMN ID_CARRERA INT NULL AFTER ID_INSTITUCION_CONVENIO;

-- Asignar una carrera por defecto a convenios existentes (reemplaza 1 por un ID_CARRERA válido de TAB_CARRERAS si es necesario)
UPDATE TAB_DETALLES_CONVENIOS SET ID_CARRERA = 1 WHERE ID_CARRERA IS NULL;

-- Hacer la columna obligatoria para nuevos registros
ALTER TABLE TAB_DETALLES_CONVENIOS MODIFY COLUMN ID_CARRERA INT NOT NULL;

-- Clave foránea a TAB_CARRERAS
ALTER TABLE TAB_DETALLES_CONVENIOS
ADD CONSTRAINT FK_DETALLES_CONVENIOS_CARRERA
FOREIGN KEY (ID_CARRERA) REFERENCES TAB_CARRERAS (ID_CARRERA) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- 2. Agregar columna PLAZAS_DISPONIBLES (plazas para prácticas)
ALTER TABLE TAB_DETALLES_CONVENIOS
ADD COLUMN PLAZAS_DISPONIBLES INT NOT NULL DEFAULT 0 AFTER RENOVABLE;
