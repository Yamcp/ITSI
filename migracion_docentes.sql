/*==============================================================*/
/* NOTA: Esta migración ya está integrada en bddITSI.sql.       */
/* Este archivo se mantiene solo como referencia.                */
/*                                                               */
/* Si tu BD ya fue creada SIN la columna ID_DOCENTE_TUTOR,       */
/* ejecuta los siguientes ALTER para actualizar:                 */
/*==============================================================*/

-- 1. Agregar columna ID_DOCENTE_TUTOR a prácticas preprofesionales
ALTER TABLE TAB_PRACTICAS_PREPROFESIONALES
    ADD COLUMN ID_DOCENTE_TUTOR int NULL AFTER ID_INSTRUCTOR;

ALTER TABLE TAB_PRACTICAS_PREPROFESIONALES
    ADD CONSTRAINT FK_PRACTICAS_DOCENTE_TUTOR
    FOREIGN KEY (ID_DOCENTE_TUTOR) REFERENCES TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR)
    ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE TAB_PRACTICAS_PREPROFESIONALES
    ADD KEY IDX_DOCENTE_TUTOR (ID_DOCENTE_TUTOR);

-- 2. Agregar columna ID_DOCENTE_TUTOR a servicio comunitario
ALTER TABLE TAB_SERVICIO_COMUNITARIO
    ADD COLUMN ID_DOCENTE_TUTOR int NULL AFTER ID_INSTRUCTOR;

ALTER TABLE TAB_SERVICIO_COMUNITARIO
    ADD CONSTRAINT FK_SERVICIO_DOCENTE_TUTOR
    FOREIGN KEY (ID_DOCENTE_TUTOR) REFERENCES TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR)
    ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE TAB_SERVICIO_COMUNITARIO
    ADD KEY IDX_DOCENTE_TUTOR (ID_DOCENTE_TUTOR);

-- 3. Verificación
SELECT 'Migración completada exitosamente' AS RESULTADO;
