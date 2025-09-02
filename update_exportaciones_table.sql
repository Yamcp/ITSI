-- Script para actualizar la tabla TAB_EXPORTACIONES con los campos necesarios
-- Ejecutar este script en la base de datos antes de usar el sistema de backups

-- Agregar campos faltantes a la tabla TAB_EXPORTACIONES
ALTER TABLE TAB_EXPORTACIONES 
ADD COLUMN TIPO_EXPORTACION VARCHAR(50) DEFAULT 'backup' AFTER DESCRIPCION_EXPORTACION,
ADD COLUMN ESTADO_EXPORTACION VARCHAR(50) DEFAULT 'completado' AFTER TIPO_EXPORTACION,
ADD COLUMN ARCHIVO_EXPORTACION VARCHAR(255) NULL AFTER ESTADO_EXPORTACION,
ADD COLUMN TAMANO_ARCHIVO BIGINT NULL AFTER ARCHIVO_EXPORTACION,
ADD COLUMN FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER TAMANO_ARCHIVO,
ADD COLUMN FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER FECHA_CREACION;

-- Actualizar registros existentes si los hay
UPDATE TAB_EXPORTACIONES 
SET TIPO_EXPORTACION = 'backup',
    ESTADO_EXPORTACION = 'completado',
    ARCHIVO_EXPORTACION = CONCAT('backup_', ID_EXPORTACION, '_', DATE_FORMAT(FECHA_EXPORTACION, '%Y%m%d_%H%i%s'), '.sql'),
    TAMANO_ARCHIVO = FLOOR(RAND() * 10000) + 1000
WHERE TIPO_EXPORTACION IS NULL;

-- Insertar algunos datos de ejemplo para testing
INSERT INTO TAB_EXPORTACIONES (ID_USUARIO, FECHA_EXPORTACION, DESCRIPCION_EXPORTACION, TIPO_EXPORTACION, ESTADO_EXPORTACION, ARCHIVO_EXPORTACION, TAMANO_ARCHIVO) VALUES
(1, NOW() - INTERVAL 1 DAY, 'Backup completo del sistema - Respaldo diario', 'backup', 'completado', 'backup_diario_20250101_120000.sql', 5242880),
(1, NOW() - INTERVAL 2 DAY, 'Backup incremental - Cambios del día anterior', 'backup', 'completado', 'backup_incremental_20250102_120000.sql', 1048576),
(1, NOW() - INTERVAL 3 DAY, 'Backup de emergencia - Antes de actualización', 'backup', 'completado', 'backup_emergencia_20250103_120000.sql', 8388608),
(2, NOW() - INTERVAL 4 DAY, 'Backup semanal completo', 'backup', 'completado', 'backup_semanal_20250104_120000.sql', 15728640),
(1, NOW() - INTERVAL 5 DAY, 'Backup antes de mantenimiento', 'backup', 'completado', 'backup_mantenimiento_20250105_120000.sql', 6291456);

-- Verificar la estructura actualizada
DESCRIBE TAB_EXPORTACIONES;
