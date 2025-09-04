# Sistema de Documentos de Prácticas Preprofesionales

## Descripción
Este sistema permite gestionar todos los documentos relacionados con las prácticas preprofesionales de los estudiantes del Instituto Tecnológico Superior Ibarra (ITSI).

## Archivos Incluidos

### 1. `script_completo_documentos_practicas.sql`
Script principal que actualiza la base de datos con todas las tablas necesarias para el sistema de documentos de prácticas.

**Contenido:**
- Creación de tablas nuevas para estados de revisión, entidades receptoras, docentes tutores, etc.
- Actualización de la tabla existente `TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES`
- Inserción de datos iniciales
- Creación de vista para consultas completas
- Procedimiento almacenado para cambiar estados de documentos

### 2. `actualizacion_tablas_documentos_practicas.sql`
Script detallado con la estructura completa de todas las tablas necesarias.

**Contenido:**
- Definición completa de todas las tablas
- Restricciones de clave foránea
- Índices para optimización
- Comentarios de tablas
- Datos de ejemplo

### 3. `datos_ejemplo_documentos_practicas.sql`
Script con datos de ejemplo que coinciden con lo mostrado en la vista.

**Contenido:**
- Estudiantes de ejemplo
- Prácticas preprofesionales de ejemplo
- Documentos con diferentes estados
- Notificaciones de ejemplo
- Historial de cambios

## Instalación

### Paso 1: Ejecutar el script principal
```sql
-- Ejecutar en MySQL/MariaDB
SOURCE script_completo_documentos_practicas.sql;
```

### Paso 2: (Opcional) Insertar datos de ejemplo
```sql
-- Si desea datos de ejemplo para probar
SOURCE datos_ejemplo_documentos_practicas.sql;
```

## Estructura de la Base de Datos

### Tablas Principales

#### `TAB_ESTADOS_REVISIONES`
Maneja los diferentes estados de revisión de documentos:
- Pendiente
- En Revisión
- Aprobado
- Rechazado
- Requiere Corrección

#### `TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES` (Actualizada)
Tabla principal que almacena los documentos con campos adicionales:
- `ID_ESTADO_REVISION`: Estado actual del documento
- `NOMBRE_ORIGINAL`: Nombre original del archivo
- `TAMANO_ARCHIVO`: Tamaño del archivo en bytes
- `RUTA_ARCHIVO`: Ruta donde se almacena el archivo
- `FECHA_REVISION`: Fecha de la última revisión
- `ID_REVISOR`: Usuario que revisó el documento
- `OBSERVACIONES_REVISOR`: Comentarios del revisor
- `VERSION`: Versión del documento
- `ACTIVO`: Si el documento está activo

#### `TAB_ENTIDADES_RECEPTORAS`
Información de las entidades donde se realizan las prácticas:
- Hospital San Vicente de Paúl
- Banco del Pacífico
- Fundación Niños del Ecuador
- Municipio de Ibarra
- Empresa Tecnológica XYZ

#### `TAB_DOCENTES_TUTORES`
Información de los docentes tutores:
- Dr. Mario Montenegro (Rector)
- Ing. Juan Pérez (Coordinador)
- Mg. María González (Tutora)

#### `TAB_ASIGNACIONES_DOCENTES_PRACTICAS`
Relación entre docentes tutores y prácticas específicas.

#### `TAB_HISTORIAL_CAMBIOS_DOCUMENTOS`
Registro de todos los cambios realizados en los documentos.

#### `TAB_NOTIFICACIONES_DOCUMENTOS`
Sistema de notificaciones para cambios de estado.

### Vista Principal

#### `V_DOCUMENTOS_PRACTICAS_COMPLETOS`
Vista que combina toda la información relevante:
- Datos del documento
- Información del estudiante
- Información de la entidad receptora
- Información del docente tutor
- Estado actual del documento

## Funcionalidades Implementadas

### 1. Gestión de Estados
- Cambio de estado de documentos
- Historial de cambios
- Notificaciones automáticas

### 2. Información Completa
- Datos del estudiante
- Entidad receptora
- Docente tutor asignado
- Tipo de documento

### 3. Seguimiento
- Historial de cambios
- Observaciones del revisor
- Versiones de documentos

### 4. Notificaciones
- Notificaciones por cambio de estado
- Sistema de lectura/no lectura
- Diferentes tipos de notificaciones

## Uso en la Aplicación

### Consultar Documentos
```sql
-- Obtener todos los documentos con información completa
SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS;

-- Filtrar por estado
SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS 
WHERE ESTADO_REVISION = 'Pendiente';

-- Filtrar por estudiante
SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS 
WHERE CEDULA_ESTUDIANTE = '1001234567';
```

### Cambiar Estado de Documento
```sql
-- Usar el procedimiento almacenado
CALL SP_CAMBIAR_ESTADO_DOCUMENTO(
    1,                    -- ID del documento
    3,                    -- Nuevo estado (3 = Aprobado)
    17,                   -- ID del revisor
    'Documento aprobado correctamente'  -- Observaciones
);
```

### Obtener Estadísticas
```sql
-- Documentos por estado
SELECT 
    er.ESTADO,
    COUNT(*) as CANTIDAD
FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp
LEFT JOIN TAB_ESTADOS_REVISIONES er ON dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
GROUP BY er.ESTADO, er.ORDEN
ORDER BY er.ORDEN;
```

## Integración con CodeIgniter

### Modelo Actualizado
El modelo `DocumentosPracticasModel` debe actualizarse para incluir los nuevos campos:

```php
protected $allowedFields = [
    'ID_PRACTICA_PREPROFESIONAL',
    'ID_TIPO_DOCUMENTO',
    'ID_ESTADO_REVISION',
    'NOMBRE_ARCHIVO',
    'NOMBRE_ORIGINAL',
    'TIPO_ARCHIVO',
    'TAMANO_ARCHIVO',
    'RUTA_ARCHIVO',
    'FECHA_SUBIDA',
    'FECHA_REVISION',
    'ID_REVISOR',
    'OBSERVACIONES',
    'OBSERVACIONES_REVISOR',
    'VERSION',
    'ACTIVO'
];
```

### Controlador
El controlador puede usar la vista para obtener información completa:

```php
public function obtenerDocumentosCompletos()
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS ORDER BY FECHA_SUBIDA DESC");
    return $query->getResultArray();
}
```

## Notas Importantes

1. **Backup**: Siempre hacer backup de la base de datos antes de ejecutar los scripts.

2. **Permisos**: Asegurarse de que el usuario de base de datos tenga permisos para crear tablas, vistas y procedimientos.

3. **Compatibilidad**: Los scripts están diseñados para MySQL 5.7+ y MariaDB 10.2+.

4. **Rutas de Archivos**: Ajustar las rutas de archivos según la configuración del servidor.

5. **Seguridad**: Los scripts incluyen validaciones básicas, pero se recomienda implementar validaciones adicionales en la aplicación.

## Soporte

Para cualquier duda o problema con la implementación, contactar al equipo de desarrollo del sistema ITSI.
