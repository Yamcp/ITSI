-- Datos de prueba para el sistema de prácticas
-- Ejecutar este script después de crear la base de datos

-- Insertar algunas instituciones de convenio
INSERT INTO `TAB_INSTITUCIONES_CONVENIOS` (`ID_INSTITUCION_CONVENIO`, `ID_TIPO_INSTITUCION`, `NOMBRE`, `RUC`, `DIRECCION`, `CIUDAD`, `TELEFONO`, `EMAIL`, `REPRESENTANTE_LEGAL`, `CONTACTO`, `TELEFONO_CONTACTO`, `EMAIL_CONTACTO`) VALUES
(1, 1, 'Hospital San Vicente de Paúl', '1234567890001', 'Av. 17 de Julio, Ibarra', 'Ibarra', '062-123456', 'contacto@hospitalsanvicente.com', 'Dr. Juan Pérez', 'Lic. María González', '0987654321', 'maria.gonzalez@hospitalsanvicente.com'),
(2, 2, 'Banco del Pacífico', '0987654321001', 'Av. Amazonas, Quito', 'Quito', '022-987654', 'info@bancodelpacifico.com', 'Sr. Carlos Mendoza', 'Ing. Ana Ruiz', '0912345678', 'ana.ruiz@bancodelpacifico.com'),
(3, 1, 'Fundación Niños del Ecuador', '1122334455001', 'Calle 10 de Agosto, Guayaquil', 'Guayaquil', '042-555666', 'info@ninosdelecuador.org', 'Dra. Sofía Morales', 'Lic. Pedro Aguirre', '0999888777', 'pedro.aguirre@ninosdelecuador.org');

-- Insertar algunos estados de prácticas
INSERT INTO `TAB_ESTADO_PRACTICAS` (`ID_ESTADO_PRACTICAS`, `ESTADO`) VALUES
(1, 'Pendiente'),
(2, 'En Progreso'),
(3, 'Completada'),
(4, 'Cancelada');

-- Insertar algunas asignaciones de prácticas
INSERT INTO `TAB_ASIGNACIONES_PRACTICAS` (`ID_ASIGNACION_PRACTICA`, `ID_TIPO_PRACTICA`, `ID_USUARIO`, `ID_ESTADO_PRACTICAS`, `ID_INSTITUCION_CONVENIO`, `FECHA_INICIO`, `FECHA_FIN`, `HORA_TOTAL`, `DESCRIPCION`, `CRONOGRAMA`) VALUES
(1, 2, 1, 2, 1, '2025-06-01', '2025-08-30', 240, 'Desarrollo e implementación de sistema de gestión hospitalaria', 'Lunes a Viernes 8:00-17:00'),
(2, 2, 1, 2, 2, '2025-07-01', '2025-09-30', 240, 'Desarrollo de aplicaciones móviles para servicios bancarios', 'Lunes a Viernes 9:00-18:00'),
(3, 1, 1, 2, 3, '2025-08-01', '2025-10-30', 96, 'Desarrollo de plataforma educativa para niños en situación vulnerable', 'Sábados 8:00-16:00');

-- Insertar algunas prácticas preprofesionales
INSERT INTO `TAB_PRACTICAS_PREPROFESIONALES` (`ID_PRACTICA_PREPROFESIONAL`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_INSTRUCTOR`, `ID_INSTITUCION_CONVENIO`, `AREA_ESPECIALIZACION`, `PROYECTO_ESPECIFICO`, `HORAS_PRACTICAS`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_PRACTICA`, `EVALUACION_FINAL`, `OBSERVACIONES`) VALUES
(1, 1, 1, 1, 1, 'Desarrollo de Software', 'Sistema de gestión de pacientes y citas médicas', 240, '2025-06-01', '2025-08-30', 'En Progreso', NULL, 'Estudiante con buen desempeño en desarrollo web'),
(2, 2, 2, 2, 2, 'Desarrollo Móvil', 'Aplicación móvil para consulta de saldos y transferencias', 240, '2025-07-01', '2025-09-30', 'En Progreso', NULL, 'Proyecto en desarrollo con tecnologías React Native');

-- Insertar algunos servicios comunitarios
INSERT INTO `TAB_SERVICIO_COMUNITARIO` (`ID_SERVICIO_COMUNITARIO`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_INSTRUCTOR`, `ID_INSTITUCION_CONVENIO`, `PROYECTO_SOCIAL`, `COMUNIDAD_BENEFICIADA`, `HORAS_SERVICIO`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_SERVICIO`, `IMPACTO_SOCIAL`, `OBSERVACIONES`) VALUES
(1, 3, 3, 3, 3, 'Plataforma Educativa Digital', 'Niños y adolescentes en situación vulnerable de Guayaquil', 96, '2025-08-01', '2025-10-30', 'En Progreso', 'Mejora en el acceso a educación digital para 200+ niños', 'Proyecto con alto impacto social positivo');

-- Insertar algunas asistencias de ejemplo
INSERT INTO `TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES` (`ID_ASISTENCIA_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `FECHA_ASISTENCIA`, `HORA_ENTRADA`, `HORA_SALIDA`, `ACTIVIDADES_DIA`, `COMPETENCIAS_DESARROLLADAS`, `FECHA_REGISTRO`, `OBSERVACIONES`) VALUES
(1, 1, '2025-08-30', '08:00:00', '17:00:00', 'Desarrollo de módulo de gestión de pacientes, implementación de base de datos, pruebas unitarias', 'Programación en PHP, MySQL, JavaScript, Bootstrap', '2025-08-30 17:30:00', 'Excelente trabajo en el desarrollo del módulo'),
(2, 1, '2025-08-29', '08:00:00', '17:00:00', 'Análisis de requerimientos, diseño de interfaz de usuario, configuración del entorno de desarrollo', 'Análisis de sistemas, diseño UX/UI, configuración de entornos', '2025-08-29 17:15:00', 'Buen análisis de requerimientos del sistema'),
(3, 2, '2025-08-30', '09:00:00', '18:00:00', 'Desarrollo de componentes React Native, integración con API bancaria, pruebas de funcionalidad', 'React Native, integración de APIs, testing móvil', '2025-08-30 18:30:00', 'Progreso satisfactorio en la aplicación móvil');

-- Insertar algunas asistencias de servicio comunitario
INSERT INTO `TAB_ASISTENCIAS_SERVICIO_COMUNITARIO` (`ID_ASISTENCIA_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `FECHA_ASISTENCIA`, `HORA_ENTRADA`, `HORA_SALIDA`, `ACTIVIDADES_DIA`, `BENEFICIARIOS_ATENDIDOS`, `FECHA_REGISTRO`, `OBSERVACIONES`) VALUES
(1, 1, '2025-08-31', '08:00:00', '16:00:00', 'Capacitación a niños en uso de computadoras, instalación de software educativo, soporte técnico', '25 niños de 8-12 años', '2025-08-31 16:30:00', 'Los niños mostraron gran interés en aprender'),
(2, 1, '2025-08-24', '08:00:00', '16:00:00', 'Desarrollo de contenido educativo digital, creación de tutoriales interactivos', '30 adolescentes de 13-17 años', '2025-08-24 16:45:00', 'Contenido educativo bien recibido por los adolescentes');

-- Insertar algunos seguimientos de prácticas preprofesionales
INSERT INTO `TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES` (`ID_SEGUIMIENTO_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `HORAS_CUMPLIDAS`, `ACTIVIDADES_REALIZADAS`, `COMPETENCIAS_DESARROLLADAS`, `OBSERVACIONES`, `ARCHIVO_REPORTE`, `FECHA_REPORTE`) VALUES
(1, 1, 80, 'Desarrollo de módulo de gestión de pacientes, implementación de base de datos, diseño de interfaz', 'Programación web, gestión de bases de datos, diseño UX/UI', 'El estudiante muestra excelente progreso en el desarrollo del sistema', 'reporte_semanal_1.pdf', '2025-08-30 17:00:00'),
(2, 2, 60, 'Desarrollo de componentes móviles, integración con servicios bancarios, pruebas de funcionalidad', 'Desarrollo móvil, integración de APIs, testing', 'Buen desempeño en el desarrollo de la aplicación móvil', 'reporte_semanal_2.pdf', '2025-08-30 18:00:00');

-- Insertar algunos seguimientos de servicio comunitario
INSERT INTO `TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO` (`ID_SEGUIMIENTO_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `HORAS_CUMPLIDAS`, `ACTIVIDADES_REALIZADAS`, `BENEFICIARIOS_ATENDIDOS`, `OBSERVACIONES`, `ARCHIVO_REPORTE`, `FECHA_REPORTE`) VALUES
(1, 1, 16, 'Capacitación digital a niños, desarrollo de contenido educativo, soporte técnico', '55 beneficiarios (25 niños + 30 adolescentes)', 'Excelente impacto social, los beneficiarios muestran gran interés en aprender', 'reporte_servicio_1.pdf', '2025-08-31 16:00:00');
