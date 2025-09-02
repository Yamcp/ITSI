# Sistema de Gestión de Documentos de Prácticas

## Descripción
Sistema completo para la gestión y subida de documentos relacionados con las prácticas de servicio comunitario del Instituto Tecnológico Superior Ibarra (ITSI).

## Características Principales

### 1. Tipos de Documentos Soportados
El sistema maneja los 12 tipos de documentos requeridos para las prácticas:

1. **Oficio de Asignación de Tutor** - Emitido por el coordinador de la carrera
2. **Oficio Personal a Entidad** - Realizado a la entidad receptora ITSI
3. **Carta de Aceptación** - De la entidad receptora dirigida al estudiante
4. **Solicitud Institucional** - Dirigida al Sr. Rector Dr. Mario Montenegro
5. **Certificado de Culminación** - Por 60 horas de prácticas de servicio comunitario
6. **Rúbrica de Evaluación Entidad** - Llena, firmada y sellada por entidades receptoras
7. **Hojas de Asistencia** - Llenas, firmadas y selladas por entidades receptoras
8. **Ficha de Registro de Actividades** - Registro de actividades realizadas
9. **Ficha de Control y Seguimiento** - Llena, firmada y sellada por docentes tutores
10. **Rúbrica de Evaluación Docente** - Llena, firmada y sellada por docentes tutores
11. **Rúbrica de Evaluación de Resultados** - Realizada por el Departamento de Vinculación
12. **Respaldo en Fotos** - Fotos, capturas, videos, impresiones del proyecto

### 2. Funcionalidades del Sistema

#### Interfaz de Usuario
- **Diseño Moderno**: UI/UX intuitiva con diseño responsivo
- **Proceso de 3 Pasos**: Selección de tipo → Subida → Confirmación
- **Drag & Drop**: Arrastra y suelta archivos fácilmente
- **Validación en Tiempo Real**: Verificación de tipos y tamaños de archivo
- **Barra de Progreso**: Seguimiento visual del proceso de subida

#### Gestión de Archivos
- **Formatos Soportados**: PDF, DOC, DOCX, JPG, PNG, MP4, AVI
- **Tamaño Máximo**: 10MB por archivo
- **Nombres Únicos**: Generación automática de nombres únicos para evitar conflictos
- **Almacenamiento Seguro**: Archivos guardados en `writable/uploads/documentos-practicas/`

#### Estados de Revisión
- **Pendiente**: Documento recién subido, esperando revisión
- **Aprobado**: Documento revisado y aprobado
- **Rechazado**: Documento rechazado con observaciones

## Instalación y Configuración

### 1. Base de Datos
Ejecutar el script SQL para crear las tablas necesarias:
```sql
-- Ejecutar el archivo: documentos_practicas_data.sql
```

### 2. Estructura de Archivos
Los archivos se organizan de la siguiente manera:
```
app/
├── Controllers/admin/
│   └── DocumentosPracticasController.php
├── Models/
│   ├── DocumentosPracticasModel.php
│   ├── EstadosRevisionesModel.php
│   └── TiposDocumentosPracticasModel.php
└── Views/admin/documentos/
    └── uploadDocumentosPracticas.php
```

### 3. Rutas Configuradas
```php
// Rutas principales
GET  /admin/documentos-practicas              // Página principal
GET  /admin/documentos-practicas/upload       // Formulario de subida
POST /admin/documentos-practicas/store        // Procesar subida
GET  /admin/documentos-practicas/download/{id} // Descargar documento

// APIs
GET  /admin/documentos-practicas/api/estudiantes     // Lista de estudiantes
GET  /admin/documentos-practicas/api/recientes       // Documentos recientes
```

## Uso del Sistema

### 1. Acceso
- Navegar a `/admin/documentos-practicas` desde el dashboard administrativo
- O hacer clic en "Documentos" en las acciones rápidas del dashboard

### 2. Proceso de Subida
1. **Seleccionar Tipo**: Elegir el tipo de documento de las 12 opciones disponibles
2. **Seleccionar Estudiante**: Elegir el estudiante para quien se sube el documento
3. **Subir Archivo**: Arrastrar y soltar o seleccionar el archivo
4. **Agregar Observaciones**: Comentarios adicionales (opcional)
5. **Confirmar**: El sistema procesa y confirma la subida

### 3. Seguimiento
- Ver documentos recientes en la sección inferior
- Estados de revisión visibles con códigos de color
- Fechas de subida y nombres de archivos

## API Endpoints

### Obtener Estudiantes
```javascript
GET /admin/documentos-practicas/api/estudiantes
Response: {
    "success": true,
    "data": [
        {
            "ID_USUARIO": 1,
            "NOMBRE": "Ana",
            "APELLIDO": "Yandun",
            "CEDULA": "1724143290"
        }
    ]
}
```

### Obtener Documentos Recientes
```javascript
GET /admin/documentos-practicas/api/recientes
Response: {
    "success": true,
    "data": [
        {
            "ID_DOCUMENTO_PRACTICA": 1,
            "TIPO": "oficio-asignacion-tutor",
            "NOMBRE": "Ana",
            "APELLIDO": "Yandun",
            "FECHA_SUBIDA": "2025-01-15 10:30:00",
            "ESTADO_REVISION": "Pendiente"
        }
    ]
}
```

### Subir Documento
```javascript
POST /admin/documentos-practicas/store
FormData: {
    document_type: "oficio-asignacion-tutor",
    id_usuario: "1",
    archivo: [File],
    observaciones: "Documento oficial"
}
Response: {
    "success": true,
    "message": "Documento subido exitosamente",
    "data": {
        "id": 1,
        "nombre": "documento.pdf",
        "fecha": "15/01/2025 10:30"
    }
}
```

## Seguridad

### Validaciones Implementadas
- **Tipos de Archivo**: Solo formatos permitidos
- **Tamaño**: Máximo 10MB por archivo
- **Usuarios**: Solo estudiantes activos pueden tener documentos
- **Estados**: Validación de estados de revisión

### Permisos
- Solo administradores pueden acceder al sistema
- Los archivos se almacenan fuera del directorio web público
- Nombres de archivo únicos para evitar conflictos

## Mantenimiento

### Limpieza de Archivos
Para eliminar documentos y sus archivos asociados:
```php
$documentosModel = new DocumentosPracticasModel();
$documentosModel->eliminarDocumento($id); // Elimina registro y archivo
```

### Backup de Documentos
Los archivos se encuentran en:
```
writable/uploads/documentos-practicas/
```

### Logs
El sistema registra todas las operaciones en los logs de CodeIgniter 4.

## Personalización

### Agregar Nuevos Tipos de Documentos
1. Insertar en `TAB_TIPOS_DOCUMENTOS_PRACTICAS`
2. Actualizar el mapeo en `DocumentosPracticasController::getTipoDocumentoId()`
3. Agregar la opción en la vista

### Modificar Validaciones
Editar las reglas en `DocumentosPracticasController::store()`:
```php
$rules = [
    'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf,doc,docx,jpg,jpeg,png,mp4,avi]'
];
```

## Soporte
Para soporte técnico o reportar problemas, contactar al equipo de desarrollo del ITSI.

---
**Versión**: 1.0  
**Fecha**: Enero 2025  
**Desarrollado para**: Instituto Tecnológico Superior Ibarra
