# Sistema Completo de Documentos de Prácticas y Servicio Comunitario

## Descripción
Este sistema permite gestionar todos los documentos relacionados con las **Prácticas Preprofesionales** y **Servicio Comunitario** de los estudiantes del Instituto Tecnológico Superior Ibarra (ITSI).

## Archivos Incluidos

### 1. `script_completo_documentos_ambas_modalidades.sql`
Script principal que actualiza la base de datos con todas las tablas necesarias para ambas modalidades.

**Contenido:**
- Creación de tablas comunes para ambas modalidades
- Actualización de tablas existentes de documentos
- Inserción de datos iniciales
- Creación de vistas unificadas
- Procedimientos almacenados para ambas modalidades

### 2. `datos_ejemplo_ambas_modalidades.sql`
Script con datos de ejemplo que incluyen ambas modalidades.

**Contenido:**
- Estudiantes de ejemplo
- Prácticas preprofesionales y servicios comunitarios
- Documentos con diferentes estados para ambas modalidades
- Notificaciones y historial de cambios
- Asignaciones de docentes tutores

## Instalación

### Paso 1: Ejecutar el script principal
```sql
-- Ejecutar en MySQL/MariaDB
SOURCE script_completo_documentos_ambas_modalidades.sql;
```

### Paso 2: (Opcional) Insertar datos de ejemplo
```sql
-- Si desea datos de ejemplo para probar
SOURCE datos_ejemplo_ambas_modalidades.sql;
```

## Estructura del Sistema

### Modalidades Soportadas

#### 🎓 Prácticas Preprofesionales
- **Duración**: 240 horas
- **Modalidad**: Presencial
- **Objetivo**: Desarrollo de competencias profesionales
- **Documentos**: 12 tipos obligatorios (PPR-001 a PPR-012)

#### 🌍 Servicio Comunitario
- **Duración**: 96 horas
- **Modalidad**: Presencial/Virtual
- **Objetivo**: Impacto social en la comunidad
- **Documentos**: 12 tipos obligatorios (PSC-001 a PSC-012)

### Tablas del Sistema

#### **Tablas Comunes (Ambas Modalidades)**

##### `TAB_ESTADOS_REVISIONES`
Estados de revisión unificados:
- **Pendiente** - Documento pendiente de revisión
- **En Revisión** - Documento siendo revisado
- **Aprobado** - Documento aprobado
- **Rechazado** - Documento rechazado
- **Requiere Corrección** - Documento que necesita correcciones

##### `TAB_ENTIDADES_RECEPTORAS`
Entidades donde se realizan las prácticas:
- Hospital San Vicente de Paúl
- Banco del Pacífico
- Fundación Niños del Ecuador
- Municipio de Ibarra
- Empresa Tecnológica XYZ
- Casa de la Cultura Ecuatoriana
- Fundación Telefónica

##### `TAB_DOCENTES_TUTORES`
Docentes tutores especializados:
- **Dr. Mario Montenegro** - Administración Educativa (Rector)
- **Ing. Juan Pérez** - Desarrollo de Software (Coordinador)
- **Mg. María González** - Psicología Educativa (Tutora)
- **Lic. Ana Ruiz** - Trabajo Social (Servicio Comunitario)
- **Ing. Carlos Mendoza** - Ingeniería Civil (Proyectos Comunitarios)

##### `TAB_ASIGNACIONES_DOCENTES_PRACTICAS`
Relación entre docentes y prácticas/servicios:
- Soporta ambas modalidades
- Tipos de asignación: Principal, Suplente, Co-tutor
- Fechas de asignación y finalización

##### `TAB_HISTORIAL_CAMBIOS_DOCUMENTOS`
Registro de cambios en documentos:
- Soporta ambas modalidades
- Tipos de cambio: Estado, Observaciones, Archivo
- Información del usuario y fecha

##### `TAB_NOTIFICACIONES_DOCUMENTOS`
Sistema de notificaciones:
- Soporta ambas modalidades
- Tipos: Nuevo, Revisado, Aprobado, Rechazado, Requiere Corrección
- Sistema de lectura/no lectura

#### **Tablas Específicas por Modalidad**

##### `TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES` (Actualizada)
Documentos de prácticas preprofesionales con campos adicionales:
- `ID_ESTADO_REVISION` - Estado actual
- `NOMBRE_ORIGINAL` - Nombre original del archivo
- `TAMANO_ARCHIVO` - Tamaño en bytes
- `RUTA_ARCHIVO` - Ruta de almacenamiento
- `FECHA_REVISION` - Fecha de última revisión
- `ID_REVISOR` - Usuario revisor
- `OBSERVACIONES_REVISOR` - Comentarios del revisor
- `VERSION` - Versión del documento
- `ACTIVO` - Estado activo/inactivo

##### `TAB_DOCUMENTOS_SERVICIO_COMUNITARIO` (Actualizada)
Documentos de servicio comunitario con los mismos campos adicionales.

### Vistas del Sistema

#### `V_DOCUMENTOS_PRACTICAS_COMPLETOS`
Vista completa para documentos de prácticas preprofesionales con:
- Información del documento
- Datos del estudiante
- Entidad receptora
- Docente tutor
- Estado actual

#### `V_DOCUMENTOS_SERVICIO_COMPLETOS`
Vista completa para documentos de servicio comunitario con la misma estructura.

#### `V_DOCUMENTOS_UNIFICADOS`
Vista unificada que combina ambas modalidades:
```sql
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE TIPO_MODALIDAD = 'PRACTICAS' 
ORDER BY FECHA_SUBIDA DESC;
```

### Procedimientos Almacenados

#### `SP_CAMBIAR_ESTADO_DOCUMENTO_PRACTICAS`
Cambia el estado de un documento de prácticas preprofesionales:
```sql
CALL SP_CAMBIAR_ESTADO_DOCUMENTO_PRACTICAS(
    1,                    -- ID del documento
    3,                    -- Nuevo estado (3 = Aprobado)
    17,                   -- ID del revisor
    'Documento aprobado correctamente'  -- Observaciones
);
```

#### `SP_CAMBIAR_ESTADO_DOCUMENTO_SERVICIO`
Cambia el estado de un documento de servicio comunitario:
```sql
CALL SP_CAMBIAR_ESTADO_DOCUMENTO_SERVICIO(
    1,                    -- ID del documento
    3,                    -- Nuevo estado (3 = Aprobado)
    19,                   -- ID del revisor
    'Documento aprobado correctamente'  -- Observaciones
);
```

## Tipos de Documentos

### Prácticas Preprofesionales (PPR-001 a PPR-012)
1. **PPR-001** - Oficio de Asignación de Tutor
2. **PPR-002** - Oficio a Entidad Receptora
3. **PPR-003** - Carta de Aceptación
4. **PPR-004** - Solicitud Institucional Valorada
5. **PPR-005** - Certificado de Culminación de Horas
6. **PPR-006** - Hojas de Asistencia
7. **PPR-007** - Ficha de Registro de Actividades
8. **PPR-008** - Rúbrica de Evaluación de Entidad
9. **PPR-009** - Ficha de Control y Seguimiento Docente
10. **PPR-010** - Rúbrica de Evaluación Docente
11. **PPR-011** - Rúbrica de Evaluación de Resultados
12. **PPR-012** - Evidencia Fotográfica y Digital

### Servicio Comunitario (PSC-001 a PSC-012)
1. **PSC-001** - Oficio de Asignación de Tutor
2. **PSC-002** - Oficio a Entidad Receptora
3. **PSC-003** - Carta de Aceptación
4. **PSC-004** - Solicitud Institucional Valorada
5. **PSC-005** - Certificado de Culminación de Horas
6. **PSC-006** - Hojas de Asistencia
7. **PSC-007** - Ficha de Registro de Actividades
8. **PSC-008** - Rúbrica de Evaluación de Entidad
9. **PSC-009** - Ficha de Control y Seguimiento Docente
10. **PSC-010** - Rúbrica de Evaluación Docente
11. **PSC-011** - Rúbrica de Evaluación de Resultados
12. **PSC-012** - Evidencia Fotográfica y Digital

## Consultas Útiles

### Obtener Documentos por Modalidad
```sql
-- Solo prácticas preprofesionales
SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS 
WHERE ESTADO_REVISION = 'Pendiente';

-- Solo servicio comunitario
SELECT * FROM V_DOCUMENTOS_SERVICIO_COMPLETOS 
WHERE ESTADO_REVISION = 'Pendiente';

-- Ambas modalidades
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE ESTADO_REVISION = 'Pendiente';
```

### Estadísticas por Modalidad
```sql
-- Documentos por estado - Prácticas Preprofesionales
SELECT 
    'PRACTICAS_PREPROFESIONALES' as MODALIDAD,
    er.ESTADO,
    COUNT(*) as CANTIDAD
FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp
LEFT JOIN TAB_ESTADOS_REVISIONES er ON dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
GROUP BY er.ESTADO, er.ORDEN
ORDER BY er.ORDEN;

-- Documentos por estado - Servicio Comunitario
SELECT 
    'SERVICIO_COMUNITARIO' as MODALIDAD,
    er.ESTADO,
    COUNT(*) as CANTIDAD
FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds
LEFT JOIN TAB_ESTADOS_REVISIONES er ON ds.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
GROUP BY er.ESTADO, er.ORDEN
ORDER BY er.ORDEN;
```

### Filtrar por Estudiante
```sql
-- Buscar documentos de un estudiante específico
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE CEDULA_ESTUDIANTE = '1001234567';
```

### Filtrar por Entidad Receptora
```sql
-- Buscar documentos por entidad
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE ENTIDAD_RECEPTORA LIKE '%Hospital%';
```

## Integración con CodeIgniter

### Modelos Actualizados

#### DocumentosPracticasModel
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

#### DocumentosServicioComunitarioModel
```php
protected $allowedFields = [
    'ID_SERVICIO_COMUNITARIO',
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

### Controladores

#### Obtener Documentos por Modalidad
```php
public function obtenerDocumentosPracticas()
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS ORDER BY FECHA_SUBIDA DESC");
    return $query->getResultArray();
}

public function obtenerDocumentosServicio()
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM V_DOCUMENTOS_SERVICIO_COMPLETOS ORDER BY FECHA_SUBIDA DESC");
    return $query->getResultArray();
}

public function obtenerDocumentosUnificados()
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM V_DOCUMENTOS_UNIFICADOS ORDER BY FECHA_SUBIDA DESC");
    return $query->getResultArray();
}
```

## Características del Sistema

### ✅ Funcionalidades Implementadas

1. **Gestión Unificada** - Ambas modalidades en un solo sistema
2. **Estados de Revisión** - Sistema completo de estados
3. **Historial de Cambios** - Registro detallado de modificaciones
4. **Notificaciones** - Sistema de alertas automáticas
5. **Docentes Tutores** - Asignación especializada por modalidad
6. **Entidades Receptoras** - Base de datos completa
7. **Vistas Unificadas** - Consultas optimizadas
8. **Procedimientos Almacenados** - Operaciones automatizadas
9. **Validaciones** - Integridad de datos
10. **Auditoría** - Trazabilidad completa

### 📊 Datos de Ejemplo Incluidos

- **Estudiantes**: 16 estudiantes de diferentes carreras
- **Prácticas Preprofesionales**: 5 prácticas con 17 documentos
- **Servicios Comunitarios**: 5 servicios con 14 documentos
- **Estados**: Documentos en todos los estados posibles
- **Notificaciones**: 31 notificaciones de ejemplo
- **Historial**: 18 registros de cambios
- **Docentes**: 5 tutores especializados
- **Entidades**: 7 entidades receptoras

## Notas Importantes

1. **Backup**: Siempre hacer backup antes de ejecutar los scripts
2. **Permisos**: Usuario de BD debe tener permisos completos
3. **Compatibilidad**: MySQL 5.7+ / MariaDB 10.2+
4. **Rutas**: Ajustar rutas de archivos según configuración
5. **Seguridad**: Implementar validaciones adicionales en la aplicación

## Soporte

Para dudas o problemas con la implementación, contactar al equipo de desarrollo del sistema ITSI.

---

**Sistema de Gestión Académica ITSI**  
*Documentos de Prácticas Preprofesionales y Servicio Comunitario*
