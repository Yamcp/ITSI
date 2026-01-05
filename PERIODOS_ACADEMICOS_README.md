# Sistema de Períodos Académicos - ITSI

## Descripción General

El sistema de períodos académicos permite organizar y filtrar todas las actividades del instituto por períodos específicos, facilitando la gestión y consulta de datos históricos y actuales.

## Estructura de la Base de Datos

### Tabla Principal: TAB_PERIODOS_ACADEMICOS

```sql
CREATE TABLE TAB_PERIODOS_ACADEMICOS (
    ID_PERIODO_ACADEMICO int NOT NULL AUTO_INCREMENT,
    NOMBRE_PERIODO varchar(100) NOT NULL,
    AÑO_ACADEMICO int NOT NULL,
    FECHA_INICIO date NOT NULL,
    FECHA_FIN date NOT NULL,
    TIPO_PERIODO enum('Semestre', 'Trimestre', 'Cuatrimestre', 'Anual') NOT NULL DEFAULT 'Semestre',
    NUMERO_PERIODO int NOT NULL,
    ESTADO enum('Activo', 'Inactivo', 'Finalizado', 'Planificado') NOT NULL DEFAULT 'Planificado',
    DESCRIPCION text,
    FECHA_CREACION timestamp DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ACTIVO boolean DEFAULT true,
    PRIMARY KEY (ID_PERIODO_ACADEMICO)
);
```

### Campos Agregados a Tablas Existentes

Se agregó el campo `ID_PERIODO_ACADEMICO` a las siguientes tablas:
- `TAB_ASIGNACIONES_PRACTICAS`
- `TAB_PRACTICAS_PREPROFESIONALES`
- `TAB_SERVICIO_COMUNITARIO`
- `TAB_ACTIVIDADES_EDUCACION`

## Vistas Disponibles

### 1. V_PERIODO_ACADEMICO_ACTUAL
Obtiene el período académico actualmente activo.

```sql
SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL;
```

### 2. V_PERIODOS_ACADEMICOS_ORDENADOS
Lista todos los períodos académicos ordenados por año y número.

```sql
SELECT * FROM V_PERIODOS_ACADEMICOS_ORDENADOS;
```

### 3. V_ESTADISTICAS_PERIODOS
Proporciona estadísticas completas de cada período académico.

```sql
SELECT * FROM V_ESTADISTICAS_PERIODOS;
```

### 4. V_DOCUMENTOS_POR_PERIODO
Documentos filtrados por período académico.

```sql
SELECT * FROM V_DOCUMENTOS_POR_PERIODO WHERE ID_PERIODO_ACADEMICO = 4;
```

### 5. V_PRACTICAS_POR_PERIODO
Prácticas preprofesionales filtradas por período.

```sql
SELECT * FROM V_PRACTICAS_POR_PERIODO WHERE ID_PERIODO_ACADEMICO = 4;
```

### 6. V_SERVICIOS_POR_PERIODO
Servicios comunitarios filtrados por período.

```sql
SELECT * FROM V_SERVICIOS_POR_PERIODO WHERE ID_PERIODO_ACADEMICO = 4;
```

### 7. V_DOCUMENTOS_UNIFICADOS (Actualizada)
Vista unificada que incluye información de períodos académicos.

```sql
SELECT * FROM V_DOCUMENTOS_UNIFICADOS WHERE ID_PERIODO_ACADEMICO = 4;
```

## Procedimientos Almacenados

### 1. SP_OBTENER_PERIODO_ACTUAL()
Obtiene el período académico actualmente activo.

```sql
CALL SP_OBTENER_PERIODO_ACTUAL();
```

### 2. SP_ESTADISTICAS_PERIODO(p_id_periodo)
Obtiene estadísticas detalladas de un período específico.

```sql
CALL SP_ESTADISTICAS_PERIODO(4);
```

### 3. SP_CAMBIAR_ESTADO_PERIODO(p_id_periodo, p_nuevo_estado)
Cambia el estado de un período académico.

```sql
CALL SP_CAMBIAR_ESTADO_PERIODO(4, 'Finalizado');
```

### 4. SP_DOCUMENTOS_POR_PERIODO(p_id_periodo, p_tipo_modalidad)
Obtiene documentos filtrados por período y tipo de modalidad.

```sql
-- Todos los documentos del período 4
CALL SP_DOCUMENTOS_POR_PERIODO(4, NULL);

-- Solo documentos de prácticas del período 4
CALL SP_DOCUMENTOS_POR_PERIODO(4, 'PRACTICAS');

-- Solo documentos de servicio comunitario del período 4
CALL SP_DOCUMENTOS_POR_PERIODO(4, 'SERVICIO_COMUNITARIO');
```

## Consultas Útiles

### Obtener Período Actual
```sql
SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL;
```

### Listar Todos los Períodos
```sql
SELECT 
    ID_PERIODO_ACADEMICO,
    NOMBRE_PERIODO,
    AÑO_ACADEMICO,
    TIPO_PERIODO,
    ESTADO,
    CONCAT(NOMBRE_PERIODO, ' - ', AÑO_ACADEMICO) as PERIODO_COMPLETO
FROM TAB_PERIODOS_ACADEMICOS 
WHERE ACTIVO = true
ORDER BY AÑO_ACADEMICO DESC, NUMERO_PERIODO DESC;
```

### Obtener Estadísticas de un Período
```sql
CALL SP_ESTADISTICAS_PERIODO(4);
```

### Filtrar Documentos por Período
```sql
-- Documentos del período actual
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE ID_PERIODO_ACADEMICO = (SELECT ID_PERIODO_ACADEMICO FROM V_PERIODO_ACADEMICO_ACTUAL);

-- Documentos de un período específico
SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
WHERE ID_PERIODO_ACADEMICO = 4;
```

### Filtrar Prácticas por Período
```sql
-- Prácticas del período actual
SELECT * FROM V_PRACTICAS_POR_PERIODO 
WHERE ID_PERIODO_ACADEMICO = (SELECT ID_PERIODO_ACADEMICO FROM V_PERIODO_ACADEMICO_ACTUAL);

-- Prácticas de un período específico
SELECT * FROM V_PRACTICAS_POR_PERIODO 
WHERE ID_PERIODO_ACADEMICO = 4;
```

### Filtrar Servicios Comunitarios por Período
```sql
-- Servicios del período actual
SELECT * FROM V_SERVICIOS_POR_PERIODO 
WHERE ID_PERIODO_ACADEMICO = (SELECT ID_PERIODO_ACADEMICO FROM V_PERIODO_ACADEMICO_ACTUAL);

-- Servicios de un período específico
SELECT * FROM V_SERVICIOS_POR_PERIODO 
WHERE ID_PERIODO_ACADEMICO = 4;
```

## Gestión de Períodos Académicos

### Crear un Nuevo Período
```sql
INSERT INTO TAB_PERIODOS_ACADEMICOS (
    NOMBRE_PERIODO, 
    AÑO_ACADEMICO, 
    FECHA_INICIO, 
    FECHA_FIN, 
    TIPO_PERIODO, 
    NUMERO_PERIODO, 
    ESTADO, 
    DESCRIPCION
) VALUES (
    'Primer Semestre', 
    2026, 
    '2026-01-15', 
    '2026-06-30', 
    'Semestre', 
    1, 
    'Planificado', 
    'Primer semestre académico del año 2026'
);
```

### Activar un Período
```sql
CALL SP_CAMBIAR_ESTADO_PERIODO(5, 'Activo');
```

### Finalizar un Período
```sql
CALL SP_CAMBIAR_ESTADO_PERIODO(4, 'Finalizado');
```

## Integración con el Sistema

### En las Vistas PHP
```php
// Obtener período actual
$periodoActual = $db->query("SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL")->getRow();

// Obtener documentos del período actual
$documentos = $db->query("
    SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
    WHERE ID_PERIODO_ACADEMICO = ?
", [$periodoActual->ID_PERIODO_ACADEMICO])->getResult();
```

### Filtros por Período
```php
// Obtener períodos para un dropdown
$periodos = $db->query("SELECT * FROM V_PERIODOS_ACADEMICOS_ORDENADOS")->getResult();

// Filtrar por período seleccionado
$periodoId = $this->request->getPost('periodo_id');
$documentos = $db->query("
    SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
    WHERE ID_PERIODO_ACADEMICO = ?
", [$periodoId])->getResult();
```

## Beneficios del Sistema

1. **Organización Temporal**: Todos los datos están organizados por períodos académicos.
2. **Filtrado Fácil**: Puedes filtrar cualquier vista por período específico.
3. **Historial Completo**: Acceso a datos de períodos anteriores.
4. **Estadísticas por Período**: Análisis detallado de cada período académico.
5. **Gestión Centralizada**: Control centralizado de períodos académicos.
6. **Compatibilidad**: Todas las vistas existentes mantienen su funcionalidad.

## Notas Importantes

- El sistema mantiene solo un período activo por año académico.
- Al activar un período, automáticamente se desactivan otros períodos del mismo año.
- Todas las vistas existentes han sido actualizadas para incluir información de períodos.
- Los procedimientos almacenados facilitan la gestión de períodos.
- El sistema es retrocompatible con datos existentes.

## Mantenimiento

### Verificar Integridad
```sql
-- Verificar que todos los registros tienen período asignado
SELECT COUNT(*) as REGISTROS_SIN_PERIODO 
FROM TAB_PRACTICAS_PREPROFESIONALES 
WHERE ID_PERIODO_ACADEMICO IS NULL;
```

### Limpiar Períodos Inactivos
```sql
-- Marcar períodos muy antiguos como inactivos
UPDATE TAB_PERIODOS_ACADEMICOS 
SET ACTIVO = false 
WHERE AÑO_ACADEMICO < YEAR(CURDATE()) - 3;
```

Este sistema de períodos académicos proporciona una base sólida para la gestión temporal de todas las actividades del instituto, facilitando la organización, consulta y análisis de datos por períodos específicos.
