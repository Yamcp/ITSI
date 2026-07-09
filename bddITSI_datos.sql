/*==============================================================*/
/* ITSI — Datos iniciales (INSERT)                              */
/* Requisito: haber importado antes bddITSI.sql (esquema)       */
/* Motor: MySQL 5.7+ / MariaDB 10.2+ · utf8mb4_unicode_ci      */
/*                                                              */
/* En phpMyAdmin: selecciona primero tu base de datos y luego   */
/* importa este archivo.                                        */
/* No importes bddITSI_vistas_local.sql en InfinityFree.        */
/*==============================================================*/

-- Solo en local (descomentar si hace falta):
-- USE `itsi`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

/*==============================================================*/
/* Insertar datos iniciales                                     */
/*==============================================================*/
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-03-03-000001', 'App\\Database\\Migrations\\CreateTabRecuperacionContrasena', 'default', 'App', 1772554056, 1),
(2, '2026-03-10-000001', 'App\\Database\\Migrations\\CreateTabInscripcionesActividades', 'default', 'App', 1773177893, 2),
(3, '2026-03-15-000001', 'App\\Database\\Migrations\\AddLogoTabInstitucionesConvenios', 'default', 'App', 1773629842, 3);

INSERT INTO `TAB_DATOS_PERSONAS` (`ID_DATO_PERSONA`, `NOMBRE`, `APELLIDO`, `CEDULA`, `CELULAR`, `DIRECCION`, `EMAIL`, `GENERO`, `ESTADO_CIVIL`, `NACIONALIDAD`, `FECHA_INGRESO`, `ACTIVO`, `FOTO_URL`) VALUES
(1, 'Yamilex Marisol', 'Campues Angamarca', '1004191845', '0992432078', 'Ibarra', 'yamilex.campues2023@itsi.edu.ec', 'Femenino', 'Soltero/a', 'Ecuatoriana', '2025-06-05', 1, 'perfil_1_1773953875.jpg'),
(2, 'Ana ', 'Yandun', '1724143290', '0981377492', 'Ibarra', 'ana.yandun2023@itsi.edu.ec', 'Femenino', 'Casada', 'Ecuatoriana', '2025-06-10', 1, ''),
(3, 'Pedro', 'Aguirre', '0123456789', '0990000001', 'Ibarra', 'pedro.aguirre2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-03-01', 1, ''),
(4, 'Carlos', 'Mendoza', '1234567890', '0987654321', 'Ibarra, Ecuador', 'carlos.mendoza@itsi.edu.ec', 'Masculino', 'Casado', 'Ecuatoriana', '2025-01-15', 1, ''),
(5, 'Ana', 'Ruiz', '0987654321', '0912345678', 'Quito, Ecuador', 'ana.ruiz@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-01-20', 1, ''),
(6, 'María', 'González', '1122334455', '0999888777', 'Guayaquil, Ecuador', 'maria.gonzalez@itsi.edu.ec', 'Femenino', 'Casada', 'Ecuatoriana', '2025-01-25', 1, ''),
(7, 'Juan Carlos', 'Pérez López', '1001234567', '0987654321', 'Ibarra, Ecuador', 'juan.perez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-01-15', 1, ''),
(8, 'María Elena', 'García Torres', '1002345678', '0976543210', 'Quito, Ecuador', 'maria.garcia2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-01-20', 1, ''),
(9, 'Carlos Alberto', 'Rodríguez Silva', '1003456789', '0965432109', 'Guayaquil, Ecuador', 'carlos.rodriguez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-01-25', 1, ''),
(10, 'Ana Lucía', 'Martínez Vega', '1004567890', '0954321098', 'Cuenca, Ecuador', 'ana.martinez2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-01', 1, ''),
(11, 'Luis Fernando', 'Torres Vaca', '1005678901', '0991112233', 'Ibarra, Ecuador', 'luis.torres@itsi.edu.ec', 'Masculino', 'Casado', 'Ecuatoriana', '2025-02-05', 1, ''),
(12, 'Sandra Patricia', 'Chávez Ruiz', '1006789012', '0992223344', 'Otavalo, Ecuador', 'sandra.chavez@itsi.edu.ec', 'Femenino', 'Casada', 'Ecuatoriana', '2025-02-05', 1, ''),
(13, 'Jorge Andrés', 'Flores Benítez', '1007890123', '0993334455', 'Ibarra, Ecuador', 'jorge.flores@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-05', 1, ''),
(14, 'Diana Carolina', 'Vallejo Andrade', '1008901234', '0994445566', 'Cotacachi, Ecuador', 'diana.vallejo@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-05', 1, ''),
(15, 'Kevin Andrés', 'Suárez Morales', '1009012345', '0995556677', 'Ibarra, Ecuador', 'kevin.suarez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-10', 1, ''),
(16, 'Paola Nicole', 'Chamorro Reina', '1000123456', '0996667788', 'Ibarra, Ecuador', 'paola.chamorro2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-10', 1, ''),
(17, 'Mateo Sebastián', 'Villalba Cárdenas', '1001234568', '0997778899', 'Atuntaqui, Ecuador', 'mateo.villalba2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-10', 1, '');

INSERT INTO `TAB_USUARIOS` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(1, 1, 'admin', '$2y$10$TIMV8h.jkhNV8CLSitL6gOq7fzNIRKyjrJXejA9E49Zf8.LjdZNdC', '1'),
(2, 2, 'coord', '$2y$10$i2tATNDRscPHKmaElhHJfejq2SrmjJ1guv2jCOHKoUlm7NT6YvN0a', '1'),
(3, 3, 'docente', '$2y$10$nLujePj1IK/t0Te3WEPNq.iGdBBAty3Oo5xJeSIUrV/s6mmVrExsK', '1'),
(4, 4, 'estud1', '123', '1'),
(5, 5, 'estud2', '123', '1'),
(6, 6, 'estud3', '123', '1'),
(7, 7, 'estud4', '123', '1'),
(8, 8, 'estud5', '123', '1'),
(9, 9, 'estud6', '123', '1'),
(10, 10, 'estud7', '123', '1'),
(11, 11, 'docente2', '123', '1'),
(12, 12, 'docente3', '123', '1'),
(13, 13, 'docente4', '123', '1'),
(14, 14, 'docente5', '123', '1'),
(15, 15, 'estud8', '123', '1'),
(16, 16, 'estud9', '123', '1'),
(17, 17, 'estud10', '123', '1');

INSERT INTO `TAB_RECUPERACION_CONTRASENA` (`ID_RECUPERACION`, `ID_USUARIO`, `TOKEN`, `EXPIRA_EN`, `USADO`, `CREADO_EN`) VALUES
(1, 1, '6a585b02f0e38f1d9bbdd8bddbba2fa4fb47154726356a90d0a54ce64a3014c2', '2026-03-03 12:08:09', 1, '2026-03-03 11:08:09'),
(2, 1, 'b1bfa1e5fc126451b02f8678b2625ebd7431c2dc397628b1b3c5ebbe3b146e14', '2026-03-03 12:19:17', 1, '2026-03-03 11:19:17'),
(3, 1, '4a5e0ed0ac1de40f16a6888863040cf73eb13cce4f2fd1f3daaf4dc928d5b366', '2026-03-03 12:19:27', 1, '2026-03-03 11:19:27'),
(4, 1, 'df3d68e15aac480c0472d4d5be7c3a25ff4a9629ce7fe8bbe31ecb30d5b7d8b7', '2026-03-03 12:35:05', 1, '2026-03-03 11:35:05'),
(5, 1, '80b7dca7638f9d23969c1aa4c7c68b12a11fb2cdda5c2edfaf2c4866b94928ad', '2026-03-03 22:52:43', 1, '2026-03-03 21:52:43'),
(6, 1, 'ec5939eb8bd19ad51dfe368c2b20a62d5bbd54b198c6ecfb97f29006ddef941b', '2026-03-10 16:59:33', 1, '2026-03-10 15:59:33'),
(7, 1, 'a04ff8df48b57151ba8ee52eb286e093eb6db6b54d3c3c82c13b78ee6c3e10b1', '2026-03-12 17:05:59', 1, '2026-03-12 16:05:59'),
(8, 1, '850e9eeef43cbbee356ba3f97f9a4ee2e77e5e48a73dd18259f8990e76068080', '2026-03-12 17:08:00', 1, '2026-03-12 16:08:00'),
(9, 1, '4237b08baaa13d0899e28310dd4b8d89f0dc8d2437f11c1719ee4c93057863db', '2026-03-12 22:56:35', 1, '2026-03-12 21:56:35'),
(10, 1, 'f6b836a0249609d243460c4b5ff6226b722a302fb5a424b90435eb362022f902', '2026-03-12 22:57:19', 1, '2026-03-12 21:57:19'),
(11, 1, '2c87baa47055dac4daf8473c1ed3c2b6fa90ce10a4656a59fb608a7a47f63543', '2026-03-12 22:57:34', 1, '2026-03-12 21:57:34');

INSERT INTO `TAB_TIPOS_ROLES` (`ID_TIPOS_ROLES`, `ROL`) VALUES
(1, 'Administrador'),
(2, 'Coordinador'),
(3, 'Docente'),
(4, 'Estudiante');

INSERT INTO `TAB_ROLES` (`ID_ROL`, `ID_USUARIO`, `ID_TIPOS_ROLES`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 4),
(6, 6, 4),
(7, 7, 4),
(8, 8, 4),
(9, 9, 4),
(10, 10, 4),
(11, 11, 3),
(12, 12, 3),
(13, 13, 3),
(14, 14, 3),
(15, 15, 4),
(16, 16, 4),
(17, 17, 4);

INSERT INTO `TAB_TIPOS_CONVENIOS` (`ID_TIPO_CONVENIO`, `CONVENIO`) VALUES
(1, 'Preprofesional'),
(2, 'Servicio Comunitario'),
(3, 'Mixta');

INSERT INTO `TAB_TIPOS_ESTADOS` (`ID_TIPO_ESTADO`, `ESTADO`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

INSERT INTO `TAB_TIPOS_INSTITUCION` (`ID_TIPO_INSTITUCION`, `INSTITUCION`) VALUES
(1, 'Pública'),
(2, 'Privada');

INSERT INTO `TAB_TIPOS_PRACTICAS` (`ID_TIPO_PRACTICA`, `PRACTICA`) VALUES
(1, 'Prácticas de Servicio Comunitario'),
(2, 'Prácticas Preprofesionales');

INSERT INTO `TAB_CARRERAS` (`ID_CARRERA`, `NOMBRE`) VALUES
(1, 'Desarrollo de Software'),
(2, 'Diseño Gráfico'),
(3, 'Redes y Telecomunicaciones'),
(4, 'Administración'),
(5, 'Atención Integral a Adultos Mayores'),
(6, 'Marketing Digital y Comercio Electronico');

INSERT INTO `TAB_DEPARTAMENTOS` (`ID_DEPARTAMENTO`, `NOMBRE`, `RESPONSABLE`) VALUES
(1, 'Departamento de Vinculación con la Sociedad', 'Coordinador de Vinculación'),
(2, 'Departamento Académico', 'Director Académico'),
(3, 'Departamento de Investigación', 'Director de Investigación');

INSERT INTO `TAB_TIPOS_ACTIVIDADES` (`ID_TIPO_ACTIVIDAD`, `ACTIVIDAD`) VALUES
(1, 'Curso'),
(2, 'Taller'),
(3, 'Conferencia'),
(4, 'Capacitación');

INSERT INTO `TAB_TIPOS_CONTRATO` (`ID_TIPO_CONTRATO`, `TIPO_CONTRATO`) VALUES
(1, 'Tiempo Completo'),
(2, 'Medio Tiempo'),
(3, 'Por Horas');

INSERT INTO `TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES` (`CODIGO`, `NOMBRE`, `DESCRIPCION`, `ORDEN`, `OBLIGATORIO`) VALUES
('PPR-001', 'Oficio de Asignación de Tutor', 'Documento oficial emitido por la coordinación de la carrera que designa al docente responsable de la tutoría y seguimiento de las prácticas de servicio comunitario del estudiante.', 1, true),
('PPR-002', 'Oficio a Entidad Receptora', 'Carta formal enviada por el estudiante a la institución "Instituto Tecnológico Superior Ibarra", con el propósito de solicitar la oportunidad de realizar sus prácticas de servicio comunitario.', 2, true),
('PPR-003', 'Carta de Aceptación', 'Carta oficial de la entidad receptora "Instituto Tecnológico Superior Ibarra" que confirma la aceptación del o los estudiantes para realizar las prácticas de servicio comunitario en sus instalaciones.', 3, true),
('PPR-004', 'Planificación de Actividades', 'Documento que detalla las actividades específicas que el estudiante realizará durante sus prácticas, incluyendo objetivos, cronograma y recursos necesarios.', 4, true),
('PPR-005', 'Solicitud Institucional Valorada', 'Documento de solicitud formal dirigido al Sr. Rector, Dr. Mario Montenegro, pidiendo la aprobación institucional para la realización de las prácticas de servicio comunitario.', 4, true),
('PPR-006', 'Certificado de Culminación de Horas', 'Certificado emitido por la entidad receptora "Instituto Tecnológico Superior Ibarra" que acredita que el estudiante ha completado las 60 horas requeridas de prácticas de servicio comunitario.', 5, true),
('PPR-007', 'Rúbrica de Evaluación de Entidad', 'Formulario de evaluación del desempeño del estudiante, llenado y sellado por la entidad receptora. Valora aspectos como la responsabilidad, la proactividad y la calidad del trabajo.', 8, true),
('PPR-008', 'Hojas de Asistencia', 'Registro físico de la asistencia del estudiante en la entidad receptora. Incluye las firmas y sellos para validar las horas de trabajo.', 6, true),
('PPR-009', 'Ficha de Registro de Actividades', 'Documento detallado en el que el estudiante registra las actividades específicas realizadas durante sus prácticas, incluyendo fechas y descripciones.', 7, true),
('PPR-010', 'Ficha de Control y Seguimiento Docente', 'Documento utilizado por el tutor docente para registrar el seguimiento académico del estudiante durante las prácticas. Incluye visitas o revisiones periódicas.', 9, true),
('PPR-011', 'Rúbrica de Evaluación Docente', 'Rúbrica de evaluación llenada y firmada por el tutor docente. Califica el desempeño del estudiante en base a los criterios académicos del programa.', 10, true),
('PPR-012', 'Rúbrica de Evaluación de Resultados', 'Evaluación final realizada por el Departamento de Vinculación con la Sociedad, que valora los resultados y el impacto del proyecto de servicio comunitario en su conjunto.', 11, true),
('PPR-013', 'Evidencia Fotográfica y Digital', 'Material de apoyo visual y digital, como fotos, capturas, videos o impresiones, que documenta y comprueba la realización de las actividades y trabajos del proyecto.', 12, true),
('PPR-014', 'Informe Final de Prácticas Preprofesionales', 'Documento que resume todas las actividades realizadas durante las prácticas preprofesionales, incluyendo resultados, aprendizajes y recomendaciones.', 13, true);

INSERT INTO `TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO` (`CODIGO`, `NOMBRE`, `DESCRIPCION`, `ORDEN`, `OBLIGATORIO`, `ACTIVO`) VALUES
('PSC-001', 'Oficio de Asignación de Tutor', 'Documento oficial emitido por la coordinación de la carrera que designa al docente responsable de la tutoría y seguimiento de las prácticas de servicio comunitario del estudiante.', 1, 1, 1),
('PSC-002', 'Oficio a Entidad Receptora', 'Carta formal enviada por el estudiante a la institución "Instituto Tecnológico Superior Ibarra", con el propósito de solicitar la oportunidad de realizar sus prácticas de servicio comunitario.', 2, 1, 1),
('PSC-003', 'Carta de Aceptación', 'Carta oficial de la entidad receptora "Instituto Tecnológico Superior Ibarra" que confirma la aceptación del o los estudiantes para realizar las prácticas de servicio comunitario en sus instalaciones.', 3, 1, 1),
('PSC-004', 'Solicitud Institucional Valorada', 'Documento de solicitud formal dirigido al Sr. Rector, Dr. Mario Montenegro, pidiendo la aprobación institucional para la realización de las prácticas de servicio comunitario.', 4, 1, 1),
('PSC-005', 'Certificado de Culminación de Horas', 'Certificado emitido por la entidad receptora "Instituto Tecnológico Superior Ibarra" que acredita que el estudiante ha completado las 60 horas requeridas de prácticas de servicio comunitario.', 5, 1, 1),
('PSC-006', 'Hojas de Asistencia', 'Registro físico de la asistencia del estudiante en la entidad receptora. Incluye las firmas y sellos para validar las horas de trabajo.', 6, 1, 1),
('PSC-007', 'Ficha de Registro de Actividades', 'Documento detallado en el que el estudiante registra las actividades específicas realizadas durante sus prácticas, incluyendo fechas y descripciones.', 7, 1, 1),
('PSC-008', 'Rúbrica de Evaluación de Entidad', 'Formulario de evaluación del desempeño del estudiante, llenado y sellado por la entidad receptora. Valora aspectos como la responsabilidad, la proactividad y la calidad del trabajo.', 8, 1, 1),
('PSC-009', 'Ficha de Control y Seguimiento Docente', 'Documento utilizado por el tutor docente para registrar el seguimiento académico del estudiante durante las prácticas. Incluye visitas o revisiones periódicas.', 9, 1, 1),
('PSC-010', 'Rúbrica de Evaluación Docente', 'Rúbrica de evaluación llenada y firmada por el tutor docente. Califica el desempeño del estudiante en base a los criterios académicos del programa.', 10, 1, 1),
('PSC-011', 'Rúbrica de Evaluación de Resultados', 'Evaluación final realizada por el Departamento de Vinculación con la Sociedad, que valora los resultados y el impacto del proyecto de servicio comunitario en su conjunto.', 11, 1, 1),
('PSC-012', 'Evidencia Fotográfica y Digital', 'Material de apoyo visual y digital, como fotos, capturas, videos o impresiones, que documenta y comprueba la realización de las actividades y trabajos del proyecto.', 12, 1, 1),
('PSC-013', 'Informe Final de Prácticas de Servicio Comunitario', 'Documento que resume todas las actividades realizadas durante las prácticas de servicio comunitario, incluyendo resultados, aprendizajes y recomendaciones.', 13, 1, 1);

INSERT INTO TAB_ESTADOS_REVISIONES (ID_ESTADO_REVISION, ESTADO, DESCRIPCION, COLOR, ORDEN) VALUES
(1, 'Pendiente', 'Documento pendiente de revisión', '#ffc107', 1),
(2, 'En Revisión', 'Documento siendo revisado por el docente', '#17a2b8', 2),
(3, 'Aprobado', 'Documento aprobado por el revisor', '#28a745', 3),
(4, 'Rechazado', 'Documento rechazado por el revisor', '#dc3545', 4),
(5, 'Requiere Corrección', 'Documento que requiere correcciones', '#fd7e14', 5);

INSERT INTO `TAB_ESTADOS_PRACTICAS_PREPROFESIONALES` (`ID_ESTADO_PREPROFESIONAL`, `ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
(1, 'Pendiente', 'Práctica pendiente de inicio', '#ffc107'),
(2, 'En Progreso', 'Práctica en desarrollo', '#17a2b8'),
(3, 'Pausada', 'Práctica temporalmente pausada', '#6c757d'),
(4, 'Completada', 'Práctica finalizada exitosamente', '#28a745'),
(5, 'Cancelada', 'Práctica cancelada', '#dc3545'),
(6, 'Evaluada', 'Práctica evaluada y aprobada', '#20c997');

INSERT INTO `TAB_ESTADOS_SERVICIO_COMUNITARIO` (`ID_ESTADO_SERVICIO`, `ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
(1, 'Pendiente', 'Servicio pendiente de inicio', '#ffc107'),
(2, 'En Progreso', 'Servicio en desarrollo', '#17a2b8'),
(3, 'Pausado', 'Servicio temporalmente pausado', '#6c757d'),
(4, 'Completado', 'Servicio finalizado exitosamente', '#28a745'),
(5, 'Cancelado', 'Servicio cancelado', '#dc3545'),
(6, 'Evaluada', 'Servicio evaluado y aprobado', '#20c997');

INSERT INTO `TAB_TIPOS_MODALIDADES` (`ID_TIPO_MODALIDAD`, `MODALIDAD`) VALUES
(1, 'Presencial'),
(2, 'Virtual'),
(3, 'Híbrida');

INSERT INTO `TAB_TIPOS_INSTRUCTORES` (`ID_TIPO_INSTRUCTOR`, `TIPO`) VALUES
(1, 'Interno'),
(2, 'Externo');

INSERT INTO `TAB_INSTRUCTORES` (`ID_INSTRUCTOR`, `ID_TIPO_INSTRUCTOR`, `ID_DATO_PERSONA`, `ESPECIALIDAD`, `TITULO_PROFESIONAL`) VALUES
(1, 1, 4, 'Desarrollo de Software', 'Ingeniero en Sistemas'),
(2, 1, 5, 'Hardware y Redes', 'Técnico en Electrónica'),
(3, 2, 6, 'Inteligencia Artificial', 'Doctora en Ciencias de la Computación');

INSERT INTO `TAB_PERIODOS_ACADEMICOS` (`ID_PERIODO_ACADEMICO`, `MES_INICIO`, `AÑO_INICIO`, `MES_FIN`, `AÑO_FIN`) VALUES
(1, 4, 2024, 9, 2024),
(2, 10, 2024, 3, 2025),
(3, 4, 2025, 9, 2025),
(4, 10, 2025, 3, 2026),
(5, 4, 2026, 9, 2026);

INSERT INTO `TAB_ACTIVIDADES_EDUCACION` (`ID_ACTIVIDAD_EDUCACION`, `ID_INSTRUCTOR`, `ID_TIPO_MODALIDAD`, `ID_TIPO_ACTIVIDAD`, `ID_USUARIO`, `ID_PERIODO_ACADEMICO`, `NOMBRE_ACTIVIDAD`, `DESCRIPCION`, `OBJETIVOS`, `DURACION_HORAS`, `FECHA_INICIO`, `FECHA_FIN`, `LUGAR`, `HORARIO`, `INCLUYE_CERTIFICADO`, `PROGRAMA_DETALLADO`) VALUES
(1, 1, 1, 1, 1, 4, 'Desarrollo Web Full Stack', 'Curso completo de desarrollo web con tecnologías modernas como React, Node.js, MongoDB y más.', 'Formar desarrolladores full stack competentes en tecnologías web modernas', 4, '2025-08-18', '2025-08-19', 'Laboratorio de Programación', 'Lunes a Martess 16:00-18:00', 1, 'Módulo 1: HTML/CSS/JavaScript\r\nMódulo 2: React.js\r\nMódulo 3: Node.js\r\nMódulo 4: Base de datos\r\nMódulo 5: Proyecto final'),
(2, 2, 2, 2, 1, 4, 'Reparación de Equipos de Cómputo', 'Taller práctico de mantenimiento y reparación de hardware de computadoras.', 'Capacitar en técnicas de diagnóstico y reparación de equipos', 40, '2025-10-01', '2025-10-31', 'Plataforma Virtual', 'Sábados 9:00-13:00', 1, 'Diagnóstico de problemas\nReparación de hardware\nMantenimiento preventivo\nInstalación de software'),
(3, 3, 1, 3, 1, 4, 'Inteligencia Artificial y Machine Learning', 'Seminario sobre tendencias actuales en IA y aplicaciones prácticas.', 'Actualizar conocimientos en inteligencia artificial y sus aplicaciones', 16, '2025-12-15', '2025-12-16', 'Auditorio Principal', '8:00-17:00', 1, 'Introducción a la IA\nMachine Learning básico\nDeep Learning\nAplicaciones prácticas\nCasos de estudio'),
(4, 1, 1, 1, 1, 4, 'Programación en Python', 'Curso introductorio de programación usando Python como lenguaje principal.', 'Enseñar los fundamentos de programación usando Python', 80, '2025-08-01', '2025-09-30', 'Laboratorio de Programación', 'Martes y Jueves 18:00-20:00', 1, 'Variables y tipos de datos\nEstructuras de control\nFunciones\nPOO\nLibrerías básicas'),
(5, 2, 2, 2, 1, 4, 'Configuración de Redes', 'Taller de configuración y administración de redes de computadoras.', 'Capacitar en configuración y administración de redes', 32, '2025-11-01', '2025-11-30', 'Laboratorio de Redes', 'Sábados 8:00-12:00', 1, 'Protocolos de red\nConfiguración de routers\nSwitches y VLANs\nSeguridad en redes'),
(6, 1, 2, 1, 1, 5, 'React para Producción', 'Curso enfocado en buenas prácticas para aplicaciones React listas para producción.', 'Desarrollar habilidades para crear aplicaciones React mantenibles y eficientes', 24, '2026-04-15', '2026-05-15', 'Plataforma Virtual', 'Lunes a Viernes 18:00-20:00', 1, 'Módulo 1: Estructura de proyecto\r\nMódulo 2: Manejo de estado\r\nMódulo 3: Optimización y performance\r\nMódulo 4: Testing y buenas prácticas\r\nMódulo 5: Proyecto final'),
(7, 2, 1, 2, 1, 5, 'Taller de Ciberseguridad Básica', 'Taller práctico con fundamentos de seguridad y buenas prácticas para usuarios y equipos.', 'Comprender riesgos y aplicar medidas básicas de ciberseguridad', 12, '2026-04-20', '2026-05-05', 'Laboratorio de Redes', 'Martes y Jueves 15:00-17:00', 1, 'Módulo 1: Amenazas comunes\r\nMódulo 2: OWASP básico\r\nMódulo 3: Configuración segura\r\nMódulo 4: Higiene digital'),
(8, 3, 2, 3, 1, 5, 'Conferencia: Tendencias en IA', 'Conferencia de actualización sobre tendencias y casos reales de uso de IA.', 'Conocer tendencias y oportunidades de IA en distintos sectores', 4, '2026-05-10', '2026-05-10', 'Plataforma Virtual', 'Domingo 09:00-13:00', 1, 'Agenda: Tendencias\r\nCasos de uso\r\nSesión Q&A'),
(9, 1, 1, 4, 1, 5, 'Capacitación en Gestión de Proyectos', 'Capacitación para organización de proyectos con metodologías ágiles y planificación.', 'Mejorar habilidades de gestión y planificación de proyectos', 16, '2026-04-22', '2026-05-08', 'Aula Taller', 'Miércoles 18:00-20:00', 1, 'Módulo 1: Fundamentos\r\nMódulo 2: Scrum/Kanban\r\nMódulo 3: Planificación\r\nMódulo 4: Métricas y retrospectivas'),
(10, 1, 1, 1, 1, 4, 'Curso de Bases de Datos', 'Curso de fundamentos y modelado de datos con enfoque práctico.', 'Comprender diseño de bases de datos y consultas', 20, '2026-01-10', '2026-02-10', 'Laboratorio de Programación', 'Lunes a Jueves 16:00-18:00', 1, 'Módulo 1: Modelado\r\nMódulo 2: SQL\r\nMódulo 3: Normalización\r\nMódulo 4: Casos prácticos'),
(11, 2, 2, 2, 1, 4, 'Taller de Soporte Técnico', 'Taller práctico sobre atención de tickets y resolución básica de problemas.', 'Resolver incidencias comunes con enfoque en soporte técnico', 10, '2026-01-20', '2026-02-20', 'Plataforma Virtual', 'Viernes 16:00-18:00', 1, 'Módulo 1: Flujo de soporte\r\nMódulo 2: Diagnóstico\r\nMódulo 3: Herramientas\r\nMódulo 4: Casos'),
(12, 3, 1, 3, 1, 4, 'Conferencia: IA en la práctica', 'Conferencia aplicada sobre cómo implementar soluciones de IA.', 'Entender cómo llevar IA a casos reales', 3, '2026-01-25', '2026-02-05', 'Auditorio Principal', 'Sábado 09:00-12:00', 1, 'Introducción\r\nArquitecturas\r\nImplementación y retos'),
(13, 1, 2, 4, 1, 4, 'Capacitación: Ética y Datos', 'Capacitación para comprender ética, privacidad y manejo de datos.', 'Aplicar principios de ética y privacidad en proyectos de datos', 14, '2025-12-15', '2026-01-25', 'Plataforma Virtual', 'Lunes y Miércoles 19:00-20:30', 1, 'Módulo 1: Marco ético\r\nMódulo 2: Privacidad\r\nMódulo 3: Buenas prácticas\r\nMódulo 4: Casos');

INSERT INTO `TAB_ESTUDIANTES` (`ID_ESTUDIANTE`, `ID_TIPO_ESTADO`, `ID_DATO_PERSONA`, `ID_CARRERA`, `SEMESTRE_ACTUAL`) VALUES
(1, 1, 4, 1, 3),  -- estud1 (Carlos Mendoza) - Desarrollo de Software
(2, 1, 5, 2, 2),  -- estud2 (Ana Ruiz) - Diseño Gráfico
(3, 1, 6, 3, 4),  -- estud3 (María González) - Redes y Telecomunicaciones
(4, 1, 7, 4, 3),  -- estud4 (Juan Carlos Pérez) - Administración
(5, 1, 8, 5, 2),  -- estud5 (María Elena García) - Atención Integral a Adultos Mayores
(6, 1, 9, 6, 4),  -- estud6 (Carlos Alberto Rodríguez) - Marketing Digital
(7, 1, 10, 1, 1), -- estud7 (Ana Lucía Martínez) - Desarrollo de Software
(8, 1, 15, 2, 3),  -- estud8 (Kevin Andrés Suárez) - Diseño Gráfico
(9, 1, 16, 4, 2),  -- estud9 (Paola Nicole Chamorro) - Administración
(10, 1, 17, 6, 1); -- estud10 (Mateo Sebastián Villalba) - Marketing Digital y Comercio Electronico

INSERT INTO `TAB_DOCENTES_TUTORES` (`ID_USUARIO`, `ID_DATO_PERSONA`, `ESPECIALIDAD`, `TITULO_PROFESIONAL`, `AREA_ESPECIALIZACION`, `AÑOS_EXPERIENCIA`) VALUES
(3, 3, 'Desarrollo de Software y Redes', 'Ingeniero en Sistemas', 'Tecnologías de la Información', 10),
(11, 11, 'Diseño Gráfico y Multimedia', 'Diseñador Gráfico', 'Comunicación Visual', 6),
(12, 12, 'Redes y Telecomunicaciones', 'Ingeniera en Telecomunicaciones', 'Infraestructura de Redes', 8),
(13, 13, 'Administración de Empresas', 'Ingeniero Comercial', 'Gestión Empresarial', 7),
(14, 14, 'Marketing Digital y Comercio Electrónico', 'Máster en Marketing Digital', 'Comercio Electrónico', 5);

INSERT INTO `TAB_ENTIDADES_RECEPTORAS` (`ID_ENTIDAD_RECEPTORA`, `NOMBRE`, `RUC`, `DIRECCION`, `CIUDAD`, `TELEFONO`, `EMAIL`, `REPRESENTANTE_LEGAL`, `CONTACTO_DIRECTO`, `TELEFONO_CONTACTO`, `EMAIL_CONTACTO`, `TIPO_ENTIDAD`, `ACTIVO`, `FECHA_CREACION`, `FECHA_ACTUALIZACION`) VALUES
(1, 'Hospital San Vicente de Paúl', '1234567890001', 'Av. 17 de Julio, Ibarra', 'Ibarra', '062-123456', 'contacto@hospitalsanvicente.com', 'Dr. Juan Pérez', 'Lic. María González', '0987654321', 'maria.gonzalez@hospitalsanvicente.com', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(2, 'Banco del Pacífico', '0987654321001', 'Av. Amazonas, Quito', 'Quito', '022-987654', 'info@bancodelpacifico.com', 'Sr. Carlos Mendoza', 'Ing. Ana Ruiz', '0912345678', 'ana.ruiz@bancodelpacifico.com', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(3, 'Fundación Niños del Ecuador', '1122334455001', 'Calle 10 de Agosto, Guayaquil', 'Guayaquil', '042-555666', 'info@ninosdelecuador.org', 'Dra. Sofía Morales', 'Lic. Pedro Aguirre', '0999888777', 'pedro.aguirre@ninosdelecuador.org', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(4, 'Municipio de Ibarra', '1760001230001', 'Plaza de la Independencia, Ibarra', 'Ibarra', '062-123456', 'info@municipioibarra.gob.ec', 'Alcalde Juan Carlos', 'Secretaria General', '0987654321', 'secretaria@municipioibarra.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(5, 'Empresa Tecnológica XYZ', '1234567890002', 'Zona Industrial, Ibarra', 'Ibarra', '062-987654', 'info@tecnologiaxyz.com', 'Ing. Director', 'RRHH', '0912345678', 'rrhh@tecnologiaxyz.com', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(6, 'Casa de la Cultura', '1234567890003', 'Calle Bolívar, Ibarra', 'Ibarra', '062-555777', 'info@casaculturaibarra.gob.ec', 'Lic. Director Cultural', 'Coordinador de Proyectos', '0987654321', 'proyectos@casaculturaibarra.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(7, 'Fundación Telefónica', '1234567890004', 'Av. 6 de Diciembre, Quito', 'Quito', '022-333444', 'info@fundaciontelefonica.org', 'Director Ejecutivo', 'Coordinador Social', '0912345678', 'social@fundaciontelefonica.org', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(8, 'GAD Provincial de Imbabura', '1060000580001', 'Av. Mariano Acosta y Circunvalación, Ibarra', 'Ibarra', '062-955118', 'info@imbabura.gob.ec', 'Ing. Prefecto Provincial', 'Lic. Coordinador de Proyectos', '0998123456', 'proyectos@imbabura.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(9, 'CNT EP Ibarra', '1768013090001', 'Av. Pérez Guerrero y Bolívar, Ibarra', 'Ibarra', '062-951100', 'ibarra@cnt.gob.ec', 'Ing. Gerente Regional', 'Ing. Jefe de Talento Humano', '0997654321', 'talentohumano.ibarra@cnt.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(10, 'Cruz Roja Ecuatoriana - Junta Provincial Imbabura', '1060014030001', 'Calle Oviedo y Sucre, Ibarra', 'Ibarra', '062-640666', 'imbabura@cruzroja.org.ec', 'Dr. Presidente Provincial', 'Lic. Coordinadora de Voluntariado', '0986543210', 'voluntariado.imbabura@cruzroja.org.ec', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(11, 'Universidad Técnica del Norte', '1060001070001', 'Av. 17 de Julio 5-21, Ibarra', 'Ibarra', '062-997800', 'info@utn.edu.ec', 'Dr. Rector UTN', 'Ing. Director de Vinculación', '0991234567', 'vinculacion@utn.edu.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(12, 'Centro de Salud Tipo C Ibarra', '1768016490001', 'Calle Juan de Velasco y Av. Jaime Roldós, Ibarra', 'Ibarra', '062-612000', 'centro.salud.ibarra@msp.gob.ec', 'Dr. Director Distrital de Salud', 'Lic. Coordinadora Administrativa', '0978123456', 'admin.salud.ibarra@msp.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(13, 'Cooperativa de Ahorro y Crédito Atuntaqui', '1090078248001', 'Calle Bolívar y García Moreno, Atuntaqui', 'Atuntaqui', '062-906247', 'info@coopatuntaqui.fin.ec', 'Ing. Gerente General', 'Lic. Jefe de Recursos Humanos', '0965432198', 'rrhh@coopatuntaqui.fin.ec', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(14, 'Registro Civil de Ibarra', '1760004250001', 'Calle García Moreno y Rocafuerte, Ibarra', 'Ibarra', '062-643188', 'ibarra@registrocivil.gob.ec', 'Dr. Director Provincial', 'Ing. Coordinador de Sistemas', '0954321876', 'sistemas.ibarra@registrocivil.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(15, 'Cámara de Comercio de Ibarra', '1060006330001', 'Calle Colón y Sucre, Ibarra', 'Ibarra', '062-958400', 'info@camaraibarra.org.ec', 'Ing. Presidente Ejecutivo', 'Lic. Secretaria Ejecutiva', '0943210987', 'secretaria@camaraibarra.org.ec', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17');

INSERT INTO `TAB_INSTITUCIONES_CONVENIOS` (`ID_INSTITUCION_CONVENIO`, `ID_TIPO_INSTITUCION`, `ID_ENTIDAD_RECEPTORA`, `NOMBRE`, `RUC`, `DIRECCION`, `CIUDAD`, `TELEFONO`, `EMAIL`, `REPRESENTANTE_LEGAL`, `CONTACTO`, `TELEFONO_CONTACTO`, `EMAIL_CONTACTO`, `LOGO`) VALUES
(1, 1, 1, 'Hospital San Vicente de Paúl', '1234567890001', 'Av. 17 de Julio, Ibarra', 'Ibarra', '062-123456', 'contacto@hospitalsanvicente.com', 'Dr. Juan Pérez', 'Lic. María González', '0987654321', 'maria.gonzalez@hospitalsanvicente.com', NULL),
(2, 2, 2, 'Banco del Pacífico', '0987654321001', 'Av. Amazonas, Quito', 'Quito', '022-987654', 'info@bancodelpacifico.com', 'Sr. Carlos Mendoza', 'Ing. Ana Ruiz', '0912345678', 'ana.ruiz@bancodelpacifico.com', NULL),
(3, 1, 3, 'Fundación Niños del Ecuador', '1122334455001', 'Calle 10 de Agosto, Guayaquil', 'Guayaquil', '042-555666', 'info@ninosdelecuador.org', 'Dra. Sofía Morales', 'Lic. Pedro Aguirre', '0999888777', 'pedro.aguirre@ninosdelecuador.org', NULL),
(4, 1, 4, 'Municipio de Ibarra', '1760001230001', 'Plaza de la Independencia, Ibarra', 'Ibarra', '062-123456', 'info@municipioibarra.gob.ec', 'Alcalde Juan Carlos', 'Secretaria General', '0987654321', 'secretaria@municipioibarra.gob.ec', NULL),
(5, 2, 5, 'Empresa Tecnológica XYZ', '1234567890002', 'Zona Industrial, Ibarra', 'Ibarra', '062-987654', 'info@tecnologiaxyz.com', 'Ing. Director', 'RRHH', '0912345678', 'rrhh@tecnologiaxyz.com', NULL),
(6, 1, 6, 'Casa de la Cultura', '1234567890003', 'Calle Bolívar, Ibarra', 'Ibarra', '062-555777', 'info@casaculturaibarra.gob.ec', 'Lic. Director Cultural', 'Coordinador de Proyectos', '0987654321', 'proyectos@casaculturaibarra.gob.ec', NULL),
(7, 2, 7, 'Fundación Telefónica', '1234567890004', 'Av. 6 de Diciembre, Quito', 'Quito', '022-333444', 'info@fundaciontelefonica.org', 'Director Ejecutivo', 'Coordinador Social', '0912345678', 'social@fundaciontelefonica.org', NULL),
(8, 1, 8, 'GAD Provincial de Imbabura', '1060000580001', 'Av. Mariano Acosta y Circunvalación, Ibarra', 'Ibarra', '062-955118', 'info@imbabura.gob.ec', 'Ing. Prefecto Provincial', 'Lic. Coordinador de Proyectos', '0998123456', 'proyectos@imbabura.gob.ec', NULL),
(9, 1, 9, 'CNT EP Ibarra', '1768013090001', 'Av. Pérez Guerrero y Bolívar, Ibarra', 'Ibarra', '062-951100', 'ibarra@cnt.gob.ec', 'Ing. Gerente Regional', 'Ing. Jefe de Talento Humano', '0997654321', 'talentohumano.ibarra@cnt.gob.ec', NULL),
(10, 2, 10, 'Cruz Roja Ecuatoriana - Junta Provincial Imbabura', '1060014030001', 'Calle Oviedo y Sucre, Ibarra', 'Ibarra', '062-640666', 'imbabura@cruzroja.org.ec', 'Dr. Presidente Provincial', 'Lic. Coordinadora de Voluntariado', '0986543210', 'voluntariado.imbabura@cruzroja.org.ec', NULL),
(11, 1, 11, 'Universidad Técnica del Norte', '1060001070001', 'Av. 17 de Julio 5-21, Ibarra', 'Ibarra', '062-997800', 'info@utn.edu.ec', 'Dr. Rector UTN', 'Ing. Director de Vinculación', '0991234567', 'vinculacion@utn.edu.ec', NULL),
(12, 1, 12, 'Centro de Salud Tipo C Ibarra', '1768016490001', 'Calle Juan de Velasco y Av. Jaime Roldós, Ibarra', 'Ibarra', '062-612000', 'centro.salud.ibarra@msp.gob.ec', 'Dr. Director Distrital de Salud', 'Lic. Coordinadora Administrativa', '0978123456', 'admin.salud.ibarra@msp.gob.ec', NULL),
(13, 2, 13, 'Cooperativa de Ahorro y Crédito Atuntaqui', '1090078248001', 'Calle Bolívar y García Moreno, Atuntaqui', 'Atuntaqui', '062-906247', 'info@coopatuntaqui.fin.ec', 'Ing. Gerente General', 'Lic. Jefe de Recursos Humanos', '0965432198', 'rrhh@coopatuntaqui.fin.ec', NULL),
(14, 1, 14, 'Registro Civil de Ibarra', '1760004250001', 'Calle García Moreno y Rocafuerte, Ibarra', 'Ibarra', '062-643188', 'ibarra@registrocivil.gob.ec', 'Dr. Director Provincial', 'Ing. Coordinador de Sistemas', '0954321876', 'sistemas.ibarra@registrocivil.gob.ec', NULL),
(15, 2, 15, 'Cámara de Comercio de Ibarra', '1060006330001', 'Calle Colón y Sucre, Ibarra', 'Ibarra', '062-958400', 'info@camaraibarra.org.ec', 'Ing. Presidente Ejecutivo', 'Lic. Secretaria Ejecutiva', '0943210987', 'secretaria@camaraibarra.org.ec', NULL);

INSERT INTO `TAB_ASIGNACIONES_PRACTICAS` (`ID_ASIGNACION_PRACTICA`, `ID_TIPO_PRACTICA`, `ID_USUARIO`, `ID_PERIODO_ACADEMICO`, `ID_INSTITUCION_CONVENIO`, `FECHA_INICIO`, `FECHA_FIN`, `HORA_TOTAL`, `DESCRIPCION`) VALUES
(1, 2, 1, 4, 1, '2025-06-01', '2025-08-30', 240, 'Desarrollo e implementación de sistema de gestión hospitalaria'),
(2, 2, 1, 4, 2, '2025-07-01', '2025-09-30', 240, 'Desarrollo de aplicaciones móviles para servicios bancarios'),
(3, 1, 1, 4, 3, '2025-08-01', '2025-10-30', 96, 'Desarrollo de plataforma educativa para niños en situación vulnerable'),
(4, 2, 1, 4, 1, '2025-09-01', '2025-11-30', 240, 'Desarrollo de sistema de gestión hospitalaria para el Hospital San Vicente'),
(5, 2, 1, 4, 2, '2025-10-01', '2025-12-31', 240, 'Desarrollo de aplicación móvil bancaria para Banco del Pacífico'),
(6, 1, 1, 4, 3, '2025-11-01', '2026-01-31', 96, 'Desarrollo de plataforma educativa para Fundación Niños del Ecuador'),
(7, 2, 1, 4, 1, '2025-12-01', '2026-02-28', 240, 'Sistema de gestión hospitalaria avanzado para el Hospital San Vicente'),
(8, 1, 1, 5, 2, '2026-01-01', '2026-03-31', 96, 'Proyecto social de alfabetización digital para Banco del Pacífico'),
(9, 1, 1, 5, 3, '2026-02-01', '2026-04-30', 96, 'Proyecto cultural comunitario para Fundación Niños del Ecuador'),
(10, 1, 1, 5, 1, '2026-03-01', '2026-05-31', 96, 'Proyecto de inclusión digital para el Hospital San Vicente');

INSERT INTO `TAB_PRACTICAS_PREPROFESIONALES` (`ID_PRACTICA_PREPROFESIONAL`, `ID_PERIODO_ACADEMICO`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_DOCENTE_TUTOR`, `ID_INSTITUCION_CONVENIO`, `ID_ESTADO_PREPROFESIONAL`, `AREA_ESPECIALIZACION`, `PROYECTO_ESPECIFICO`, `HORAS_PRACTICAS`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_PRACTICA`, `EVALUACION_FINAL`, `OBSERVACIONES`) VALUES
(1, 4, 1, 1, 1, 1, 2, 'Desarrollo de Software', 'Sistema de gestión de pacientes y citas médicas', 240, '2025-06-01', '2025-08-30', 'En Progreso', NULL, 'Estudiante con buen desempeño en desarrollo web'),
(2, 4, 2, 2, 1, 2, 2, 'Desarrollo Móvil', 'Aplicación móvil para consulta de saldos y transferencias', 240, '2025-07-01', '2025-09-30', 'En Progreso', NULL, 'Proyecto en desarrollo con tecnologías React Native'),
(3, 4, 4, 4, 1, 1, 2, 'Desarrollo de Software', 'Sistema de gestión de historias clínicas digitales', 240, '2025-09-01', '2025-11-30', 'En Progreso', NULL, 'Estudiante con excelente desempeño en desarrollo web'),
(4, 4, 5, 5, 1, 2, 2, 'Administración', 'Apoyo en gestión de proyectos de inclusión financiera y atención al cliente', 240, '2025-10-01', '2025-12-31', 'En Progreso', NULL, 'Práctica preprofesional en entidad financiera');

INSERT INTO `TAB_SERVICIO_COMUNITARIO` (`ID_SERVICIO_COMUNITARIO`, `ID_PERIODO_ACADEMICO`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_DOCENTE_TUTOR`, `ID_INSTITUCION_CONVENIO`, `ID_ESTADO_SERVICIO`, `PROYECTO_SOCIAL`, `COMUNIDAD_BENEFICIADA`, `HORAS_SERVICIO`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_SERVICIO`, `IMPACTO_SOCIAL`, `OBSERVACIONES`) VALUES
(1, 4, 3, 3, 1, 3, 2, 'Plataforma Educativa Digital', 'Niños y adolescentes en situación vulnerable de Guayaquil', 96, '2025-08-01', '2025-10-30', 'En Progreso', 'Mejora en el acceso a educación digital para 200+ niños', 'Proyecto con alto impacto social positivo'),
(2, 4, 6, 4, 1, 3, 2, 'Plataforma Educativa Digital', 'Niños y adolescentes en situación vulnerable de Guayaquil', 96, '2025-11-01', '2026-01-31', 'En Progreso', 'Mejora en el acceso a educación digital para 200+ niños', 'Proyecto con alto impacto social positivo');

INSERT INTO `TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES` (`ID_ASISTENCIA_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `FECHA_ASISTENCIA`, `HORA_ENTRADA`, `HORA_SALIDA`, `ACTIVIDADES_DIA`, `COMPETENCIAS_DESARROLLADAS`, `FECHA_REGISTRO`, `OBSERVACIONES`) VALUES
(1, 1, '2025-08-30', '08:00:00', '17:00:00', 'Desarrollo de módulo de gestión de pacientes, implementación de base de datos, pruebas unitarias', 'Programación en PHP, MySQL, JavaScript, Bootstrap', '2025-08-30 17:30:00', 'Excelente trabajo en el desarrollo del módulo'),
(2, 1, '2025-08-29', '08:00:00', '17:00:00', 'Análisis de requerimientos, diseño de interfaz de usuario, configuración del entorno de desarrollo', 'Análisis de sistemas, diseño UX/UI, configuración de entornos', '2025-08-29 17:15:00', 'Buen análisis de requerimientos del sistema'),
(3, 2, '2025-08-30', '09:00:00', '18:00:00', 'Desarrollo de componentes React Native, integración con API bancaria, pruebas de funcionalidad', 'React Native, integración de APIs, testing móvil', '2025-08-30 18:30:00', 'Progreso satisfactorio en la aplicación móvil'),
(4, 1, '2025-08-28', '08:00:00', '17:00:00', 'Reunión con el equipo de desarrollo, revisión de código, documentación técnica', 'Trabajo en equipo, documentación técnica, revisión de código', '2025-08-28 17:20:00', 'Participación activa en las reuniones de equipo'),
(5, 1, '2025-08-27', '08:00:00', '17:00:00', 'Testing del sistema, corrección de bugs, optimización de consultas', 'Testing de software, resolución de problemas, optimización', '2025-08-27 17:10:00', 'Excelente capacidad para identificar y corregir errores'),
(6, 2, '2025-08-29', '09:00:00', '18:00:00', 'Integración con servicios de pago, implementación de seguridad, pruebas de usuario', 'Integración de APIs, seguridad en aplicaciones móviles, UX testing', '2025-08-29 18:15:00', 'Buen manejo de aspectos de seguridad en la aplicación');

INSERT INTO `TAB_ASISTENCIAS_SERVICIO_COMUNITARIO` (`ID_ASISTENCIA_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `FECHA_ASISTENCIA`, `HORA_ENTRADA`, `HORA_SALIDA`, `ACTIVIDADES_DIA`, `BENEFICIARIOS_ATENDIDOS`, `FECHA_REGISTRO`, `OBSERVACIONES`) VALUES
(1, 1, '2025-08-31', '08:00:00', '16:00:00', 'Capacitación a niños en uso de computadoras, instalación de software educativo, soporte técnico', '25 niños de 8-12 años', '2025-08-31 16:30:00', 'Los niños mostraron gran interés en aprender'),
(2, 1, '2025-08-24', '08:00:00', '16:00:00', 'Desarrollo de contenido educativo digital, creación de tutoriales interactivos', '30 adolescentes de 13-17 años', '2025-08-24 16:45:00', 'Contenido educativo bien recibido por los adolescentes'),
(3, 1, '2025-08-17', '08:00:00', '16:00:00', 'Taller de programación básica para niños, creación de juegos educativos', '20 niños de 10-14 años', '2025-08-17 16:20:00', 'Los niños mostraron gran entusiasmo por aprender programación'),
(4, 1, '2025-08-10', '08:00:00', '16:00:00', 'Capacitación en uso de herramientas digitales, soporte técnico a educadores', '15 educadores y 40 niños', '2025-08-10 16:35:00', 'Capacitación exitosa a los educadores de la fundación');

INSERT INTO `TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES` (`ID_SEGUIMIENTO_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `HORAS_CUMPLIDAS`, `ACTIVIDADES_REALIZADAS`, `COMPETENCIAS_DESARROLLADAS`, `OBSERVACIONES`, `ARCHIVO_REPORTE`, `FECHA_REPORTE`) VALUES
(1, 1, 80, 'Desarrollo de módulo de gestión de pacientes, implementación de base de datos, diseño de interfaz', 'Programación web, gestión de bases de datos, diseño UX/UI', 'El estudiante muestra excelente progreso en el desarrollo del sistema', 'reporte_semanal_1.pdf', '2025-08-30 17:00:00'),
(2, 2, 60, 'Desarrollo de componentes móviles, integración con servicios bancarios, pruebas de funcionalidad', 'Desarrollo móvil, integración de APIs, testing', 'Buen desempeño en el desarrollo de la aplicación móvil', 'reporte_semanal_2.pdf', '2025-08-30 18:00:00'),
(3, 1, 120, 'Desarrollo de módulo de citas médicas, implementación de notificaciones, testing integral', 'Desarrollo web avanzado, notificaciones push, testing automatizado', 'El estudiante ha completado exitosamente el módulo de citas médicas', 'reporte_semanal_3.pdf', '2025-08-15 16:30:00'),
(4, 2, 100, 'Desarrollo de funcionalidades de transferencias, implementación de biometría, pruebas de seguridad', 'Desarrollo móvil avanzado, biometría, seguridad financiera', 'Excelente progreso en las funcionalidades de seguridad de la aplicación', 'reporte_semanal_4.pdf', '2025-08-22 17:45:00');

INSERT INTO `TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO` (`ID_SEGUIMIENTO_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `HORAS_CUMPLIDAS`, `ACTIVIDADES_REALIZADAS`, `BENEFICIARIOS_ATENDIDOS`, `OBSERVACIONES`, `ARCHIVO_REPORTE`, `FECHA_REPORTE`) VALUES
(1, 1, 16, 'Capacitación digital a niños, desarrollo de contenido educativo, soporte técnico', '55 beneficiarios (25 niños + 30 adolescentes)', 'Excelente impacto social, los beneficiarios muestran gran interés en aprender', 'reporte_servicio_1.pdf', '2025-08-31 16:00:00'),
(2, 1, 32, 'Desarrollo de plataforma educativa, capacitación a educadores, soporte técnico continuo', '75 beneficiarios (15 educadores + 60 niños)', 'La plataforma educativa está funcionando correctamente y beneficiando a más personas', 'reporte_servicio_2.pdf', '2025-08-15 15:30:00'),
(3, 1, 48, 'Implementación de nuevas funcionalidades educativas, talleres de robótica básica', '90 beneficiarios (20 educadores + 70 niños)', 'Los talleres de robótica han sido un éxito total entre los beneficiarios', 'reporte_servicio_3.pdf', '2025-08-22 16:15:00');

INSERT INTO `TAB_EXPORTACIONES` (`ID_USUARIO`, `FECHA_EXPORTACION`, `DESCRIPCION_EXPORTACION`, `TIPO_EXPORTACION`, `ESTADO_EXPORTACION`, `ARCHIVO_EXPORTACION`, `TAMANO_ARCHIVO`) VALUES
(1, NOW() - INTERVAL 1 DAY, 'Backup completo del sistema - Respaldo diario', 'backup', 'completado', 'backup_diario_20250101_120000.sql', 5242880),
(1, NOW() - INTERVAL 2 DAY, 'Backup incremental - Cambios del día anterior', 'backup', 'completado', 'backup_incremental_20250102_120000.sql', 1048576),
(1, NOW() - INTERVAL 3 DAY, 'Backup de emergencia - Antes de actualización', 'backup', 'completado', 'backup_emergencia_20250103_120000.sql', 8388608),
(2, NOW() - INTERVAL 4 DAY, 'Backup semanal completo', 'backup', 'completado', 'backup_semanal_20250104_120000.sql', 15728640),
(1, NOW() - INTERVAL 5 DAY, 'Backup antes de mantenimiento', 'backup', 'completado', 'backup_mantenimiento_20250105_120000.sql', 6291456);

INSERT INTO `TAB_EMPLEADOS` (`ID_EMPLEADO`, `ID_DEPARTAMENTO`, `ID_DATO_PERSONA`, `ID_TIPO_CONTRATO`, `CARGO`, `FECHA_INGRESO`) VALUES
(1, 1, 4, 1, 'Coordinador de Vinculación con la Sociedad', '2024-01-15'),
(2, 2, 5, 1, 'Director Académico', '2023-08-01'),
(3, 3, 6, 2, 'Investigador Senior', '2024-03-10');

INSERT INTO `TAB_EMPLEADOS_INSTRUCTORES` (`ID_EMPLEADO_INSTRUCTOR`, `ID_EMPLEADO`, `ID_INSTRUCTOR`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `TAB_DETALLES_CONVENIOS` (`ID_DETALLE_CONVENIO`, `ID_TIPO_CONVENIO`, `ID_INSTITUCION_CONVENIO`, `ID_CARRERA`, `FECHA_INICIO`, `FECHA_FIN`, `DURACION`, `OBJETIVO`, `OBSERVACIONES`, `ARCHIVO_CONVENIO`, `RENOVABLE`, `PLAZAS_DISPONIBLES`) VALUES
-- Convenios originales
(1, 1, 1, 1, '2025-01-01', '2025-12-31', '12 meses', 'Establecer convenio para prácticas preprofesionales en el área de salud', 'Convenio renovable anualmente', 'convenio_hospital_2025.pdf', 1, 0),
(2, 2, 2, 2, '2025-02-01', '2026-01-31', '12 meses', 'Convenio para servicio comunitario en el sector financiero', 'Convenio para proyectos de impacto social', 'convenio_banco_2025.pdf', 1, 0),
(3, 3, 3, 3, '2025-03-01', '2025-12-31', '10 meses', 'Convenio mixto para prácticas y servicio comunitario', 'Convenio integral para múltiples actividades', 'convenio_fundacion_2025.pdf', 1, 0),
(4, 3, 4, 1, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para desarrollo de sistemas de gestión municipal', 'Convenio mixto para prácticas y servicio comunitario en el GAD Municipal', 'convenio_municipio_2025.pdf', 1, 5),
(5, 3, 4, 2, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para diseño de imagen y comunicación visual institucional', 'Convenio mixto para diseño gráfico en el municipio', 'convenio_municipio_dg_2025.pdf', 1, 4),
(6, 3, 4, 3, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para mantenimiento de infraestructura de red municipal', 'Convenio mixto para redes y telecomunicaciones', 'convenio_municipio_redes_2025.pdf', 1, 3),
(7, 3, 4, 4, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para apoyo en gestión administrativa municipal', 'Convenio mixto para prácticas administrativas', 'convenio_municipio_admin_2025.pdf', 1, 5),
(8, 3, 4, 5, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para atención integral a adultos mayores en programas sociales', 'Convenio de servicio comunitario para programas de adultos mayores', 'convenio_municipio_adultos_2025.pdf', 1, 6),
(9, 3, 4, 6, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para estrategias de marketing digital y promoción turística', 'Convenio mixto para marketing digital municipal', 'convenio_municipio_mkt_2025.pdf', 1, 4),
(10, 1, 5, 1, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para desarrollo de software empresarial', 'Convenio de prácticas preprofesionales en empresa tecnológica', 'convenio_xyz_sw_2025.pdf', 1, 8),
(11, 1, 5, 3, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para administración de redes corporativas', 'Convenio de prácticas en infraestructura de TI', 'convenio_xyz_redes_2025.pdf', 1, 4),
(12, 1, 5, 6, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para marketing digital de productos tecnológicos', 'Convenio de prácticas en marketing digital', 'convenio_xyz_mkt_2025.pdf', 1, 3),
(13, 2, 6, 2, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para diseño gráfico cultural y exposiciones', 'Convenio de servicio comunitario en diseño cultural', 'convenio_casacultura_dg_2025.pdf', 1, 5),
(14, 2, 6, 6, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para promoción digital de eventos culturales', 'Convenio de servicio comunitario en marketing cultural', 'convenio_casacultura_mkt_2025.pdf', 1, 4),
(15, 3, 7, 1, '2025-03-01', '2026-02-28', '12 meses', 'Convenio para desarrollo de plataformas educativas digitales', 'Convenio mixto para desarrollo de software educativo', 'convenio_telefonica_sw_2025.pdf', 1, 6),
(16, 3, 7, 3, '2025-03-01', '2026-02-28', '12 meses', 'Convenio para infraestructura de conectividad educativa', 'Convenio mixto para redes en programas de conectividad', 'convenio_telefonica_redes_2025.pdf', 1, 4),
(17, 3, 8, 1, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para modernización tecnológica del GAD Provincial', 'Convenio mixto para desarrollo de software gubernamental', 'convenio_gadimbabura_sw_2025.pdf', 1, 5),
(18, 3, 8, 2, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para diseño de material comunicacional provincial', 'Convenio mixto para diseño gráfico institucional', 'convenio_gadimbabura_dg_2025.pdf', 1, 4),
(19, 3, 8, 3, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para modernización de red provincial de telecomunicaciones', 'Convenio mixto para infraestructura de red', 'convenio_gadimbabura_redes_2025.pdf', 1, 3),
(20, 3, 8, 4, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para gestión administrativa y procesos organizacionales', 'Convenio mixto para prácticas administrativas provinciales', 'convenio_gadimbabura_admin_2025.pdf', 1, 5),
(21, 3, 8, 5, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para programas de atención a adultos mayores en parroquias', 'Convenio de servicio comunitario en atención social', 'convenio_gadimbabura_adultos_2025.pdf', 1, 6),
(22, 3, 8, 6, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para promoción turística digital de Imbabura', 'Convenio mixto para marketing digital provincial', 'convenio_gadimbabura_mkt_2025.pdf', 1, 4),
(23, 3, 9, 1, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para desarrollo de aplicaciones de telecomunicaciones', 'Convenio mixto para desarrollo de software en CNT', 'convenio_cnt_sw_2025.pdf', 1, 5),
(24, 3, 9, 2, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para diseño de material publicitario y corporativo de CNT', 'Convenio mixto para diseño gráfico en telecomunicaciones', 'convenio_cnt_dg_2025.pdf', 1, 3),
(25, 3, 9, 3, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para gestión y mantenimiento de redes de telecomunicaciones', 'Convenio mixto para infraestructura de telecomunicaciones', 'convenio_cnt_redes_2025.pdf', 1, 8),
(26, 3, 9, 4, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para gestión administrativa y atención al cliente', 'Convenio mixto para prácticas administrativas en CNT', 'convenio_cnt_admin_2025.pdf', 1, 4),
(27, 2, 9, 5, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para alfabetización digital de adultos mayores', 'Convenio de servicio comunitario para inclusión digital', 'convenio_cnt_adultos_2025.pdf', 1, 5),
(28, 3, 9, 6, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para estrategias de marketing digital y comercio electrónico', 'Convenio mixto para marketing digital en telecomunicaciones', 'convenio_cnt_mkt_2025.pdf', 1, 4),
(29, 2, 10, 1, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para desarrollo de sistemas de gestión humanitaria', 'Convenio de servicio comunitario en desarrollo de software', 'convenio_cruzroja_sw_2025.pdf', 1, 4),
(30, 2, 10, 2, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para diseño de campañas de sensibilización social', 'Convenio de servicio comunitario en diseño gráfico', 'convenio_cruzroja_dg_2025.pdf', 1, 3),
(31, 2, 10, 3, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para mantenimiento de infraestructura tecnológica humanitaria', 'Convenio de servicio comunitario en redes', 'convenio_cruzroja_redes_2025.pdf', 1, 3),
(32, 2, 10, 4, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para apoyo en gestión administrativa humanitaria', 'Convenio de servicio comunitario en administración', 'convenio_cruzroja_admin_2025.pdf', 1, 5),
(33, 2, 10, 5, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para atención integral a adultos mayores en situación de vulnerabilidad', 'Convenio de servicio comunitario en atención a adultos mayores', 'convenio_cruzroja_adultos_2025.pdf', 1, 8),
(34, 2, 10, 6, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para difusión digital de campañas humanitarias', 'Convenio de servicio comunitario en marketing digital', 'convenio_cruzroja_mkt_2025.pdf', 1, 3),
(35, 3, 11, 1, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para desarrollo de proyectos de investigación en TI', 'Convenio mixto con la UTN para desarrollo de software', 'convenio_utn_sw_2025.pdf', 1, 6),
(36, 3, 11, 2, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para diseño gráfico en proyectos académicos y culturales', 'Convenio mixto con la UTN para diseño gráfico', 'convenio_utn_dg_2025.pdf', 1, 4),
(37, 3, 11, 3, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para investigación en redes y telecomunicaciones', 'Convenio mixto con la UTN para infraestructura de red', 'convenio_utn_redes_2025.pdf', 1, 4),
(38, 3, 11, 4, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para apoyo en procesos administrativos universitarios', 'Convenio mixto con la UTN para gestión administrativa', 'convenio_utn_admin_2025.pdf', 1, 5),
(39, 3, 11, 5, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para programas de atención a adultos mayores en comunidades', 'Convenio de servicio comunitario con la UTN', 'convenio_utn_adultos_2025.pdf', 1, 5),
(40, 3, 11, 6, '2025-04-01', '2026-03-31', '12 meses', 'Convenio para estrategias de comunicación digital universitaria', 'Convenio mixto con la UTN para marketing digital', 'convenio_utn_mkt_2025.pdf', 1, 3),
(41, 3, 12, 1, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para desarrollo de sistemas de información de salud', 'Convenio mixto para digitalización de procesos de salud', 'convenio_centrosalud_sw_2025.pdf', 1, 4),
(42, 3, 12, 2, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para diseño de material educativo y preventivo de salud', 'Convenio mixto para campañas gráficas de salud', 'convenio_centrosalud_dg_2025.pdf', 1, 3),
(43, 3, 12, 3, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para mantenimiento de red informática del centro de salud', 'Convenio mixto para infraestructura tecnológica de salud', 'convenio_centrosalud_redes_2025.pdf', 1, 3),
(44, 3, 12, 4, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para apoyo en gestión administrativa de salud pública', 'Convenio mixto para prácticas administrativas en salud', 'convenio_centrosalud_admin_2025.pdf', 1, 5),
(45, 3, 12, 5, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para atención integral a adultos mayores en servicios de salud', 'Convenio de servicio comunitario en atención geriátrica', 'convenio_centrosalud_adultos_2025.pdf', 1, 8),
(46, 3, 12, 6, '2025-05-01', '2026-04-30', '12 meses', 'Convenio para promoción digital de campañas de salud preventiva', 'Convenio mixto para marketing digital en salud pública', 'convenio_centrosalud_mkt_2025.pdf', 1, 3),
(47, 3, 13, 1, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para desarrollo de plataformas financieras digitales', 'Convenio mixto para desarrollo de software financiero', 'convenio_coopatuntaqui_sw_2025.pdf', 1, 4),
(48, 3, 13, 2, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para diseño de imagen corporativa y material publicitario', 'Convenio mixto para diseño gráfico financiero', 'convenio_coopatuntaqui_dg_2025.pdf', 1, 3),
(49, 3, 13, 3, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para administración de redes y seguridad informática', 'Convenio mixto para infraestructura de red financiera', 'convenio_coopatuntaqui_redes_2025.pdf', 1, 3),
(50, 3, 13, 4, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para prácticas en gestión financiera y administrativa', 'Convenio mixto para prácticas administrativas financieras', 'convenio_coopatuntaqui_admin_2025.pdf', 1, 6),
(51, 2, 13, 5, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para educación financiera a adultos mayores', 'Convenio de servicio comunitario en inclusión financiera', 'convenio_coopatuntaqui_adultos_2025.pdf', 1, 5),
(52, 3, 13, 6, '2025-06-01', '2026-05-31', '12 meses', 'Convenio para marketing digital y comercio electrónico cooperativo', 'Convenio mixto para marketing digital financiero', 'convenio_coopatuntaqui_mkt_2025.pdf', 1, 4),
(53, 3, 14, 1, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para desarrollo de sistemas de registro e identificación', 'Convenio mixto para modernización de sistemas de registro civil', 'convenio_regcivil_sw_2025.pdf', 1, 4),
(54, 3, 14, 2, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para diseño de imagen institucional y señalética', 'Convenio mixto para diseño gráfico institucional', 'convenio_regcivil_dg_2025.pdf', 1, 3),
(55, 3, 14, 3, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para mantenimiento de infraestructura de red y conectividad', 'Convenio mixto para infraestructura tecnológica del registro civil', 'convenio_regcivil_redes_2025.pdf', 1, 3),
(56, 3, 14, 4, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para apoyo en procesos administrativos y atención ciudadana', 'Convenio mixto para prácticas administrativas en registro civil', 'convenio_regcivil_admin_2025.pdf', 1, 5),
(57, 2, 14, 5, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para atención preferencial a adultos mayores en trámites civiles', 'Convenio de servicio comunitario en atención al adulto mayor', 'convenio_regcivil_adultos_2025.pdf', 1, 5),
(58, 3, 14, 6, '2025-07-01', '2026-06-30', '12 meses', 'Convenio para digitalización de servicios y comunicación digital', 'Convenio mixto para marketing digital institucional', 'convenio_regcivil_mkt_2025.pdf', 1, 3),
(59, 3, 15, 1, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para desarrollo de plataforma de comercio electrónico regional', 'Convenio mixto para desarrollo de software comercial', 'convenio_camaracom_sw_2025.pdf', 1, 5),
(60, 3, 15, 2, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para diseño de marca e identidad visual para emprendedores', 'Convenio mixto para diseño gráfico empresarial', 'convenio_camaracom_dg_2025.pdf', 1, 4),
(61, 3, 15, 3, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para asesoría en infraestructura tecnológica a comerciantes', 'Convenio mixto para redes y telecomunicaciones comerciales', 'convenio_camaracom_redes_2025.pdf', 1, 3),
(62, 3, 15, 4, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para apoyo en gestión empresarial y comercial', 'Convenio mixto para prácticas administrativas comerciales', 'convenio_camaracom_admin_2025.pdf', 1, 6),
(63, 2, 15, 5, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para capacitación empresarial a adultos mayores emprendedores', 'Convenio de servicio comunitario en emprendimiento senior', 'convenio_camaracom_adultos_2025.pdf', 1, 4),
(64, 3, 15, 6, '2025-08-01', '2026-07-31', '12 meses', 'Convenio para estrategias de marketing digital y comercio electrónico', 'Convenio mixto para marketing digital comercial', 'convenio_camaracom_mkt_2025.pdf', 1, 5);

INSERT INTO `TAB_INSTITUCION_CARRERA` (`ID_INSTITUCION_CARRERA`, `ID_CARRERA`, `ID_INSTITUCION_CONVENIO`) VALUES
-- Hospital San Vicente de Paúl (todas las carreras)
(1, 1, 1),   -- Desarrollo de Software
(2, 2, 1),   -- Diseño Gráfico
(3, 3, 1),   -- Redes y Telecomunicaciones
(4, 4, 1),   -- Administración
(5, 5, 1),   -- Atención Integral a Adultos Mayores
(6, 6, 1),   -- Marketing Digital y Comercio Electrónico
-- Banco del Pacífico (todas las carreras)
(7, 1, 2),
(8, 2, 2),
(9, 3, 2),
(10, 4, 2),
(11, 5, 2),
(12, 6, 2),
-- Fundación Niños del Ecuador (todas las carreras)
(13, 1, 3),
(14, 2, 3),
(15, 3, 3),
(16, 4, 3),
(17, 5, 3),
(18, 6, 3),
-- Municipio de Ibarra (todas las carreras)
(19, 1, 4),
(20, 2, 4),
(21, 3, 4),
(22, 4, 4),
(23, 5, 4),
(24, 6, 4),
-- Empresa Tecnológica XYZ (todas las carreras)
(25, 1, 5),
(26, 2, 5),
(27, 3, 5),
(28, 4, 5),
(29, 5, 5),
(30, 6, 5),
-- Casa de la Cultura (todas las carreras)
(31, 1, 6),
(32, 2, 6),
(33, 3, 6),
(34, 4, 6),
(35, 5, 6),
(36, 6, 6),
-- Fundación Telefónica (todas las carreras)
(37, 1, 7),
(38, 2, 7),
(39, 3, 7),
(40, 4, 7),
(41, 5, 7),
(42, 6, 7),
-- GAD Provincial de Imbabura (todas las carreras)
(43, 1, 8),
(44, 2, 8),
(45, 3, 8),
(46, 4, 8),
(47, 5, 8),
(48, 6, 8),
-- CNT EP Ibarra (todas las carreras)
(49, 1, 9),
(50, 2, 9),
(51, 3, 9),
(52, 4, 9),
(53, 5, 9),
(54, 6, 9),
-- Cruz Roja Ecuatoriana - Imbabura (todas las carreras)
(55, 1, 10),
(56, 2, 10),
(57, 3, 10),
(58, 4, 10),
(59, 5, 10),
(60, 6, 10),
-- Universidad Técnica del Norte (todas las carreras)
(61, 1, 11),
(62, 2, 11),
(63, 3, 11),
(64, 4, 11),
(65, 5, 11),
(66, 6, 11),
-- Centro de Salud Tipo C Ibarra (todas las carreras)
(67, 1, 12),
(68, 2, 12),
(69, 3, 12),
(70, 4, 12),
(71, 5, 12),
(72, 6, 12),
-- Cooperativa de Ahorro y Crédito Atuntaqui (todas las carreras)
(73, 1, 13),
(74, 2, 13),
(75, 3, 13),
(76, 4, 13),
(77, 5, 13),
(78, 6, 13),
-- Registro Civil de Ibarra (todas las carreras)
(79, 1, 14),
(80, 2, 14),
(81, 3, 14),
(82, 4, 14),
(83, 5, 14),
(84, 6, 14),
-- Cámara de Comercio de Ibarra (todas las carreras)
(85, 1, 15),
(86, 2, 15),
(87, 3, 15),
(88, 4, 15),
(89, 5, 15),
(90, 6, 15);

INSERT INTO `TAB_DOCUMENTOS_SERVICIO_COMUNITARIO` (`ID_DOCUMENTO_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `ID_TIPO_DOCUMENTO`, `ID_ESTADO_REVISION`, `NOMBRE_ARCHIVO`, `NOMBRE_ORIGINAL`, `TIPO_ARCHIVO`, `TAMANO_ARCHIVO`, `RUTA_ARCHIVO`, `FECHA_SUBIDA`, `FECHA_REVISION`, `ID_REVISOR`, `OBSERVACIONES`, `OBSERVACIONES_REVISOR`, `VERSION`) VALUES
(7, 1, 4, 3, 'solicitud_institucional_sc_001_20250804.pdf', 'Solicitud Institucional SC - Rector.pdf', 'application/pdf', 298496, '/uploads/documentos-servicio/', '2025-08-04 13:00:00', '2025-08-04 15:10:00', 1, 'Solicitud institucional valorada para servicio comunitario', 'Solicitud aprobada por el rector', 1),
(8, 1, 5, 3, 'certificado_culminacion_sc_001_20251030.pdf', 'Certificado Culminación SC - 96 horas.pdf', 'application/pdf', 201728, '/uploads/documentos-servicio/', '2025-10-30 15:00:00', '2025-10-30 17:30:00', 1, 'Certificado de culminación de 96 horas de servicio comunitario', 'Certificado válido y completo', 1),
(9, 1, 6, 3, 'hojas_asistencia_sc_001_20251030.pdf', 'Hojas de Asistencia SC - Carlos.pdf', 'application/pdf', 123456, '/uploads/documentos-servicio/', '2025-10-30 15:15:00', '2025-10-30 17:45:00', 1, 'Hojas de asistencia completas y validadas para servicio comunitario', 'Hojas de asistencia validadas correctamente', 1),
(10, 1, 7, 3, 'ficha_registro_actividades_sc_001_20251015.pdf', 'Ficha Registro Actividades SC - Carlos.pdf', 'application/pdf', 98765, '/uploads/documentos-servicio/', '2025-10-15 11:30:00', '2025-10-15 14:20:00', 1, 'Ficha de registro de actividades de servicio comunitario', 'Ficha completa y detallada', 1),
(11, 1, 8, 3, 'rubrica_evaluacion_entidad_sc_001_20251025.pdf', 'Rúbrica Evaluación Entidad SC - Carlos.pdf', 'application/pdf', 87654, '/uploads/documentos-servicio/', '2025-10-25 15:00:00', '2025-10-25 16:30:00', 1, 'Rúbrica de evaluación de entidad para servicio comunitario', 'Rúbrica completada correctamente', 1),
(12, 1, 9, 3, 'ficha_control_seguimiento_sc_001_20251020.pdf', 'Ficha Control Seguimiento SC - Carlos.pdf', 'application/pdf', 112233, '/uploads/documentos-servicio/', '2025-10-20 10:15:00', '2025-10-20 12:45:00', 1, 'Ficha de control y seguimiento docente para servicio comunitario', 'Seguimiento realizado correctamente', 1),
(15, 1, 1, 3, 'oficio_asignacion_tutor_sc_001_20250801.pdf', 'Oficio Asignación Tutor SC - Carlos.pdf', 'application/pdf', 234880, '/uploads/documentos-servicio/', '2025-08-01 10:00:00', '2025-08-01 14:30:00', 1, 'Documento oficial de asignación para servicio comunitario', 'Documento aprobado correctamente', 1),
(16, 1, 2, 3, 'oficio_entidad_receptora_sc_001_20250802.pdf', 'Oficio Entidad Receptora SC - Fundación.pdf', 'application/pdf', 189440, '/uploads/documentos-servicio/', '2025-08-02 14:30:00', '2025-08-02 16:45:00', 1, 'Oficio enviado a la entidad receptora para servicio comunitario', 'Oficio bien redactado y formal', 1),
(17, 1, 3, 3, 'carta_aceptacion_sc_001_20250803.pdf', 'Carta Aceptación SC - Fundación.pdf', 'application/pdf', 156672, '/uploads/documentos-servicio/', '2025-08-03 09:15:00', '2025-08-03 11:20:00', 1, 'Carta de aceptación de la entidad para servicio comunitario', 'Carta oficial con sello institucional', 1),
(18, 1, 4, 3, 'solicitud_institucional_sc_001_20250804.pdf', 'Solicitud Institucional SC - Rector.pdf', 'application/pdf', 298496, '/uploads/documentos-servicio/', '2025-08-04 13:00:00', '2025-08-04 15:10:00', 1, 'Solicitud institucional valorada para servicio comunitario', 'Solicitud aprobada por el rector', 1),
(19, 1, 5, 3, 'certificado_culminacion_sc_001_20251030.pdf', 'Certificado Culminación SC - 96 horas.pdf', 'application/pdf', 201728, '/uploads/documentos-servicio/', '2025-10-30 15:00:00', '2025-10-30 17:30:00', 1, 'Certificado de culminación de 96 horas de servicio comunitario', 'Certificado válido y completo', 1),
(20, 2, 1, 1, 'oficio_asignacion_tutor_sc_002_20251101.pdf', 'Oficio Asignación Tutor SC - Ana Lucía.pdf', 'application/pdf', 234880, '/uploads/documentos-servicio/', '2025-11-01 09:00:00', NULL, NULL, 'Documento pendiente de revisión', NULL, 1),
(21, 2, 2, 1, 'oficio_entidad_receptora_sc_002_20251102.pdf', 'Oficio Entidad Receptora SC - Fundación.pdf', 'application/pdf', 189440, '/uploads/documentos-servicio/', '2025-11-02 14:00:00', NULL, NULL, 'Oficio enviado a la entidad receptora', NULL, 1),
(22, 2, 3, 1, 'carta_aceptacion_sc_002_20251103.pdf', 'Carta Aceptación SC - Fundación.pdf', 'application/pdf', 156672, '/uploads/documentos-servicio/', '2025-11-03 10:30:00', NULL, NULL, 'Carta de aceptación de la entidad', NULL, 1),
(25, 1, 6, 5, 'hojas_asistencia_sc_001_20251030.pdf', 'Hojas de Asistencia SC - Carlos.pdf', 'application/pdf', 123456, '/uploads/documentos-servicio/', '2025-10-30 15:15:00', '2025-10-30 17:45:00', 1, 'Hojas de asistencia completas para servicio comunitario', 'Faltan firmas en algunas fechas, corregir y volver a subir', 1),
(26, 1, 7, 5, 'ficha_registro_actividades_sc_001_20251015.pdf', 'Ficha Registro Actividades SC - Carlos.pdf', 'application/pdf', 98765, '/uploads/documentos-servicio/', '2025-10-15 11:30:00', '2025-10-15 14:20:00', 1, 'Ficha de registro de actividades de servicio comunitario', 'Descripción de actividades muy general, especificar más detalles', 1),
(27, 1, 8, 4, 'rubrica_evaluacion_entidad_sc_001_20251025.pdf', 'Rúbrica Evaluación Entidad SC - Carlos.pdf', 'application/pdf', 87654, '/uploads/documentos-servicio/', '2025-10-25 15:00:00', '2025-10-25 16:30:00', 1, 'Rúbrica de evaluación de entidad para servicio comunitario', 'Documento no tiene sello oficial de la entidad, rechazado', 1),
(28, 1, 9, 4, 'ficha_control_seguimiento_sc_001_20251020.pdf', 'Ficha Control Seguimiento SC - Carlos.pdf', 'application/pdf', 112233, '/uploads/documentos-servicio/', '2025-10-20 10:15:00', '2025-10-20 12:45:00', 1, 'Ficha de control y seguimiento docente para servicio comunitario', 'Faltan las firmas del tutor docente, documento inválido', 1);

INSERT INTO `TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES` (`ID_EVALUACION_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `ID_EVALUADOR`, `TIPO_EVALUACION`, `CRITERIO_1`, `CRITERIO_2`, `CRITERIO_3`, `CRITERIO_4`, `CRITERIO_5`, `NOTA_FINAL`, `COMENTARIOS`, `FECHA_EVALUACION`) VALUES
(1, 1, 1, 'Evaluación Parcial', 8.5, 9.0, 8.0, 8.5, 9.0, 8.6, 'Excelente desempeño en el desarrollo del sistema. Muestra competencias técnicas sólidas.', '2025-08-15 16:00:00'),
(2, 2, 2, 'Evaluación Parcial', 7.5, 8.0, 7.0, 8.5, 8.0, 7.8, 'Buen progreso en la aplicación móvil. Necesita mejorar en la documentación del código.', '2025-08-20 17:30:00'),
(3, 1, 1, 'Evaluación Final', 9.0, 9.5, 8.5, 9.0, 9.5, 9.1, 'Excelente trabajo final. El sistema desarrollado cumple con todos los requerimientos y está listo para producción.', '2025-08-30 18:00:00'),
(4, 2, 2, 'Evaluación Final', 8.0, 8.5, 7.5, 8.0, 8.5, 8.1, 'Buen trabajo en la aplicación móvil. Se recomienda mejorar la documentación y optimizar algunas funcionalidades.', '2025-09-30 17:00:00');

INSERT INTO `TAB_EVALUACIONES_SERVICIO_COMUNITARIO` (`ID_EVALUACION_SERVICIO`, `ID_SERVICIO_COMUNITARIO`, `ID_EVALUADOR`, `TIPO_EVALUACION`, `CRITERIO_1`, `CRITERIO_2`, `CRITERIO_3`, `CRITERIO_4`, `CRITERIO_5`, `NOTA_FINAL`, `COMENTARIOS`, `FECHA_EVALUACION`) VALUES
(1, 1, 3, 'Evaluación Parcial', 9.0, 9.5, 8.5, 9.0, 9.5, 9.1, 'Excelente impacto social. Los beneficiarios muestran gran satisfacción con el proyecto educativo.', '2025-08-25 15:00:00'),
(2, 1, 3, 'Evaluación Final', 9.5, 9.5, 9.0, 9.5, 9.5, 9.4, 'Proyecto excepcional con impacto social muy positivo. La plataforma educativa ha beneficiado significativamente a la comunidad.', '2025-10-30 16:00:00');

INSERT INTO `TAB_EVALUACIONES_ENLACES` (`ID_EVALUACION_ENLACE`, `ID_ACTIVIDAD_EDUCACION`, `ID_USUARIO_CREADOR`, `NOMBRE_EVALUACION`, `TIPO_EVALUACION`, `ENLACE_FORMULARIO`, `DESCRIPCION`, `FECHA_CREACION`, `FECHA_VENCIMIENTO`, `ESTADO`, `NUMERO_RESPUESTAS`, `ACTIVO`) VALUES
(1, 1, 1, 'Encuesta de satisfacción - Desarrollo Web Full Stack', 'satisfaccion', 'https://forms.google.com/evaluacion-web-fullstack', 'Encuesta de satisfacción del curso de desarrollo web', '2026-03-20 10:00:00', '2026-05-15', 'activo', 15, 1),
(2, 2, 1, 'Encuesta de satisfacción - Reparación de Equipos', 'satisfaccion', 'https://forms.google.com/evaluacion-reparacion-equipos', 'Encuesta de satisfacción del taller de reparación de equipos', '2026-03-20 10:10:00', '2026-05-20', 'activo', 8, 1),
(3, 3, 1, 'Encuesta de satisfacción - Seminario de IA', 'satisfaccion', 'https://forms.google.com/evaluacion-seminario-ia', 'Encuesta de satisfacción del seminario de inteligencia artificial', '2026-03-20 10:20:00', '2026-05-25', 'activo', 0, 1),
(4, 4, 1, 'Encuesta de satisfacción - Programación en Python', 'satisfaccion', 'https://forms.google.com/evaluacion-python', 'Encuesta de satisfacción del curso de programación en Python', '2026-03-20 10:30:00', '2026-05-30', 'activo', 0, 1),
(5, 5, 1, 'Encuesta de satisfacción - Configuración de Redes', 'satisfaccion', 'https://forms.google.com/evaluacion-redes', 'Encuesta de satisfacción del taller de redes', '2026-03-20 10:40:00', '2026-06-05', 'activo', 0, 1),
(6, 10, 1, 'Encuesta de satisfacción - Bases de Datos', 'satisfaccion', 'https://forms.google.com/evaluacion-bases-datos', 'Encuesta de satisfacción del curso de bases de datos', '2026-03-20 10:50:00', '2026-06-10', 'activo', 0, 1),
(7, 11, 1, 'Encuesta de satisfacción - Soporte Técnico', 'satisfaccion', 'https://forms.google.com/evaluacion-soporte-tecnico', 'Encuesta de satisfacción del taller de soporte técnico', '2026-03-20 11:00:00', '2026-06-15', 'activo', 0, 1),
(8, 12, 1, 'Encuesta de satisfacción - IA en la práctica', 'satisfaccion', 'https://forms.google.com/evaluacion-ia-practica', 'Encuesta de satisfacción de la conferencia de IA', '2026-03-20 11:10:00', '2026-06-20', 'activo', 0, 1),
(9, 13, 1, 'Encuesta de satisfacción - Ética y Datos', 'satisfaccion', 'https://forms.google.com/evaluacion-etica-datos', 'Encuesta de satisfacción de la capacitación en ética y datos', '2026-03-20 11:20:00', '2026-06-25', 'activo', 0, 1);

INSERT INTO `TAB_ASIGNACIONES_DOCENTES_PRACTICAS` (`ID_ASIGNACION_DOCENTE`, `ID_PRACTICA_PREPROFESIONAL`, `ID_SERVICIO_COMUNITARIO`, `ID_DOCENTE_TUTOR`, `TIPO_ASIGNACION`, `FECHA_ASIGNACION`, `FECHA_FIN`, `OBSERVACIONES`, `ACTIVO`) VALUES
(1, 1, NULL, 1, 'Principal', '2025-06-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(2, 2, NULL, 2, 'Principal', '2025-07-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(3, 3, NULL, 1, 'Principal', '2025-09-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(4, 4, NULL, 2, 'Principal', '2025-10-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(5, NULL, 1, 3, 'Principal', '2025-08-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(6, NULL, 2, 3, 'Principal', '2025-11-01 05:00:00', NULL, 'Tutor principal asignado', 1);

INSERT INTO `TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES` (`ID_DOCUMENTO_PREPROFESIONAL`, `ID_PRACTICA_PREPROFESIONAL`, `ID_TIPO_DOCUMENTO`, `ID_ESTADO_REVISION`, `NOMBRE_ARCHIVO`, `NOMBRE_ORIGINAL`, `TIPO_ARCHIVO`, `TAMANO_ARCHIVO`, `RUTA_ARCHIVO`, `FECHA_SUBIDA`, `FECHA_REVISION`, `ID_REVISOR`, `OBSERVACIONES`, `OBSERVACIONES_REVISOR`, `VERSION`) VALUES
(1, 1, 1, 3, 'oficio_asignacion_tutor_001_20250601.pdf', 'Oficio Asignación Tutor - Juan Carlos.pdf', 'application/pdf', 245760, '/uploads/documentos-practicas/', '2025-06-01 10:00:00', '2025-06-01 14:30:00', 4, 'Documento oficial de asignación', 'Documento aprobado correctamente', 1),
(2, 1, 2, 3, 'oficio_entidad_receptora_001_20250602.pdf', 'Oficio Entidad Receptora - Hospital.pdf', 'application/pdf', 189440, '/uploads/documentos-practicas/', '2025-06-02 14:30:00', '2025-06-02 16:45:00', 4, 'Oficio enviado a la entidad receptora', 'Oficio bien redactado y formal', 1),
(3, 1, 3, 3, 'carta_aceptacion_001_20250603.pdf', 'Carta Aceptación - Hospital.pdf', 'application/pdf', 156672, '/uploads/documentos-practicas/', '2025-06-03 09:15:00', '2025-06-03 11:20:00', 4, 'Carta de aceptación de la entidad', 'Carta oficial con sello institucional', 1),
(4, 2, 1, 3, 'oficio_asignacion_tutor_002_20250701.pdf', 'Oficio Asignación Tutor - María Elena.pdf', 'application/pdf', 234880, '/uploads/documentos-practicas/', '2025-07-01 11:00:00', '2025-07-01 13:15:00', 5, 'Documento oficial de asignación', 'Asignación correcta del tutor', 1),
(5, 2, 2, 3, 'oficio_entidad_receptora_002_20250702.pdf', 'Oficio Entidad Receptora - Banco.pdf', 'application/pdf', 178944, '/uploads/documentos-practicas/', '2025-07-02 15:45:00', '2025-07-02 17:20:00', 5, 'Oficio enviado a la entidad receptora', 'Oficio formal y bien estructurado', 1),
(14, 1, 6, 5, 'hojas_asistencia_001_20250830.pdf', 'Hojas de Asistencia - Juan Carlos.pdf', 'application/pdf', 123456, '/uploads/documentos-practicas/', '2025-08-30 16:15:00', '2025-08-30 17:45:00', 4, 'Hojas de asistencia completas', 'Faltan firmas en algunas fechas, corregir y volver a subir', 1),
(15, 2, 7, 5, 'ficha_registro_actividades_002_20250915.pdf', 'Ficha Registro Actividades - María.pdf', 'application/pdf', 98765, '/uploads/documentos-practicas/', '2025-09-15 11:30:00', '2025-09-15 14:20:00', 5, 'Ficha de registro de actividades', 'Descripción de actividades muy general, especificar más detalles', 1),
(16, 1, 8, 4, 'rubrica_evaluacion_entidad_001_20250825.pdf', 'Rúbrica Evaluación Entidad - Juan.pdf', 'application/pdf', 87654, '/uploads/documentos-practicas/', '2025-08-25 15:00:00', '2025-08-25 16:30:00', 4, 'Rúbrica de evaluación de entidad', 'Documento no tiene sello oficial de la entidad, rechazado', 1),
(17, 2, 9, 4, 'ficha_control_seguimiento_002_20250920.pdf', 'Ficha Control Seguimiento - María.pdf', 'application/pdf', 112233, '/uploads/documentos-practicas/', '2025-09-20 10:15:00', '2025-09-20 12:45:00', 5, 'Ficha de control y seguimiento docente', 'Faltan las firmas del tutor docente, documento inválido', 1);

INSERT INTO `TAB_NOTIFICACIONES_DOCUMENTOS` (`ID_DOCUMENTO_PREPROFESIONAL`, `ID_USUARIO_DESTINATARIO`, `TIPO_NOTIFICACION`, `TITULO`, `MENSAJE`, `LEIDA`) VALUES
(1, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Asignación Tutor - Juan Carlos.pdf" ha sido aprobado por el revisor.', true),
(2, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Entidad Receptora - Hospital.pdf" ha sido aprobado por el revisor.', true),
(3, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Carta Aceptación - Hospital.pdf" ha sido aprobado por el revisor.', true),
(4, 8, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Asignación Tutor - María Elena.pdf" ha sido aprobado por el revisor.', true),
(5, 8, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Entidad Receptora - Banco.pdf" ha sido aprobado por el revisor.', true),
(14, 7, 'Requiere Corrección', 'Documento Requiere Corrección', 'El documento "Hojas de Asistencia - Juan Carlos.pdf" requiere correcciones: Faltan firmas en algunas fechas, corregir y volver a subir.', false),
(15, 8, 'Requiere Corrección', 'Documento Requiere Corrección', 'El documento "Ficha Registro Actividades - María.pdf" requiere correcciones: Descripción de actividades muy general, especificar más detalles.', false),
(16, 7, 'Rechazado', 'Documento Rechazado', 'El documento "Rúbrica Evaluación Entidad - Juan.pdf" ha sido rechazado: Documento no tiene sello oficial de la entidad, rechazado.', false),
(17, 8, 'Rechazado', 'Documento Rechazado', 'El documento "Ficha Control Seguimiento - María.pdf" ha sido rechazado: Faltan las firmas del tutor docente, documento inválido.', false);

INSERT INTO `TAB_HISTORIAL_CAMBIOS_DOCUMENTOS` (`ID_DOCUMENTO_PREPROFESIONAL`, `ID_USUARIO`, `TIPO_CAMBIO`, `VALOR_ANTERIOR`, `VALOR_NUEVO`, `OBSERVACIONES`) VALUES
(1, 4, 'Estado', 'Pendiente', 'Aprobado', 'Documento revisado y aprobado correctamente'),
(2, 4, 'Estado', 'Pendiente', 'Aprobado', 'Oficio bien redactado y formal'),
(3, 4, 'Estado', 'Pendiente', 'Aprobado', 'Carta oficial con sello institucional'),
(4, 5, 'Estado', 'Pendiente', 'Aprobado', 'Asignación correcta del tutor'),
(5, 5, 'Estado', 'Pendiente', 'Aprobado', 'Oficio formal y bien estructurado'),
(14, 4, 'Estado', 'Pendiente', 'Requiere Corrección', 'Faltan firmas en algunas fechas, corregir y volver a subir'),
(15, 5, 'Estado', 'Pendiente', 'Requiere Corrección', 'Descripción de actividades muy general, especificar más detalles'),
(16, 4, 'Estado', 'Pendiente', 'Rechazado', 'Documento no tiene sello oficial de la entidad, rechazado'),
(17, 5, 'Estado', 'Pendiente', 'Rechazado', 'Faltan las firmas del tutor docente, documento inválido');

SET FOREIGN_KEY_CHECKS = 1;
