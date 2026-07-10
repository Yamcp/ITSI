-- ============================================================
-- LIMPIEZA: borrar duplicado en minúsculas
-- La tabla REAL es TAB_PRACTICAS_PREPROFESIONALES (MAYÚSCULAS)
-- ============================================================

-- 1) Confirmar datos en la tabla REAL
SELECT ID_PRACTICA_PREPROFESIONAL, ID_ESTUDIANTE, ESTADO_PRACTICA
FROM TAB_PRACTICAS_PREPROFESIONALES
ORDER BY ID_PRACTICA_PREPROFESIONAL;

-- 2) Ver a qué tabla apunta la FK
SELECT CONSTRAINT_NAME, TABLE_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'if0_42374420_bdditsi'
  AND CONSTRAINT_NAME = 'FK_DOCS_PREPROFESIONALES_PRACTICA';

-- 3) BORRAR el duplicado vacío (minúsculas) — ejecuta esto
DROP TABLE IF EXISTS `tab_practicas_preprofesionales`;

-- 4) Verificar que solo quede la de MAYÚSCULAS
SHOW TABLES LIKE '%practicas_preprofesionales%';
-- Debe mostrar SOLO: TAB_PRACTICAS_PREPROFESIONALES
