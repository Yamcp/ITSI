# Revisión de conexión con la base de datos - ITSI

## Configuración

- **Archivo:** `app/Config/Database.php`
- **Base de datos:** `itsi`
- **Driver:** MySQLi, localhost, usuario `root`, sin contraseña por defecto.

La aplicación usa el grupo por defecto `default` y se conecta correctamente mediante CodeIgniter 4.

---

## Cambios realizados (alineación con `bddITSI.sql`)

El esquema real en `bddITSI.sql` usa **nombres de tablas con prefijo `TAB_`** (por ejemplo `TAB_PRACTICAS_PREPROFESIONALES`). Se corrigieron referencias que usaban nombres en minúsculas o distintos.

### Modelos

| Modelo | Antes | Después |
|-------|--------|---------|
| `PracticasPreprofesionalesModel` | `practicas_preprofesionales` | `TAB_PRACTICAS_PREPROFESIONALES` |
| `ServiciosComunitariosModel` | `servicios_comunitarios` | `TAB_SERVICIO_COMUNITARIO` |
| `DocumentosPracticasModel` (consultas internas) | `practicas_preprofesionales` | `TAB_PRACTICAS_PREPROFESIONALES` |

- **PracticasPreprofesionalesModel:** Cálculo de horas cumplidas pasado de tabla inexistente `actividades_practicas` a `TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES` (campo `HORAS_CUMPLIDAS`). `allowedFields` actualizado a columnas reales (`ID_INSTRUCTOR`, `ID_ASIGNACION_PRACTICA`, etc.).
- **ServiciosComunitariosModel:** Igual con `TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO`. `useTimestamps` desactivado porque la tabla no tiene `FECHA_CREACION`/`FECHA_ACTUALIZACION`. `allowedFields` alineado con la BD.

### Controladores

- **DashboardEstudianteController:** Consultas de prácticas y servicio comunitario pasan a `TAB_PRACTICAS_PREPROFESIONALES` y `TAB_SERVICIO_COMUNITARIO`.
- **PracticasEstudianteController:**
  - Uso de `TAB_PRACTICAS_PREPROFESIONALES`, `TAB_SERVICIO_COMUNITARIO`, `TAB_ESTUDIANTES`, `TAB_CARRERAS`, `TAB_INSTITUCIONES_CONVENIOS`.
  - Relación estudiante–usuario por `TAB_USUARIOS.ID_DATO_PERSONA` = `TAB_ESTUDIANTES.ID_DATO_PERSONA` (no existe `ID_USUARIO` en `TAB_ESTUDIANTES`).
  - Supervisor: uso de `ID_INSTRUCTOR` y resolución de teléfono vía `TAB_EMPLEADOS_INSTRUCTORES` y `TAB_EMPLEADOS`.
  - Horas cumplidas calculadas con `TAB_ASISTENCIAS_*` y `TAB_SEGUIMIENTO_*` en lugar de `actividades_practicas`.

---

## Tablas / vistas que debe tener la BD

Asegúrese de que en MySQL existan:

1. **Vista `V_PERIODO_ACADEMICO_ACTUAL`**  
   Usada en: `DashboardAdminController`, `DashboardDocenteController`, navbar (admin/docente/estudiante), `AuthController`.  
   Definida en `bddITSI.sql` (CREATE OR REPLACE VIEW ...).

2. **Tabla `TAB_INSCRIPCIONES_ACTIVIDADES`**  
   Script: `app/Database/TAB_INSCRIPCIONES_ACTIVIDADES.sql`.  
   Ejecutar si usa inscripciones a actividades educativas.

3. **Tabla `TAB_RECUPERACION_CONTRASENA`**  
   Migración: `app/Database/Migrations/2026-03-03-000001_CreateTabRecuperacionContrasena.php`.  
   Necesaria para recuperación de contraseña.

---

## Modelos que usan tablas no definidas en `bddITSI.sql`

En el script principal **no** aparecen:

- **`actividades_practicas`** – Referida en `ActividadesPracticasModel`.  
  Si no existe en su BD, ese modelo no funcionará hasta crear la tabla o cambiar la lógica (por ejemplo a `TAB_ASISTENCIAS_*` / `TAB_SEGUIMIENTO_*`).
- **`evaluaciones_practicas`** – Referida en `EvaluacionesPracticasModel`.  
  En `bddITSI.sql` sí existen `TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES` y `TAB_EVALUACIONES_SERVICIO_COMUNITARIO` (estructura distinta).  
  Si usa solo el esquema de `bddITSI.sql`, conviene usar esos modelos o adaptar `EvaluacionesPracticasModel` a esas tablas.

`PracticasDocenteController` y `PracticasEstudianteController` usan `ActividadesPracticasModel`; las rutas de horas cumplidas ya se cambiaron a `TAB_ASISTENCIAS_*` y `TAB_SEGUIMIENTO_*`, por lo que el dashboard y listados de prácticas no dependen de `actividades_practicas`.

---

## Resumen

- **Conexión:** Correcta (`Database.php` + grupo `default`).
- **Nombres de tablas:** Unificados con el esquema `TAB_*` de `bddITSI.sql` en los puntos revisados.
- **Relaciones:** Estudiante–usuario e instructor–empleado corregidas donde se tocó.
- **Pendiente opcional:** Crear o sustituir uso de `actividades_practicas` / `evaluaciones_practicas` si desea que esos modelos funcionen con el mismo esquema.

Para comprobar la conexión puede ejecutar:  
`php spark db:table TAB_USUARIOS` (o cualquier tabla) o el script `test_database_connection.php` si existe en el proyecto.
