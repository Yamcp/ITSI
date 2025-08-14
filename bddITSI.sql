-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2025 a las 03:28:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `itsi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_actividades_educacion`
--

CREATE TABLE `tab_actividades_educacion` (
  `ID_ACTIVIDAD_EDUCACION` int(11) NOT NULL,
  `ID_AREA_TEMATICA` int(11) DEFAULT NULL,
  `ID_INSTRUCTOR` int(11) DEFAULT NULL,
  `ID_TIPO_MODALIDAD` int(11) DEFAULT NULL,
  `ID_TIPO_ACTIVIDAD` int(11) DEFAULT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `NOMBRE_ACTIVIDAD` varchar(200) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  `OBJETIVOS` text NOT NULL,
  `DURACION_HORAS` int(11) NOT NULL,
  `FECHA_INICIO` date NOT NULL,
  `FECHA_FIN` date NOT NULL,
  `LUGAR` varchar(150) NOT NULL,
  `HORARIO` varchar(100) NOT NULL,
  `INCLUYE_CERTIFICADO` tinyint(1) NOT NULL,
  `PROGRAMA_DETALLADO` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_areas_tematicas`
--

CREATE TABLE `tab_areas_tematicas` (
  `ID_AREA_TEMATICA` int(11) NOT NULL,
  `ID_ASIGNACION_PRACTICA` int(11) DEFAULT NULL,
  `NOMBRE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_asignaciones_practicas`
--

CREATE TABLE `tab_asignaciones_practicas` (
  `ID_ASIGNACION_PRACTICA` int(11) NOT NULL,
  `ID_TIPO_PRACTICA` int(11) DEFAULT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ID_ESTADO_PRACTICAS` int(11) DEFAULT NULL,
  `FECHA_INICIO` date NOT NULL,
  `FECHA_FIN` date NOT NULL,
  `HORA_TOTAL` int(11) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  `CRONOGRAMA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_asignaturas`
--

CREATE TABLE `tab_asignaturas` (
  `ID_ASIGNATURA` int(11) NOT NULL,
  `ID_CARRERA` int(11) DEFAULT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `SEMESTRE` int(11) NOT NULL,
  `CREDITOS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_asistencias_practicas`
--

CREATE TABLE `tab_asistencias_practicas` (
  `ID_ASISTENCIA` int(11) NOT NULL,
  `ID_ASIGNACION_PRACTICA` int(11) DEFAULT NULL,
  `FECHA_ASISTENCIA` date DEFAULT NULL,
  `HORA_ENTRADA` time NOT NULL,
  `HORA_SALIDA` time NOT NULL,
  `ACTIVIDADES_DIA` text NOT NULL,
  `FECHA_REGISTRO` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `OBSERVACIONES` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_carreras`
--

CREATE TABLE `tab_carreras` (
  `ID_CARRERA` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `DURACION_SEMESTRES` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_convenios`
--

CREATE TABLE `tab_convenios` (
  `ID_CONVENIO` int(11) NOT NULL,
  `ID_TIPO_CONVENIO` int(11) DEFAULT NULL,
  `ID_INSTITUCION_CONVENIO` int(11) DEFAULT NULL,
  `FECHA_INICIO` date NOT NULL,
  `FECHA_FIN` date NOT NULL,
  `OBSERVACIONES` text NOT NULL,
  `ARCHIVO_CONVENIO` varchar(255) NOT NULL,
  `RENOVABLE` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_datos_personas`
--

CREATE TABLE `tab_datos_personas` (
  `ID_DATO_PERSONA` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `APELLIDO` varchar(100) NOT NULL,
  `CEDULA` varchar(10) NOT NULL,
  `CELULAR` varchar(10) NOT NULL,
  `DIRECCION` text NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `FECHA_NACIMIENTO` date NOT NULL,
  `GENERO` varchar(15) NOT NULL,
  `ESTADO_CIVIL` varchar(20) NOT NULL,
  `NACIONALIDAD` varchar(50) DEFAULT NULL,
  `FECHA_INGRESO` date NOT NULL,
  `ACTIVO` tinyint(1) NOT NULL,
  `FOTO_URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tab_datos_personas`
--

INSERT INTO `tab_datos_personas` (`ID_DATO_PERSONA`, `NOMBRE`, `APELLIDO`, `CEDULA`, `CELULAR`, `DIRECCION`, `EMAIL`, `FECHA_NACIMIENTO`, `GENERO`, `ESTADO_CIVIL`, `NACIONALIDAD`, `FECHA_INGRESO`, `ACTIVO`, `FOTO_URL`) VALUES
(1, 'Yamilex Marisol', 'Campues Angamarca', '1004191845', '0992432078', 'Ibarra', 'yamilex.campues2023@itsi.edu.ec', '2000-10-17', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-06-05', 1, ''),
(2, 'Ana ', 'Yandun', '1724143290', '0981377492', 'Ibarra', 'ana.yandun2023@itsi.edu.ec', '0000-00-00', 'Femenino', 'Casada', 'Ecuatoriana', '2025-06-10', 1, ''),
(3, 'Pedro', 'Aguirre', '0123456789', '', '', '', '0000-00-00', '', '', NULL, '0000-00-00', 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_departamentos`
--

CREATE TABLE `tab_departamentos` (
  `ID_DEPARTAMENTO` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `RESPONSABLE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_documentos_investigacion`
--

CREATE TABLE `tab_documentos_investigacion` (
  `ID_DOCUMENTO` int(11) NOT NULL,
  `ID_AREA_TEMATICA` int(11) DEFAULT NULL,
  `TITULO` varchar(255) NOT NULL,
  `AUTORES` text NOT NULL,
  `RESUMEN` text NOT NULL,
  `VIABLE` tinyint(1) NOT NULL,
  `ARCHIVO` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_empleados`
--

CREATE TABLE `tab_empleados` (
  `ID_EMPLEADO` int(11) NOT NULL,
  `ID_DEPARTAMENTO` int(11) DEFAULT NULL,
  `ID_DATO_PERSONA` int(11) DEFAULT NULL,
  `ID_TIPO_CONTRATO` int(11) DEFAULT NULL,
  `CARGO` varchar(100) NOT NULL,
  `FECHA_INGRESO` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_estado_practicas`
--

CREATE TABLE `tab_estado_practicas` (
  `ID_ESTADO_PRACTICAS` int(11) NOT NULL,
  `ESTADO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_estudiantes`
--

CREATE TABLE `tab_estudiantes` (
  `ID_ESTUDIANTE` int(11) NOT NULL,
  `ID_TIPO_ESTADO` int(11) DEFAULT NULL,
  `ID_ASIGNATURA` int(11) DEFAULT NULL,
  `ID_DATO_PERSONA` int(11) DEFAULT NULL,
  `SEMESTRE_ACTUAL` int(11) NOT NULL,
  `PROMEDIO_GENERAL` decimal(4,2) NOT NULL,
  `MATERIAS_APROBADAS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_exportaciones`
--

CREATE TABLE `tab_exportaciones` (
  `ID_EXPORTACION` int(11) NOT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `FECHA_EXPORTACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DESCRIPCION_EXPORTACION` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_instituciones_convenios`
--

CREATE TABLE `tab_instituciones_convenios` (
  `ID_INSTITUCION_CONVENIO` int(11) NOT NULL,
  `ID_TIPO_INSTITUCION` int(11) DEFAULT NULL,
  `NOMBRE` varchar(200) NOT NULL,
  `RUC` varchar(20) NOT NULL,
  `DIRECCION` text NOT NULL,
  `TELEFONO` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `REPRESENTANTE_LEGAL` varchar(150) NOT NULL,
  `CONTACTO` varchar(150) NOT NULL,
  `TELEFONO_CONTACTO` varchar(20) NOT NULL,
  `EMAIL_CONTACTO` varchar(100) NOT NULL,
  `AREA_INTERES` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_instructores`
--

CREATE TABLE `tab_instructores` (
  `ID_INSTRUCTOR` int(11) NOT NULL,
  `ID_TIPO_INSTRUCTOR` int(11) DEFAULT NULL,
  `ID_DATO_PERSONA` int(11) DEFAULT NULL,
  `ID_EMPLEADO` int(11) DEFAULT NULL,
  `ESPECIALIDAD` text NOT NULL,
  `TITULO_PROFESIONAL` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_roles`
--

CREATE TABLE `tab_roles` (
  `ID_ROL` int(11) NOT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ID_TIPOS_ROLES` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tab_roles`
--

INSERT INTO `tab_roles` (`ID_ROL`, `ID_USUARIO`, `ID_TIPOS_ROLES`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_seguimiento_practicas`
--

CREATE TABLE `tab_seguimiento_practicas` (
  `ID_SEGUIMIENTO` int(11) NOT NULL,
  `ID_ASIGNACION_PRACTICA` int(11) DEFAULT NULL,
  `HORAS_CUMPLIDAS` int(11) NOT NULL,
  `ACTIVIDADES_REALIZADAS` text NOT NULL,
  `OBSERVACIONES` text NOT NULL,
  `ARCHIVO_REPORTE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_actividades`
--

CREATE TABLE `tab_tipos_actividades` (
  `ID_TIPO_ACTIVIDAD` int(11) NOT NULL,
  `ACTIVIDAD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_convenios`
--

CREATE TABLE `tab_tipos_convenios` (
  `ID_TIPO_CONVENIO` int(11) NOT NULL,
  `CONVENIO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_estados`
--

CREATE TABLE `tab_tipos_estados` (
  `ID_TIPO_ESTADO` int(11) NOT NULL,
  `ESTADO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_institucion`
--

CREATE TABLE `tab_tipos_institucion` (
  `ID_TIPO_INSTITUCION` int(11) NOT NULL,
  `INSTITUCION` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_modalidades`
--

CREATE TABLE `tab_tipos_modalidades` (
  `ID_TIPO_MODALIDAD` int(11) NOT NULL,
  `MODALIDAD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_practicas`
--

CREATE TABLE `tab_tipos_practicas` (
  `ID_TIPO_PRACTICA` int(11) NOT NULL,
  `PRACTICA` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipos_roles`
--

CREATE TABLE `tab_tipos_roles` (
  `ID_TIPOS_ROLES` int(11) NOT NULL,
  `ROL` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tab_tipos_roles`
--

INSERT INTO `tab_tipos_roles` (`ID_TIPOS_ROLES`, `ROL`) VALUES
(1, 'Administrador'),
(2, 'Docente'),
(3, 'Estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipo_contrato`
--

CREATE TABLE `tab_tipo_contrato` (
  `ID_TIPO_CONTRATO` int(11) NOT NULL,
  `TIPO_CONTRATO` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_tipo_instructores`
--

CREATE TABLE `tab_tipo_instructores` (
  `ID_TIPO_INSTRUCTOR` int(11) NOT NULL,
  `TIPO` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tab_usuarios`
--

CREATE TABLE `tab_usuarios` (
  `ID_USUARIO` int(11) NOT NULL,
  `ID_DATO_PERSONA` int(11) DEFAULT NULL,
  `USUARIO` varchar(20) NOT NULL,
  `CONTRASENA` varchar(60) NOT NULL,
  `ESTADO` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tab_usuarios`
--

INSERT INTO `tab_usuarios` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(1, 1, 'ycampues', '123', '1'),
(2, 2, 'ayandun', '123', '1'),
(3, 3, 'paguirre', '123', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tab_actividades_educacion`
--
ALTER TABLE `tab_actividades_educacion`
  ADD PRIMARY KEY (`ID_ACTIVIDAD_EDUCACION`),
  ADD KEY `FK_REFERENCE_28` (`ID_AREA_TEMATICA`),
  ADD KEY `FK_REFERENCE_29` (`ID_INSTRUCTOR`),
  ADD KEY `FK_REFERENCE_31` (`ID_TIPO_MODALIDAD`),
  ADD KEY `FK_REFERENCE_32` (`ID_TIPO_ACTIVIDAD`),
  ADD KEY `FK_REFERENCE_38` (`ID_USUARIO`);

--
-- Indices de la tabla `tab_areas_tematicas`
--
ALTER TABLE `tab_areas_tematicas`
  ADD PRIMARY KEY (`ID_AREA_TEMATICA`),
  ADD KEY `FK_REFERENCE_23` (`ID_ASIGNACION_PRACTICA`);

--
-- Indices de la tabla `tab_asignaciones_practicas`
--
ALTER TABLE `tab_asignaciones_practicas`
  ADD PRIMARY KEY (`ID_ASIGNACION_PRACTICA`),
  ADD KEY `FK_REFERENCE_11` (`ID_TIPO_PRACTICA`),
  ADD KEY `FK_REFERENCE_22` (`ID_USUARIO`),
  ADD KEY `FK_REFERENCE_36` (`ID_ESTADO_PRACTICAS`);

--
-- Indices de la tabla `tab_asignaturas`
--
ALTER TABLE `tab_asignaturas`
  ADD PRIMARY KEY (`ID_ASIGNATURA`),
  ADD KEY `FK_REFERENCE_18` (`ID_CARRERA`);

--
-- Indices de la tabla `tab_asistencias_practicas`
--
ALTER TABLE `tab_asistencias_practicas`
  ADD PRIMARY KEY (`ID_ASISTENCIA`),
  ADD KEY `FK_REFERENCE_9` (`ID_ASIGNACION_PRACTICA`);

--
-- Indices de la tabla `tab_carreras`
--
ALTER TABLE `tab_carreras`
  ADD PRIMARY KEY (`ID_CARRERA`);

--
-- Indices de la tabla `tab_convenios`
--
ALTER TABLE `tab_convenios`
  ADD PRIMARY KEY (`ID_CONVENIO`),
  ADD KEY `FK_REFERENCE_34` (`ID_TIPO_CONVENIO`),
  ADD KEY `FK_REFERENCE_35` (`ID_INSTITUCION_CONVENIO`);

--
-- Indices de la tabla `tab_datos_personas`
--
ALTER TABLE `tab_datos_personas`
  ADD PRIMARY KEY (`ID_DATO_PERSONA`);

--
-- Indices de la tabla `tab_departamentos`
--
ALTER TABLE `tab_departamentos`
  ADD PRIMARY KEY (`ID_DEPARTAMENTO`);

--
-- Indices de la tabla `tab_documentos_investigacion`
--
ALTER TABLE `tab_documentos_investigacion`
  ADD PRIMARY KEY (`ID_DOCUMENTO`),
  ADD KEY `FK_REFERENCE_10` (`ID_AREA_TEMATICA`);

--
-- Indices de la tabla `tab_empleados`
--
ALTER TABLE `tab_empleados`
  ADD PRIMARY KEY (`ID_EMPLEADO`),
  ADD KEY `FK_REFERENCE_19` (`ID_DATO_PERSONA`),
  ADD KEY `FK_REFERENCE_24` (`ID_TIPO_CONTRATO`),
  ADD KEY `FK_REFERENCE_4` (`ID_DEPARTAMENTO`);

--
-- Indices de la tabla `tab_estado_practicas`
--
ALTER TABLE `tab_estado_practicas`
  ADD PRIMARY KEY (`ID_ESTADO_PRACTICAS`);

--
-- Indices de la tabla `tab_estudiantes`
--
ALTER TABLE `tab_estudiantes`
  ADD PRIMARY KEY (`ID_ESTUDIANTE`),
  ADD KEY `FK_REFERENCE_20` (`ID_TIPO_ESTADO`),
  ADD KEY `FK_REFERENCE_39` (`ID_ASIGNATURA`),
  ADD KEY `FK_REFERENCE_40` (`ID_DATO_PERSONA`);

--
-- Indices de la tabla `tab_exportaciones`
--
ALTER TABLE `tab_exportaciones`
  ADD PRIMARY KEY (`ID_EXPORTACION`),
  ADD KEY `FK_REFERENCE_17` (`ID_USUARIO`);

--
-- Indices de la tabla `tab_instituciones_convenios`
--
ALTER TABLE `tab_instituciones_convenios`
  ADD PRIMARY KEY (`ID_INSTITUCION_CONVENIO`),
  ADD KEY `FK_REFERENCE_33` (`ID_TIPO_INSTITUCION`);

--
-- Indices de la tabla `tab_instructores`
--
ALTER TABLE `tab_instructores`
  ADD PRIMARY KEY (`ID_INSTRUCTOR`),
  ADD KEY `FK_REFERENCE_25` (`ID_TIPO_INSTRUCTOR`),
  ADD KEY `FK_REFERENCE_26` (`ID_DATO_PERSONA`),
  ADD KEY `FK_REFERENCE_27` (`ID_EMPLEADO`);

--
-- Indices de la tabla `tab_roles`
--
ALTER TABLE `tab_roles`
  ADD PRIMARY KEY (`ID_ROL`),
  ADD KEY `FK_REFERENCE_7` (`ID_USUARIO`),
  ADD KEY `FK_REFERENCE_8` (`ID_TIPOS_ROLES`);

--
-- Indices de la tabla `tab_seguimiento_practicas`
--
ALTER TABLE `tab_seguimiento_practicas`
  ADD PRIMARY KEY (`ID_SEGUIMIENTO`),
  ADD KEY `FK_REFERENCE_37` (`ID_ASIGNACION_PRACTICA`);

--
-- Indices de la tabla `tab_tipos_actividades`
--
ALTER TABLE `tab_tipos_actividades`
  ADD PRIMARY KEY (`ID_TIPO_ACTIVIDAD`);

--
-- Indices de la tabla `tab_tipos_convenios`
--
ALTER TABLE `tab_tipos_convenios`
  ADD PRIMARY KEY (`ID_TIPO_CONVENIO`);

--
-- Indices de la tabla `tab_tipos_estados`
--
ALTER TABLE `tab_tipos_estados`
  ADD PRIMARY KEY (`ID_TIPO_ESTADO`);

--
-- Indices de la tabla `tab_tipos_institucion`
--
ALTER TABLE `tab_tipos_institucion`
  ADD PRIMARY KEY (`ID_TIPO_INSTITUCION`);

--
-- Indices de la tabla `tab_tipos_modalidades`
--
ALTER TABLE `tab_tipos_modalidades`
  ADD PRIMARY KEY (`ID_TIPO_MODALIDAD`);

--
-- Indices de la tabla `tab_tipos_practicas`
--
ALTER TABLE `tab_tipos_practicas`
  ADD PRIMARY KEY (`ID_TIPO_PRACTICA`);

--
-- Indices de la tabla `tab_tipos_roles`
--
ALTER TABLE `tab_tipos_roles`
  ADD PRIMARY KEY (`ID_TIPOS_ROLES`);

--
-- Indices de la tabla `tab_tipo_contrato`
--
ALTER TABLE `tab_tipo_contrato`
  ADD PRIMARY KEY (`ID_TIPO_CONTRATO`);

--
-- Indices de la tabla `tab_tipo_instructores`
--
ALTER TABLE `tab_tipo_instructores`
  ADD PRIMARY KEY (`ID_TIPO_INSTRUCTOR`);

--
-- Indices de la tabla `tab_usuarios`
--
ALTER TABLE `tab_usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD KEY `FK_REFERENCE_12` (`ID_DATO_PERSONA`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tab_actividades_educacion`
--
ALTER TABLE `tab_actividades_educacion`
  MODIFY `ID_ACTIVIDAD_EDUCACION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_areas_tematicas`
--
ALTER TABLE `tab_areas_tematicas`
  MODIFY `ID_AREA_TEMATICA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_asignaciones_practicas`
--
ALTER TABLE `tab_asignaciones_practicas`
  MODIFY `ID_ASIGNACION_PRACTICA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_asignaturas`
--
ALTER TABLE `tab_asignaturas`
  MODIFY `ID_ASIGNATURA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_asistencias_practicas`
--
ALTER TABLE `tab_asistencias_practicas`
  MODIFY `ID_ASISTENCIA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_carreras`
--
ALTER TABLE `tab_carreras`
  MODIFY `ID_CARRERA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_convenios`
--
ALTER TABLE `tab_convenios`
  MODIFY `ID_CONVENIO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_datos_personas`
--
ALTER TABLE `tab_datos_personas`
  MODIFY `ID_DATO_PERSONA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tab_departamentos`
--
ALTER TABLE `tab_departamentos`
  MODIFY `ID_DEPARTAMENTO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_documentos_investigacion`
--
ALTER TABLE `tab_documentos_investigacion`
  MODIFY `ID_DOCUMENTO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_empleados`
--
ALTER TABLE `tab_empleados`
  MODIFY `ID_EMPLEADO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_estado_practicas`
--
ALTER TABLE `tab_estado_practicas`
  MODIFY `ID_ESTADO_PRACTICAS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_estudiantes`
--
ALTER TABLE `tab_estudiantes`
  MODIFY `ID_ESTUDIANTE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_exportaciones`
--
ALTER TABLE `tab_exportaciones`
  MODIFY `ID_EXPORTACION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_instituciones_convenios`
--
ALTER TABLE `tab_instituciones_convenios`
  MODIFY `ID_INSTITUCION_CONVENIO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_instructores`
--
ALTER TABLE `tab_instructores`
  MODIFY `ID_INSTRUCTOR` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_roles`
--
ALTER TABLE `tab_roles`
  MODIFY `ID_ROL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tab_seguimiento_practicas`
--
ALTER TABLE `tab_seguimiento_practicas`
  MODIFY `ID_SEGUIMIENTO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_actividades`
--
ALTER TABLE `tab_tipos_actividades`
  MODIFY `ID_TIPO_ACTIVIDAD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_convenios`
--
ALTER TABLE `tab_tipos_convenios`
  MODIFY `ID_TIPO_CONVENIO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_estados`
--
ALTER TABLE `tab_tipos_estados`
  MODIFY `ID_TIPO_ESTADO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_institucion`
--
ALTER TABLE `tab_tipos_institucion`
  MODIFY `ID_TIPO_INSTITUCION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_modalidades`
--
ALTER TABLE `tab_tipos_modalidades`
  MODIFY `ID_TIPO_MODALIDAD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_practicas`
--
ALTER TABLE `tab_tipos_practicas`
  MODIFY `ID_TIPO_PRACTICA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipos_roles`
--
ALTER TABLE `tab_tipos_roles`
  MODIFY `ID_TIPOS_ROLES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tab_tipo_contrato`
--
ALTER TABLE `tab_tipo_contrato`
  MODIFY `ID_TIPO_CONTRATO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_tipo_instructores`
--
ALTER TABLE `tab_tipo_instructores`
  MODIFY `ID_TIPO_INSTRUCTOR` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tab_usuarios`
--
ALTER TABLE `tab_usuarios`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tab_actividades_educacion`
--
ALTER TABLE `tab_actividades_educacion`
  ADD CONSTRAINT `FK_REFERENCE_28` FOREIGN KEY (`ID_AREA_TEMATICA`) REFERENCES `tab_areas_tematicas` (`ID_AREA_TEMATICA`),
  ADD CONSTRAINT `FK_REFERENCE_29` FOREIGN KEY (`ID_INSTRUCTOR`) REFERENCES `tab_instructores` (`ID_INSTRUCTOR`),
  ADD CONSTRAINT `FK_REFERENCE_31` FOREIGN KEY (`ID_TIPO_MODALIDAD`) REFERENCES `tab_tipos_modalidades` (`ID_TIPO_MODALIDAD`),
  ADD CONSTRAINT `FK_REFERENCE_32` FOREIGN KEY (`ID_TIPO_ACTIVIDAD`) REFERENCES `tab_tipos_actividades` (`ID_TIPO_ACTIVIDAD`),
  ADD CONSTRAINT `FK_REFERENCE_38` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tab_usuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `tab_areas_tematicas`
--
ALTER TABLE `tab_areas_tematicas`
  ADD CONSTRAINT `FK_REFERENCE_23` FOREIGN KEY (`ID_ASIGNACION_PRACTICA`) REFERENCES `tab_asignaciones_practicas` (`ID_ASIGNACION_PRACTICA`);

--
-- Filtros para la tabla `tab_asignaciones_practicas`
--
ALTER TABLE `tab_asignaciones_practicas`
  ADD CONSTRAINT `FK_REFERENCE_11` FOREIGN KEY (`ID_TIPO_PRACTICA`) REFERENCES `tab_tipos_practicas` (`ID_TIPO_PRACTICA`),
  ADD CONSTRAINT `FK_REFERENCE_22` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tab_usuarios` (`ID_USUARIO`),
  ADD CONSTRAINT `FK_REFERENCE_36` FOREIGN KEY (`ID_ESTADO_PRACTICAS`) REFERENCES `tab_estado_practicas` (`ID_ESTADO_PRACTICAS`);

--
-- Filtros para la tabla `tab_asignaturas`
--
ALTER TABLE `tab_asignaturas`
  ADD CONSTRAINT `FK_REFERENCE_18` FOREIGN KEY (`ID_CARRERA`) REFERENCES `tab_carreras` (`ID_CARRERA`);

--
-- Filtros para la tabla `tab_asistencias_practicas`
--
ALTER TABLE `tab_asistencias_practicas`
  ADD CONSTRAINT `FK_REFERENCE_9` FOREIGN KEY (`ID_ASIGNACION_PRACTICA`) REFERENCES `tab_asignaciones_practicas` (`ID_ASIGNACION_PRACTICA`);

--
-- Filtros para la tabla `tab_convenios`
--
ALTER TABLE `tab_convenios`
  ADD CONSTRAINT `FK_REFERENCE_34` FOREIGN KEY (`ID_TIPO_CONVENIO`) REFERENCES `tab_tipos_convenios` (`ID_TIPO_CONVENIO`),
  ADD CONSTRAINT `FK_REFERENCE_35` FOREIGN KEY (`ID_INSTITUCION_CONVENIO`) REFERENCES `tab_instituciones_convenios` (`ID_INSTITUCION_CONVENIO`);

--
-- Filtros para la tabla `tab_documentos_investigacion`
--
ALTER TABLE `tab_documentos_investigacion`
  ADD CONSTRAINT `FK_REFERENCE_10` FOREIGN KEY (`ID_AREA_TEMATICA`) REFERENCES `tab_areas_tematicas` (`ID_AREA_TEMATICA`);

--
-- Filtros para la tabla `tab_empleados`
--
ALTER TABLE `tab_empleados`
  ADD CONSTRAINT `FK_REFERENCE_19` FOREIGN KEY (`ID_DATO_PERSONA`) REFERENCES `tab_datos_personas` (`ID_DATO_PERSONA`),
  ADD CONSTRAINT `FK_REFERENCE_24` FOREIGN KEY (`ID_TIPO_CONTRATO`) REFERENCES `tab_tipo_contrato` (`ID_TIPO_CONTRATO`),
  ADD CONSTRAINT `FK_REFERENCE_4` FOREIGN KEY (`ID_DEPARTAMENTO`) REFERENCES `tab_departamentos` (`ID_DEPARTAMENTO`);

--
-- Filtros para la tabla `tab_estudiantes`
--
ALTER TABLE `tab_estudiantes`
  ADD CONSTRAINT `FK_REFERENCE_20` FOREIGN KEY (`ID_TIPO_ESTADO`) REFERENCES `tab_tipos_estados` (`ID_TIPO_ESTADO`),
  ADD CONSTRAINT `FK_REFERENCE_39` FOREIGN KEY (`ID_ASIGNATURA`) REFERENCES `tab_asignaturas` (`ID_ASIGNATURA`),
  ADD CONSTRAINT `FK_REFERENCE_40` FOREIGN KEY (`ID_DATO_PERSONA`) REFERENCES `tab_datos_personas` (`ID_DATO_PERSONA`);

--
-- Filtros para la tabla `tab_exportaciones`
--
ALTER TABLE `tab_exportaciones`
  ADD CONSTRAINT `FK_REFERENCE_17` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tab_usuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `tab_instituciones_convenios`
--
ALTER TABLE `tab_instituciones_convenios`
  ADD CONSTRAINT `FK_REFERENCE_33` FOREIGN KEY (`ID_TIPO_INSTITUCION`) REFERENCES `tab_tipos_institucion` (`ID_TIPO_INSTITUCION`);

--
-- Filtros para la tabla `tab_instructores`
--
ALTER TABLE `tab_instructores`
  ADD CONSTRAINT `FK_REFERENCE_25` FOREIGN KEY (`ID_TIPO_INSTRUCTOR`) REFERENCES `tab_tipo_instructores` (`ID_TIPO_INSTRUCTOR`),
  ADD CONSTRAINT `FK_REFERENCE_26` FOREIGN KEY (`ID_DATO_PERSONA`) REFERENCES `tab_datos_personas` (`ID_DATO_PERSONA`),
  ADD CONSTRAINT `FK_REFERENCE_27` FOREIGN KEY (`ID_EMPLEADO`) REFERENCES `tab_empleados` (`ID_EMPLEADO`);

--
-- Filtros para la tabla `tab_roles`
--
ALTER TABLE `tab_roles`
  ADD CONSTRAINT `FK_REFERENCE_7` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tab_usuarios` (`ID_USUARIO`),
  ADD CONSTRAINT `FK_REFERENCE_8` FOREIGN KEY (`ID_TIPOS_ROLES`) REFERENCES `tab_tipos_roles` (`ID_TIPOS_ROLES`);

--
-- Filtros para la tabla `tab_seguimiento_practicas`
--
ALTER TABLE `tab_seguimiento_practicas`
  ADD CONSTRAINT `FK_REFERENCE_37` FOREIGN KEY (`ID_ASIGNACION_PRACTICA`) REFERENCES `tab_asignaciones_practicas` (`ID_ASIGNACION_PRACTICA`);

--
-- Filtros para la tabla `tab_usuarios`
--
ALTER TABLE `tab_usuarios`
  ADD CONSTRAINT `FK_REFERENCE_12` FOREIGN KEY (`ID_DATO_PERSONA`) REFERENCES `tab_datos_personas` (`ID_DATO_PERSONA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
