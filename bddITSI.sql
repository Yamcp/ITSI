/*==============================================================*/
/* ITSI — Esquema y datos iniciales                             */
/* Base de datos: `itsi`                                        */
/* Motor: MySQL 5.7+ / MariaDB 10.2+ · utf8mb4_unicode_ci      */
/* Referencia de script: 31/8/2025                              */
/*--------------------------------------------------------------*/
/* Importación: ejecutar el archivo completo (p. ej.            */
/* mysql -u USUARIO -p < bddITSI.sql). Si usas una              */
/* herramienta gráfica y la importación falla por comprobación  */
/* de claves foráneas, desactiva esa opción solo para este      */
/* archivo o importa desde línea de comandos.                   */
/*==============================================================*/

-- Crear y usar la base de datos
CREATE DATABASE IF NOT EXISTS `itsi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `itsi`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- TAB_PERIODOS_ACADEMICOS: únicamente ID + MES_INICIO, AÑO_INICIO, MES_FIN, AÑO_FIN.
-- Sin nombre, tipo, número, estado, descripción ni auditoría en esta tabla.
-- Vistas (p. ej. V_PERIODO_ACADEMICO_ACTUAL) derivan etiquetas y fechas para la UI.
-- -----------------------------------------------------------------------------

-- Eliminar tablas dependientes primero (en orden inverso de dependencias)
drop table if exists migrations;
drop table if exists TAB_ASISTENCIAS_SERVICIO_COMUNITARIO;
drop table if exists TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_EVALUACIONES_SERVICIO_COMUNITARIO;
drop table if exists TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO;
drop table if exists TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_ASIGNACIONES_DOCENTES_PRACTICAS;
drop table if exists TAB_DOCENTES_TUTORES;
drop table if exists TAB_HISTORIAL_CAMBIOS_DOCUMENTOS;
drop table if exists TAB_NOTIFICACIONES_DOCUMENTOS;
drop table if exists TAB_DOCUMENTOS_SERVICIO_COMUNITARIO;
drop table if exists TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_ESTADOS_REVISIONES;
drop table if exists TAB_EVALUACIONES_ENLACES;
drop table if exists TAB_INSCRIPCIONES_ACTIVIDADES;
drop table if exists TAB_SERVICIO_COMUNITARIO;
drop table if exists TAB_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_ACTIVIDADES_EDUCACION;
drop table if exists TAB_ASIGNACIONES_PRACTICAS;
drop table if exists TAB_PERIODOS_ACADEMICOS;
drop table if exists TAB_EMPLEADOS_INSTRUCTORES;
drop table if exists TAB_EMPLEADOS;
drop table if exists TAB_INSTRUCTORES;
drop table if exists TAB_ESTUDIANTES;
drop table if exists TAB_ROLES;
drop table if exists TAB_DETALLES_CONVENIOS;
drop table if exists TAB_INSTITUCION_CARRERA;
drop table if exists TAB_INSTITUCIONES_CONVENIOS;
drop table if exists TAB_ENTIDADES_RECEPTORAS;
drop table if exists TAB_CARRERAS;
drop table if exists TAB_DEPARTAMENTOS;
drop table if exists TAB_EXPORTACIONES;
drop table if exists TAB_RECUPERACION_CONTRASENA;
drop table if exists TAB_NOTIFICACIONES;
drop table if exists TAB_USUARIOS;
drop table if exists TAB_DATOS_PERSONAS;
drop table if exists TAB_TIPOS_ACTIVIDADES;
drop table if exists TAB_TIPOS_CONVENIOS;
drop table if exists TAB_TIPOS_ESTADOS;
drop table if exists TAB_TIPOS_INSTITUCION;
drop table if exists TAB_TIPOS_MODALIDADES;
drop table if exists TAB_TIPOS_PRACTICAS;
drop table if exists TAB_TIPOS_ROLES;
drop table if exists TAB_TIPOS_CONTRATO;
drop table if exists TAB_TIPOS_INSTRUCTORES;
drop table if exists TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES;
drop table if exists TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO;
drop table if exists TAB_ESTADOS_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_ESTADOS_SERVICIO_COMUNITARIO;

/*==============================================================*/
/* Table: TAB_PERIODOS_ACADEMICOS — solo mes/año inicio y fin    */
/*==============================================================*/
create table TAB_PERIODOS_ACADEMICOS
(
   ID_PERIODO_ACADEMICO int not null auto_increment,
   MES_INICIO           tinyint unsigned not null comment 'Mes inicio 1-12',
   AÑO_INICIO           int not null comment 'Año inicio',
   MES_FIN              tinyint unsigned not null comment 'Mes fin 1-12',
   AÑO_FIN              int not null comment 'Año fin',
   primary key (ID_PERIODO_ACADEMICO),
   unique key UK_PERIODO_RANGO_MES_ANIO (AÑO_INICIO, MES_INICIO, AÑO_FIN, MES_FIN),
   key IDX_PERIODO_MES_ANIO_INICIO (AÑO_INICIO, MES_INICIO),
   check (MES_INICIO between 1 and 12),
   check (MES_FIN between 1 and 12)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Solo rango académico: mes/año inicio y mes/año fin';

/*==============================================================*/
/* Table: TAB_ACTIVIDADES_EDUCACION                             */
/* ENLACE: reunión en línea / enlace virtual (tras LUGAR).      */
/* Integrado desde docs/sql/alter_tab_actividades_educacion_    */
/* enlace.sql — en importación completa de este archivo la      */
/* columna ya existe en CREATE. Si tienes una base antigua sin  */
/* ENLACE y no reimportas el dump, ejecuta una sola vez:        */
/*   ALTER TABLE TAB_ACTIVIDADES_EDUCACION                      */
/*       ADD COLUMN ENLACE VARCHAR(500) NULL DEFAULT NULL       */
/*       AFTER LUGAR;                                           */
/*==============================================================*/
create table TAB_ACTIVIDADES_EDUCACION
(
   ID_ACTIVIDAD_EDUCACION int not null auto_increment,
   ID_INSTRUCTOR        int,
   ID_TIPO_MODALIDAD    int,
   ID_TIPO_ACTIVIDAD    int,
   ID_USUARIO           int,
   ID_PERIODO_ACADEMICO int,
   NOMBRE_ACTIVIDAD     varchar(200) not null,
   DESCRIPCION          text not null,
   OBJETIVOS            text not null,
   DURACION_HORAS       int not null,
   FECHA_INICIO         date not null,
   FECHA_FIN            date not null,
   LUGAR                varchar(150) not null,
   ENLACE               varchar(500) null comment 'Enlace virtual o reunión en línea',
   HORARIO              varchar(100) not null,
   INCLUYE_CERTIFICADO  boolean not null,
   PROGRAMA_DETALLADO   text not null,
   primary key (ID_ACTIVIDAD_EDUCACION),
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO)
);

/*==============================================================*/
/* Table: TAB_ASIGNACIONES_PRACTICAS                            */
/*==============================================================*/
create table TAB_ASIGNACIONES_PRACTICAS
(
   ID_ASIGNACION_PRACTICA int not null auto_increment,
   ID_PERIODO_ACADEMICO int,
   ID_TIPO_PRACTICA     int,
   ID_USUARIO           int,
   ID_ESTADO_PRACTICAS  int,
   ID_INSTITUCION_CONVENIO int,
   FECHA_INICIO         date not null,
   FECHA_FIN            date,
   HORA_TOTAL           int not null,
   DESCRIPCION          text not null,
   primary key (ID_ASIGNACION_PRACTICA),
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO)
);

/*==============================================================*/
/* Table: TAB_CARRERAS                                          */
/*==============================================================*/
create table TAB_CARRERAS
(
   ID_CARRERA           int not null auto_increment,
   NOMBRE               varchar(100) not null,
   primary key (ID_CARRERA)
);

/*==============================================================*/
/* Table: TAB_DATOS_PERSONAS                                    */
/*==============================================================*/
create table TAB_DATOS_PERSONAS
(
   ID_DATO_PERSONA      int not null auto_increment,
   NOMBRE               varchar(100) not null,
   APELLIDO             varchar(100) not null,
   CEDULA               varchar(10) not null,
   CELULAR              varchar(10) not null,
   DIRECCION            text not null,
   EMAIL                varchar(100) not null,
   GENERO               varchar(15) not null,
   ESTADO_CIVIL         varchar(20) not null,
   NACIONALIDAD         varchar(50) not null,
   FECHA_INGRESO        date not null,
   ACTIVO               boolean not null,
   FOTO_URL             varchar(255) not null,
   primary key (ID_DATO_PERSONA)
);

/*==============================================================*/
/* Table: TAB_DEPARTAMENTOS                                     */
/*==============================================================*/
create table TAB_DEPARTAMENTOS
(
   ID_DEPARTAMENTO      int not null auto_increment,
   NOMBRE               varchar(100) not null,
   RESPONSABLE          varchar(100) not null,
   primary key (ID_DEPARTAMENTO)
);

/*==============================================================*/
/* Table: TAB_DETALLES_CONVENIOS                                */
/*==============================================================*/
create table TAB_DETALLES_CONVENIOS
(
   ID_DETALLE_CONVENIO  int not null auto_increment,
   ID_TIPO_CONVENIO     int,
   ID_INSTITUCION_CONVENIO int,
   ID_CARRERA           int not null,
   FECHA_INICIO         date not null,
   FECHA_FIN            date not null,
   DURACION             varchar(20) not null,
   OBJETIVO             text not null,
   OBSERVACIONES        text not null,
   ARCHIVO_CONVENIO     varchar(255) not null,
   RENOVABLE            boolean not null,
   PLAZAS_DISPONIBLES   int default 0,
   primary key (ID_DETALLE_CONVENIO)
);

/*==============================================================*/
/* Table: TAB_EMPLEADOS                                         */
/*==============================================================*/
create table TAB_EMPLEADOS
(
   ID_EMPLEADO          int not null auto_increment,
   ID_DEPARTAMENTO      int,
   ID_DATO_PERSONA      int,
   ID_TIPO_CONTRATO     int,
   CARGO                varchar(100) not null,
   FECHA_INGRESO        date not null,
   primary key (ID_EMPLEADO)
);

/*==============================================================*/
/* Table: TAB_EMPLEADOS_INSTRUCTORES                            */
/*==============================================================*/
create table TAB_EMPLEADOS_INSTRUCTORES
(
   ID_EMPLEADO_INSTRUCTOR int not null auto_increment,
   ID_EMPLEADO          int,
   ID_INSTRUCTOR        int,
   primary key (ID_EMPLEADO_INSTRUCTOR)
);

/*==============================================================*/
/* Table: TAB_ESTUDIANTES                                       */
/*==============================================================*/
create table TAB_ESTUDIANTES
(
   ID_ESTUDIANTE        int not null auto_increment,
   ID_TIPO_ESTADO       int,
   ID_DATO_PERSONA      int,
   ID_CARRERA           int,
   SEMESTRE_ACTUAL      int not null,
   primary key (ID_ESTUDIANTE)
);

/*==============================================================*/
/* Table: TAB_INSCRIPCIONES_ACTIVIDADES                         */
/*==============================================================*/
create table TAB_INSCRIPCIONES_ACTIVIDADES
(
   ID_INSCRIPCION       int not null auto_increment,
   ID_ACTIVIDAD_EDUCACION int,
   ID_ESTUDIANTE        int,
   FECHA_INSCRIPCION    date,
   ESTADO               varchar(30) default 'Inscrito',
   primary key (ID_INSCRIPCION),
   unique key UK_INSCRIPCION_ACTIVIDAD_ESTUDIANTE (ID_ACTIVIDAD_EDUCACION, ID_ESTUDIANTE),
   key IDX_INSCRIPCION_ACTIVIDAD (ID_ACTIVIDAD_EDUCACION),
   key IDX_INSCRIPCION_ESTUDIANTE (ID_ESTUDIANTE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_EXPORTACIONES                                     */
/*==============================================================*/
create table TAB_EXPORTACIONES
(
   ID_EXPORTACION       int not null auto_increment,
   ID_USUARIO           int,
   FECHA_EXPORTACION    timestamp not null,
   DESCRIPCION_EXPORTACION varchar(100),
   TIPO_EXPORTACION     varchar(50) DEFAULT 'backup',
   ESTADO_EXPORTACION   varchar(50) DEFAULT 'completado',
   ARCHIVO_EXPORTACION  varchar(255) NULL,
   TAMANO_ARCHIVO       bigint NULL,
   FECHA_CREACION       timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION  timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_EXPORTACION)
);

/*==============================================================*/
/* Table: TAB_INSTITUCIONES_CONVENIOS                           */
/*==============================================================*/
create table TAB_INSTITUCIONES_CONVENIOS
(
   ID_INSTITUCION_CONVENIO int not null auto_increment,
   ID_TIPO_INSTITUCION  int,
   ID_ENTIDAD_RECEPTORA int,
   NOMBRE               varchar(200) not null,
   RUC                  varchar(20) not null,
   DIRECCION            text not null,
   CIUDAD               varchar(50) not null,
   TELEFONO             varchar(20) not null,
   EMAIL                varchar(100) not null,
   REPRESENTANTE_LEGAL  varchar(150) not null,
   CONTACTO             varchar(150) not null,
   TELEFONO_CONTACTO    varchar(20) not null,
   EMAIL_CONTACTO       varchar(100) not null,
   LOGO                 varchar(255) null,
   primary key (ID_INSTITUCION_CONVENIO)
);

/*==============================================================*/
/* Table: TAB_INSTITUCION_CARRERA                               */
/*==============================================================*/
create table TAB_INSTITUCION_CARRERA
(
   ID_INSTITUCION_CARRERA int not null auto_increment,
   ID_CARRERA           int,
   ID_INSTITUCION_CONVENIO int,
   primary key (ID_INSTITUCION_CARRERA)
);

/*==============================================================*/
/* Table: TAB_TIPOS_INSTRUCTORES                                 */
/*==============================================================*/
create table TAB_TIPOS_INSTRUCTORES
(
   ID_TIPO_INSTRUCTOR   int not null auto_increment,
   TIPO                 varchar(100) not null,
   primary key (ID_TIPO_INSTRUCTOR)
);

/*==============================================================*/
/* Table: TAB_INSTRUCTORES                                      */
/*==============================================================*/
create table TAB_INSTRUCTORES
(
   ID_INSTRUCTOR        int not null auto_increment,
   ID_TIPO_INSTRUCTOR   int,
   ID_DATO_PERSONA      int,
   ESPECIALIDAD         text not null,
   TITULO_PROFESIONAL   varchar(200) not null,
   primary key (ID_INSTRUCTOR)
);

/*==============================================================*/
/* Table: TAB_ROLES                                             */
/*==============================================================*/
create table TAB_ROLES
(
   ID_ROL               int not null auto_increment,
   ID_USUARIO           int,
   ID_TIPOS_ROLES       int,
   primary key (ID_ROL)
);

/*==============================================================*/
/* Table: TAB_TIPOS_ACTIVIDADES                                 */
/*==============================================================*/
create table TAB_TIPOS_ACTIVIDADES
(
   ID_TIPO_ACTIVIDAD    int not null auto_increment,
   ACTIVIDAD            varchar(20) not null,
   primary key (ID_TIPO_ACTIVIDAD)
);

/*==============================================================*/
/* Table: TAB_TIPOS_CONVENIOS                                   */
/*==============================================================*/
create table TAB_TIPOS_CONVENIOS
(
   ID_TIPO_CONVENIO     int not null auto_increment,
   CONVENIO             varchar(20) not null,
   primary key (ID_TIPO_CONVENIO)
);

/*==============================================================*/
/* Table: TAB_TIPOS_ESTADOS                                     */
/*==============================================================*/
create table TAB_TIPOS_ESTADOS
(
   ID_TIPO_ESTADO       int not null auto_increment,
   ESTADO               varchar(20) not null,
   primary key (ID_TIPO_ESTADO)
);

/*==============================================================*/
/* Table: TAB_TIPOS_INSTITUCION                                 */
/*==============================================================*/
create table TAB_TIPOS_INSTITUCION
(
   ID_TIPO_INSTITUCION  int not null auto_increment,
   INSTITUCION          varchar(20) not null,
   primary key (ID_TIPO_INSTITUCION)
);

/*==============================================================*/
/* Table: TAB_TIPOS_MODALIDADES                                 */
/*==============================================================*/
create table TAB_TIPOS_MODALIDADES
(
   ID_TIPO_MODALIDAD    int not null auto_increment,
   MODALIDAD            varchar(20) not null,
   primary key (ID_TIPO_MODALIDAD)
);

/*==============================================================*/
/* Table: TAB_TIPOS_PRACTICAS                                   */
/*==============================================================*/
create table TAB_TIPOS_PRACTICAS
(
   ID_TIPO_PRACTICA     int not null auto_increment,
   PRACTICA             varchar(250) not null,
   primary key (ID_TIPO_PRACTICA)
);

/*==============================================================*/
/* Table: TAB_TIPOS_ROLES                                       */
/*==============================================================*/
create table TAB_TIPOS_ROLES
(
   ID_TIPOS_ROLES       int not null auto_increment,
   ROL                  varchar(20) not null,
   primary key (ID_TIPOS_ROLES)
);

/*==============================================================*/
/* Table: TAB_TIPOS_CONTRATO                                     */
/*==============================================================*/
create table TAB_TIPOS_CONTRATO
(
   ID_TIPO_CONTRATO     int not null auto_increment,
   TIPO_CONTRATO        varchar(100) not null,
   primary key (ID_TIPO_CONTRATO)
);

/*==============================================================*/
/* Table: TAB_USUARIOS                                          */
/*==============================================================*/
create table TAB_USUARIOS
(
   ID_USUARIO           int not null auto_increment,
   ID_DATO_PERSONA      int,
   USUARIO              varchar(20) not null,
   CONTRASENA           varchar(60) not null,
   ESTADO               char(1) not null,
   primary key (ID_USUARIO)
);

/*==============================================================*/
/* Table: TAB_RECUPERACION_CONTRASENA                            */
/*==============================================================*/
create table TAB_RECUPERACION_CONTRASENA
(
   ID_RECUPERACION      int unsigned not null auto_increment,
   ID_USUARIO           int null,
   TOKEN                varchar(64) null,
   EXPIRA_EN            datetime null,
   USADO                tinyint(1) default 0 null,
   CREADO_EN            datetime null,
   primary key (ID_RECUPERACION),
   key TOKEN (TOKEN),
   key EXPIRA_EN (EXPIRA_EN)
);

/*==============================================================*/
/* Table: TAB_PRACTICAS_PREPROFESIONALES                        */
/*==============================================================*/
create table TAB_PRACTICAS_PREPROFESIONALES
(
   ID_PRACTICA_PREPROFESIONAL int not null auto_increment,
   ID_PERIODO_ACADEMICO       int,
   ID_ASIGNACION_PRACTICA     int,
   ID_ESTUDIANTE             int,
   ID_DOCENTE_TUTOR          int,
   ID_INSTITUCION_CONVENIO   int,
   AREA_ESPECIALIZACION      varchar(200),
   PROYECTO_ESPECIFICO       text,
   HORAS_PRACTICAS           int,
   FECHA_INICIO              date,
   FECHA_FIN                 date,
   ESTADO_PRACTICA           varchar(50) comment 'Denormalizado (UI legado); fuente de verdad: ID_ESTADO_PREPROFESIONAL',
   ID_ESTADO_PREPROFESIONAL  int,
   EVALUACION_FINAL          decimal(3,2),
   OBSERVACIONES             text,
   primary key (ID_PRACTICA_PREPROFESIONAL),
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO),
   key IDX_DOCENTE_TUTOR (ID_DOCENTE_TUTOR)
);

/*==============================================================*/
/* Table: TAB_SERVICIO_COMUNITARIO                              */
/*==============================================================*/
create table TAB_SERVICIO_COMUNITARIO
(
   ID_SERVICIO_COMUNITARIO   int not null auto_increment,
   ID_PERIODO_ACADEMICO      int,
   ID_ASIGNACION_PRACTICA    int,
   ID_ESTUDIANTE             int,
   ID_DOCENTE_TUTOR          int,
   ID_INSTITUCION_CONVENIO   int,
   PROYECTO_SOCIAL           varchar(200),
   COMUNIDAD_BENEFICIADA     text,
   HORAS_SERVICIO            int,
   FECHA_INICIO              date,
   FECHA_FIN                 date,
   ESTADO_SERVICIO           varchar(50) comment 'Denormalizado (UI legado); fuente de verdad: ID_ESTADO_SERVICIO',
   ID_ESTADO_SERVICIO        int,
   IMPACTO_SOCIAL            text,
   OBSERVACIONES             text,
   primary key (ID_SERVICIO_COMUNITARIO),
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO),
   key IDX_DOCENTE_TUTOR (ID_DOCENTE_TUTOR)
);

/*==============================================================*/
/* Table: TAB_ESTADOS_REVISIONES                                */
/*==============================================================*/
create table TAB_ESTADOS_REVISIONES
(
   ID_ESTADO_REVISION int not null auto_increment,
   ESTADO varchar(50) not null,
   DESCRIPCION text,
   COLOR varchar(20) DEFAULT '#6c757d',
   ORDEN int DEFAULT 1,
   ACTIVO boolean DEFAULT true,
   FECHA_CREACION timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_ESTADO_REVISION),
   UNIQUE KEY UK_ESTADO (ESTADO),
   KEY IDX_ORDEN (ORDEN),
   KEY IDX_ACTIVO (ACTIVO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (Mejorada) */
/*==============================================================*/
create table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES
(
   ID_DOCUMENTO_PREPROFESIONAL int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL  int,
   ID_TIPO_DOCUMENTO           int,
   ID_ESTADO_REVISION          int DEFAULT 1,
   NOMBRE_ARCHIVO              varchar(255) NOT NULL,
   NOMBRE_ORIGINAL             varchar(255),
   TIPO_ARCHIVO                varchar(100),
   TAMANO_ARCHIVO              bigint,
   RUTA_ARCHIVO                varchar(500),
   FECHA_SUBIDA                timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_REVISION              timestamp NULL,
   ID_REVISOR                  int,
   OBSERVACIONES               text,
   OBSERVACIONES_REVISOR       text,
   VERSION                     int DEFAULT 1,
   ACTIVO                      boolean DEFAULT true,
   FECHA_CREACION              timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION         timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_DOCUMENTO_PREPROFESIONAL),
   KEY IDX_PRACTICA (ID_PRACTICA_PREPROFESIONAL),
   KEY IDX_TIPO_DOCUMENTO (ID_TIPO_DOCUMENTO),
   KEY IDX_ESTADO_REVISION (ID_ESTADO_REVISION),
   KEY IDX_REVISOR (ID_REVISOR),
   KEY IDX_FECHA_SUBIDA (FECHA_SUBIDA),
   KEY IDX_ACTIVO (ACTIVO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_DOCUMENTOS_SERVICIO_COMUNITARIO (Mejorada)       */
/*==============================================================*/
create table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO
(
   ID_DOCUMENTO_SERVICIO       int not null auto_increment,
   ID_SERVICIO_COMUNITARIO     int,
   ID_TIPO_DOCUMENTO           int,
   ID_ESTADO_REVISION          int DEFAULT 1,
   NOMBRE_ARCHIVO              varchar(255) NOT NULL,
   NOMBRE_ORIGINAL             varchar(255),
   TIPO_ARCHIVO                varchar(100),
   TAMANO_ARCHIVO              bigint,
   RUTA_ARCHIVO                varchar(500),
   FECHA_SUBIDA                timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_REVISION              timestamp NULL,
   ID_REVISOR                  int,
   OBSERVACIONES               text,
   OBSERVACIONES_REVISOR       text,
   VERSION                     int DEFAULT 1,
   ACTIVO                      boolean DEFAULT true,
   FECHA_CREACION              timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION         timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_DOCUMENTO_SERVICIO),
   KEY IDX_SERVICIO (ID_SERVICIO_COMUNITARIO),
   KEY IDX_TIPO_DOCUMENTO (ID_TIPO_DOCUMENTO),
   KEY IDX_ESTADO_REVISION (ID_ESTADO_REVISION),
   KEY IDX_REVISOR (ID_REVISOR),
   KEY IDX_FECHA_SUBIDA (FECHA_SUBIDA),
   KEY IDX_ACTIVO (ACTIVO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES (Mejorada)     */
/*==============================================================*/
create table TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES
(
   ID_TIPO_DOCUMENTO_PREPROFESIONAL int not null auto_increment,
   CODIGO                           varchar(50) NOT NULL,
   NOMBRE                           varchar(150) NOT NULL,
   DESCRIPCION                      text,
   ORDEN                            int DEFAULT 1,
   OBLIGATORIO                      boolean DEFAULT true,
   ACTIVO                           boolean DEFAULT true,
   FECHA_CREACION                   timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION              timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_TIPO_DOCUMENTO_PREPROFESIONAL),
   UNIQUE KEY UK_CODIGO (CODIGO),
   KEY IDX_ORDEN (ORDEN),
   KEY IDX_ACTIVO (ACTIVO),
   KEY IDX_OBLIGATORIO (OBLIGATORIO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO             */
/*==============================================================*/
create table TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO
(
   ID_TIPO_DOCUMENTO_SERVICIO       int(11) not null auto_increment,
   CODIGO                           varchar(10) not null,
   NOMBRE                           varchar(255) not null,
   DESCRIPCION                      text,
   ORDEN                            int(11) DEFAULT 1,
   OBLIGATORIO                      tinyint(1) DEFAULT 1,
   ACTIVO                           tinyint(1) DEFAULT 1,
   FECHA_CREACION                   datetime DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION              datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_TIPO_DOCUMENTO_SERVICIO),
   UNIQUE KEY `CODIGO` (`CODIGO`),
   KEY `IDX_ORDEN` (`ORDEN`),
   KEY `IDX_ACTIVO` (`ACTIVO`),
   KEY `IDX_OBLIGATORIO` (`OBLIGATORIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_ESTADOS_PRACTICAS_PREPROFESIONALES                */
/*==============================================================*/
create table TAB_ESTADOS_PRACTICAS_PREPROFESIONALES
(
   ID_ESTADO_PREPROFESIONAL int not null auto_increment,
   ESTADO                   varchar(50),
   DESCRIPCION              text,
   COLOR                    varchar(20),
   primary key (ID_ESTADO_PREPROFESIONAL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_ESTADOS_SERVICIO_COMUNITARIO                       */
/*==============================================================*/
create table TAB_ESTADOS_SERVICIO_COMUNITARIO
(
   ID_ESTADO_SERVICIO       int not null auto_increment,
   ESTADO                   varchar(50),
   DESCRIPCION              text,
   COLOR                    varchar(20),
   primary key (ID_ESTADO_SERVICIO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES            */
/*==============================================================*/
create table TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES
(
   ID_SEGUIMIENTO_PREPROFESIONAL int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL    int,
   HORAS_CUMPLIDAS               int not null,
   ACTIVIDADES_REALIZADAS        text not null,
   COMPETENCIAS_DESARROLLADAS    text,
   OBSERVACIONES                  text not null,
   ARCHIVO_REPORTE               varchar(255) not null,
   FECHA_REPORTE                 timestamp default current_timestamp,
   primary key (ID_SEGUIMIENTO_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO                   */
/*==============================================================*/
create table TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO
(
   ID_SEGUIMIENTO_SERVICIO       int not null auto_increment,
   ID_SERVICIO_COMUNITARIO      int,
   HORAS_CUMPLIDAS               int not null,
   ACTIVIDADES_REALIZADAS        text not null,
   BENEFICIARIOS_ATENDIDOS       text,
   OBSERVACIONES                  text not null,
   ARCHIVO_REPORTE               varchar(255) not null,
   FECHA_REPORTE                 timestamp default current_timestamp,
   primary key (ID_SEGUIMIENTO_SERVICIO)
);

/*==============================================================*/
/* Table: TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES            */
/*==============================================================*/
create table TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES
(
   ID_EVALUACION_PREPROFESIONAL int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL   int,
   ID_EVALUADOR                 int,
   TIPO_EVALUACION              varchar(100),
   CRITERIO_1                   decimal(3,2),
   CRITERIO_2                   decimal(3,2),
   CRITERIO_3                   decimal(3,2),
   CRITERIO_4                   decimal(3,2),
   CRITERIO_5                   decimal(3,2),
   NOTA_FINAL                   decimal(3,2),
   COMENTARIOS                  text,
   FECHA_EVALUACION             timestamp default current_timestamp,
   primary key (ID_EVALUACION_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_EVALUACIONES_SERVICIO_COMUNITARIO                   */
/*==============================================================*/
create table TAB_EVALUACIONES_SERVICIO_COMUNITARIO
(
   ID_EVALUACION_SERVICIO       int not null auto_increment,
   ID_SERVICIO_COMUNITARIO     int,
   ID_EVALUADOR                 int,
   TIPO_EVALUACION              varchar(100),
   CRITERIO_1                   decimal(3,2),
   CRITERIO_2                   decimal(3,2),
   CRITERIO_3                   decimal(3,2),
   CRITERIO_4                   decimal(3,2),
   CRITERIO_5                   decimal(3,2),
   NOTA_FINAL                   decimal(3,2),
   COMENTARIOS                  text,
   FECHA_EVALUACION             timestamp default current_timestamp,
   primary key (ID_EVALUACION_SERVICIO)
);

/*==============================================================*/
/* Table: TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES             */
/*==============================================================*/
create table TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES
(
   ID_ASISTENCIA_PREPROFESIONAL int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL   int,
   FECHA_ASISTENCIA             date,
   HORA_ENTRADA                 time not null,
   HORA_SALIDA                  time not null,
   ACTIVIDADES_DIA              text not null,
   COMPETENCIAS_DESARROLLADAS   text,
   FECHA_REGISTRO               timestamp not null,
   OBSERVACIONES                text not null,
   primary key (ID_ASISTENCIA_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_ASISTENCIAS_SERVICIO_COMUNITARIO                   */
/*==============================================================*/
create table TAB_ASISTENCIAS_SERVICIO_COMUNITARIO
(
   ID_ASISTENCIA_SERVICIO       int not null auto_increment,
   ID_SERVICIO_COMUNITARIO     int,
   FECHA_ASISTENCIA             date,
   HORA_ENTRADA                 time not null,
   HORA_SALIDA                  time not null,
   ACTIVIDADES_DIA              text not null,
   BENEFICIARIOS_ATENDIDOS      text,
   FECHA_REGISTRO               timestamp not null,
   OBSERVACIONES                text not null,
   primary key (ID_ASISTENCIA_SERVICIO)
);

/*==============================================================*/
/* Table: TAB_EVALUACIONES_ENLACES                              */
/*==============================================================*/
create table TAB_EVALUACIONES_ENLACES
(
   ID_EVALUACION_ENLACE int not null auto_increment,
   ID_ACTIVIDAD_EDUCACION int,
   ID_USUARIO_CREADOR   int,
   NOMBRE_EVALUACION    varchar(200) not null,
   TIPO_EVALUACION      varchar(50) not null,
   ENLACE_FORMULARIO    varchar(500) not null,
   DESCRIPCION          text,
   FECHA_CREACION       timestamp default current_timestamp,
   FECHA_VENCIMIENTO    date,
   ESTADO               varchar(20) default 'activo',
   NUMERO_RESPUESTAS    int default 0,
   ACTIVO               boolean default true,
   primary key (ID_EVALUACION_ENLACE)
);

/*==============================================================*/
/* Table: TAB_ENTIDADES_RECEPTORAS (Nueva)                     */
/*==============================================================*/
create table TAB_ENTIDADES_RECEPTORAS
(
   ID_ENTIDAD_RECEPTORA int not null auto_increment,
   NOMBRE               varchar(200) not null,
   RUC                  varchar(20),
   DIRECCION            text,
   CIUDAD               varchar(50),
   TELEFONO             varchar(20),
   EMAIL                varchar(100),
   REPRESENTANTE_LEGAL  varchar(150),
   CONTACTO_DIRECTO     varchar(150),
   TELEFONO_CONTACTO    varchar(20),
   EMAIL_CONTACTO       varchar(100),
   TIPO_ENTIDAD         varchar(50) DEFAULT 'Pública',
   ACTIVO               boolean DEFAULT true,
   FECHA_CREACION       timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION  timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_ENTIDAD_RECEPTORA),
   UNIQUE KEY UK_RUC (RUC),
   KEY IDX_NOMBRE (NOMBRE),
   KEY IDX_CIUDAD (CIUDAD),
   KEY IDX_ACTIVO (ACTIVO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_DOCENTES_TUTORES (Nueva)                         */
/*==============================================================*/
create table TAB_DOCENTES_TUTORES
(
   ID_DOCENTE_TUTOR     int not null auto_increment,
   ID_USUARIO           int,
   ID_DATO_PERSONA      int,
   ESPECIALIDAD         varchar(200),
   TITULO_PROFESIONAL   varchar(200),
   AREA_ESPECIALIZACION varchar(200),
   AÑOS_EXPERIENCIA     int DEFAULT 0,
   ACTIVO               boolean DEFAULT true,
   FECHA_CREACION       timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_ACTUALIZACION  timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   primary key (ID_DOCENTE_TUTOR),
   UNIQUE KEY UK_USUARIO (ID_USUARIO),
   KEY IDX_ESPECIALIDAD (ESPECIALIDAD),
   KEY IDX_ACTIVO (ACTIVO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_ASIGNACIONES_DOCENTES_PRACTICAS (Nueva)          */
/*==============================================================*/
create table TAB_ASIGNACIONES_DOCENTES_PRACTICAS
(
   ID_ASIGNACION_DOCENTE int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL int NULL,
   ID_SERVICIO_COMUNITARIO int NULL,
   ID_DOCENTE_TUTOR     int,
   TIPO_ASIGNACION      varchar(50) DEFAULT 'Principal', -- Principal, Suplente, Co-tutor
   FECHA_ASIGNACION     timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_FIN            timestamp NULL,
   OBSERVACIONES        text,
   ACTIVO               boolean DEFAULT true,
   primary key (ID_ASIGNACION_DOCENTE),
   KEY IDX_PRACTICA (ID_PRACTICA_PREPROFESIONAL),
   KEY IDX_SERVICIO (ID_SERVICIO_COMUNITARIO),
   KEY IDX_DOCENTE (ID_DOCENTE_TUTOR),
   KEY IDX_TIPO (TIPO_ASIGNACION),
   KEY IDX_ACTIVO (ACTIVO),
   CHECK ((ID_PRACTICA_PREPROFESIONAL IS NOT NULL AND ID_SERVICIO_COMUNITARIO IS NULL) OR 
          (ID_PRACTICA_PREPROFESIONAL IS NULL AND ID_SERVICIO_COMUNITARIO IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_HISTORIAL_CAMBIOS_DOCUMENTOS (Nueva)             */
/*==============================================================*/
create table TAB_HISTORIAL_CAMBIOS_DOCUMENTOS
(
   ID_HISTORIAL         int not null auto_increment,
   ID_DOCUMENTO_PREPROFESIONAL int NULL,
   ID_DOCUMENTO_SERVICIO int NULL,
   ID_USUARIO           int,
   TIPO_CAMBIO          varchar(50) NOT NULL, -- Estado, Observaciones, Archivo
   VALOR_ANTERIOR       text,
   VALOR_NUEVO          text,
   OBSERVACIONES        text,
   FECHA_CAMBIO         timestamp DEFAULT CURRENT_TIMESTAMP,
   IP_USUARIO           varchar(45),
   USER_AGENT           text,
   primary key (ID_HISTORIAL),
   KEY IDX_DOCUMENTO_PRACTICAS (ID_DOCUMENTO_PREPROFESIONAL),
   KEY IDX_DOCUMENTO_SERVICIO (ID_DOCUMENTO_SERVICIO),
   KEY IDX_USUARIO (ID_USUARIO),
   KEY IDX_TIPO_CAMBIO (TIPO_CAMBIO),
   KEY IDX_FECHA_CAMBIO (FECHA_CAMBIO),
   CHECK ((ID_DOCUMENTO_PREPROFESIONAL IS NOT NULL AND ID_DOCUMENTO_SERVICIO IS NULL) OR 
          (ID_DOCUMENTO_PREPROFESIONAL IS NULL AND ID_DOCUMENTO_SERVICIO IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Table: TAB_NOTIFICACIONES_DOCUMENTOS (Nueva)                */
/*==============================================================*/
create table TAB_NOTIFICACIONES_DOCUMENTOS
(
   ID_NOTIFICACION      int not null auto_increment,
   ID_DOCUMENTO_PREPROFESIONAL int NULL,
   ID_DOCUMENTO_SERVICIO int NULL,
   ID_USUARIO_DESTINATARIO int,
   TIPO_NOTIFICACION    varchar(50) NOT NULL, -- Nuevo, Revisado, Aprobado, Rechazado, Requiere Corrección
   TITULO               varchar(200) NOT NULL,
   MENSAJE              text NOT NULL,
   LEIDA                boolean DEFAULT false,
   FECHA_NOTIFICACION   timestamp DEFAULT CURRENT_TIMESTAMP,
   FECHA_LECTURA        timestamp NULL,
   ACTIVO               boolean DEFAULT true,
   primary key (ID_NOTIFICACION),
   KEY IDX_DOCUMENTO_PRACTICAS (ID_DOCUMENTO_PREPROFESIONAL),
   KEY IDX_DOCUMENTO_SERVICIO (ID_DOCUMENTO_SERVICIO),
   KEY IDX_USUARIO (ID_USUARIO_DESTINATARIO),
   KEY IDX_TIPO (TIPO_NOTIFICACION),
   KEY IDX_LEIDA (LEIDA),
   KEY IDX_ACTIVO (ACTIVO),
   CHECK ((ID_DOCUMENTO_PREPROFESIONAL IS NOT NULL AND ID_DOCUMENTO_SERVICIO IS NULL) OR 
          (ID_DOCUMENTO_PREPROFESIONAL IS NULL AND ID_DOCUMENTO_SERVICIO IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Restricciones de Clave Foránea                               */
/*==============================================================*/

alter table TAB_ACTIVIDADES_EDUCACION add constraint FK_REFERENCE_29 foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_ACTIVIDADES_EDUCACION add constraint FK_REFERENCE_31 foreign key (ID_TIPO_MODALIDAD)
      references TAB_TIPOS_MODALIDADES (ID_TIPO_MODALIDAD) on delete restrict on update restrict;

alter table TAB_ACTIVIDADES_EDUCACION add constraint FK_REFERENCE_32 foreign key (ID_TIPO_ACTIVIDAD)
      references TAB_TIPOS_ACTIVIDADES (ID_TIPO_ACTIVIDAD) on delete restrict on update restrict;

alter table TAB_ACTIVIDADES_EDUCACION add constraint FK_REFERENCE_38 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_REFERENCE_11 foreign key (ID_TIPO_PRACTICA)
      references TAB_TIPOS_PRACTICAS (ID_TIPO_PRACTICA) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_REFERENCE_22 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_REFERENCE_48 foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_ASIGNACIONES_ESTADO_PREPROFESIONAL foreign key (ID_ESTADO_PRACTICAS)
      references TAB_ESTADOS_PRACTICAS_PREPROFESIONALES (ID_ESTADO_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_ACTIVIDADES_EDUCACION add constraint FK_ACTIVIDADES_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_ASIGNACIONES_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

alter table TAB_DETALLES_CONVENIOS add constraint FK_REFERENCE_34 foreign key (ID_TIPO_CONVENIO)
      references TAB_TIPOS_CONVENIOS (ID_TIPO_CONVENIO) on delete restrict on update restrict;

alter table TAB_DETALLES_CONVENIOS add constraint FK_REFERENCE_35 foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_DETALLES_CONVENIOS add constraint FK_DETALLES_CONVENIOS_CARRERA foreign key (ID_CARRERA)
      references TAB_CARRERAS (ID_CARRERA) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_19 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_24 foreign key (ID_TIPO_CONTRATO)
      references TAB_TIPOS_CONTRATO (ID_TIPO_CONTRATO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_4 foreign key (ID_DEPARTAMENTO)
      references TAB_DEPARTAMENTOS (ID_DEPARTAMENTO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS_INSTRUCTORES add constraint FK_REFERENCE_42 foreign key (ID_EMPLEADO)
      references TAB_EMPLEADOS (ID_EMPLEADO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS_INSTRUCTORES add constraint FK_REFERENCE_43 foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_20 foreign key (ID_TIPO_ESTADO)
      references TAB_TIPOS_ESTADOS (ID_TIPO_ESTADO) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_40 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_45 foreign key (ID_CARRERA)
      references TAB_CARRERAS (ID_CARRERA) on delete restrict on update restrict;

alter table TAB_INSCRIPCIONES_ACTIVIDADES add constraint FK_INSCRIPCIONES_ACTIVIDAD foreign key (ID_ACTIVIDAD_EDUCACION)
      references TAB_ACTIVIDADES_EDUCACION (ID_ACTIVIDAD_EDUCACION) on delete cascade on update cascade;
alter table TAB_INSCRIPCIONES_ACTIVIDADES add constraint FK_INSCRIPCIONES_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete cascade on update cascade;

alter table TAB_EXPORTACIONES add constraint FK_REFERENCE_17 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_INSTITUCIONES_CONVENIOS add constraint FK_REFERENCE_33 foreign key (ID_TIPO_INSTITUCION)
      references TAB_TIPOS_INSTITUCION (ID_TIPO_INSTITUCION) on delete restrict on update restrict;

alter table TAB_INSTITUCIONES_CONVENIOS add constraint FK_INSTITUCION_ENTIDAD_RECEPTORA foreign key (ID_ENTIDAD_RECEPTORA)
      references TAB_ENTIDADES_RECEPTORAS (ID_ENTIDAD_RECEPTORA) on delete restrict on update restrict;

alter table TAB_INSTITUCION_CARRERA add constraint FK_REFERENCE_39 foreign key (ID_CARRERA)
      references TAB_CARRERAS (ID_CARRERA) on delete restrict on update restrict;

alter table TAB_INSTITUCION_CARRERA add constraint FK_REFERENCE_41 foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_INSTRUCTORES add constraint FK_REFERENCE_25 foreign key (ID_TIPO_INSTRUCTOR)
      references TAB_TIPOS_INSTRUCTORES (ID_TIPO_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_INSTRUCTORES add constraint FK_REFERENCE_26 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_ROLES add constraint FK_REFERENCE_7 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_ROLES add constraint FK_REFERENCE_8 foreign key (ID_TIPOS_ROLES)
      references TAB_TIPOS_ROLES (ID_TIPOS_ROLES) on delete restrict on update restrict;

alter table TAB_USUARIOS add constraint FK_REFERENCE_12 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_RECUPERACION_CONTRASENA add constraint FK_RECUPERACION_USUARIO foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete cascade on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_ASIGNACION foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_ESTADO_PREPROFESIONAL foreign key (ID_ESTADO_PREPROFESIONAL)
      references TAB_ESTADOS_PRACTICAS_PREPROFESIONALES (ID_ESTADO_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_DOCENTE_TUTOR foreign key (ID_DOCENTE_TUTOR)
      references TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ASIGNACION foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_ESTADO_SERVICIO_COMUNITARIO foreign key (ID_ESTADO_SERVICIO)
      references TAB_ESTADOS_SERVICIO_COMUNITARIO (ID_ESTADO_SERVICIO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_DOCENTE_TUTOR foreign key (ID_DOCENTE_TUTOR)
      references TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_PRACTICA foreign key (ID_PRACTICA_PREPROFESIONAL)
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_TIPO foreign key (ID_TIPO_DOCUMENTO)
      references TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES (ID_TIPO_DOCUMENTO_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_ESTADO foreign key (ID_ESTADO_REVISION)
      references TAB_ESTADOS_REVISIONES (ID_ESTADO_REVISION) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_REVISOR foreign key (ID_REVISOR)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_SERVICIO foreign key (ID_SERVICIO_COMUNITARIO)
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_TIPO foreign key (ID_TIPO_DOCUMENTO)
      references TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO (ID_TIPO_DOCUMENTO_SERVICIO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_ESTADO 
      foreign key (ID_ESTADO_REVISION) 
      references TAB_ESTADOS_REVISIONES (ID_ESTADO_REVISION) 
      on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_REVISOR 
      foreign key (ID_REVISOR) 
      references TAB_USUARIOS (ID_USUARIO) 
      on delete restrict on update restrict;

alter table TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES add constraint FK_SEGUIMIENTO_PREPROFESIONALES_PRACTICA foreign key (ID_PRACTICA_PREPROFESIONAL)
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO add constraint FK_SEGUIMIENTO_SERVICIO_SERVICIO foreign key (ID_SERVICIO_COMUNITARIO)
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES add constraint FK_EVALUACIONES_PREPROFESIONALES_PRACTICA foreign key (ID_PRACTICA_PREPROFESIONAL)
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES add constraint FK_EVALUACIONES_PREPROFESIONALES_EVALUADOR foreign key (ID_EVALUADOR)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_SERVICIO_COMUNITARIO add constraint FK_EVALUACIONES_SERVICIO_SERVICIO foreign key (ID_SERVICIO_COMUNITARIO)
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_SERVICIO_COMUNITARIO add constraint FK_EVALUACIONES_SERVICIO_EVALUADOR foreign key (ID_EVALUADOR)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES add constraint FK_ASISTENCIAS_PREPROFESIONALES_PRACTICA foreign key (ID_PRACTICA_PREPROFESIONAL)
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_ASISTENCIAS_SERVICIO_COMUNITARIO add constraint FK_ASISTENCIAS_SERVICIO_SERVICIO foreign key (ID_SERVICIO_COMUNITARIO)
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_ENLACES add constraint FK_EVALUACIONES_ENLACES_ACTIVIDAD foreign key (ID_ACTIVIDAD_EDUCACION)
      references TAB_ACTIVIDADES_EDUCACION (ID_ACTIVIDAD_EDUCACION) on delete restrict on update restrict;

alter table TAB_EVALUACIONES_ENLACES add constraint FK_EVALUACIONES_ENLACES_USUARIO foreign key (ID_USUARIO_CREADOR)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

-- Restricciones para las nuevas tablas de documentos (ya definidas anteriormente)

-- Restricciones para TAB_DOCENTES_TUTORES
alter table TAB_DOCENTES_TUTORES add constraint FK_DOCENTES_USUARIO 
      foreign key (ID_USUARIO) 
      references TAB_USUARIOS (ID_USUARIO) 
      on delete restrict on update restrict;

alter table TAB_DOCENTES_TUTORES add constraint FK_DOCENTES_PERSONA 
      foreign key (ID_DATO_PERSONA) 
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) 
      on delete restrict on update restrict;

-- Restricciones para TAB_ASIGNACIONES_DOCENTES_PRACTICAS
alter table TAB_ASIGNACIONES_DOCENTES_PRACTICAS add constraint FK_ASIGNACIONES_PRACTICA 
      foreign key (ID_PRACTICA_PREPROFESIONAL) 
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) 
      on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_DOCENTES_PRACTICAS add constraint FK_ASIGNACIONES_SERVICIO 
      foreign key (ID_SERVICIO_COMUNITARIO) 
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) 
      on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_DOCENTES_PRACTICAS add constraint FK_ASIGNACIONES_DOCENTE 
      foreign key (ID_DOCENTE_TUTOR) 
      references TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR) 
      on delete restrict on update restrict;

-- Restricciones para TAB_HISTORIAL_CAMBIOS_DOCUMENTOS
alter table TAB_HISTORIAL_CAMBIOS_DOCUMENTOS add constraint FK_HISTORIAL_DOCUMENTO_PRACTICAS 
      foreign key (ID_DOCUMENTO_PREPROFESIONAL) 
      references TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (ID_DOCUMENTO_PREPROFESIONAL) 
      on delete restrict on update restrict;

alter table TAB_HISTORIAL_CAMBIOS_DOCUMENTOS add constraint FK_HISTORIAL_DOCUMENTO_SERVICIO 
      foreign key (ID_DOCUMENTO_SERVICIO) 
      references TAB_DOCUMENTOS_SERVICIO_COMUNITARIO (ID_DOCUMENTO_SERVICIO) 
      on delete restrict on update restrict;

alter table TAB_HISTORIAL_CAMBIOS_DOCUMENTOS add constraint FK_HISTORIAL_USUARIO 
      foreign key (ID_USUARIO) 
      references TAB_USUARIOS (ID_USUARIO) 
      on delete restrict on update restrict;

-- Restricciones para TAB_NOTIFICACIONES_DOCUMENTOS
alter table TAB_NOTIFICACIONES_DOCUMENTOS add constraint FK_NOTIFICACIONES_DOCUMENTO_PRACTICAS 
      foreign key (ID_DOCUMENTO_PREPROFESIONAL) 
      references TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (ID_DOCUMENTO_PREPROFESIONAL) 
      on delete restrict on update restrict;

alter table TAB_NOTIFICACIONES_DOCUMENTOS add constraint FK_NOTIFICACIONES_DOCUMENTO_SERVICIO 
      foreign key (ID_DOCUMENTO_SERVICIO) 
      references TAB_DOCUMENTOS_SERVICIO_COMUNITARIO (ID_DOCUMENTO_SERVICIO) 
      on delete restrict on update restrict;

alter table TAB_NOTIFICACIONES_DOCUMENTOS add constraint FK_NOTIFICACIONES_USUARIO 
      foreign key (ID_USUARIO_DESTINATARIO) 
      references TAB_USUARIOS (ID_USUARIO) 
      on delete restrict on update restrict;

/*==============================================================*/
/* Table: migrations (CodeIgniter 4 — historial de migraciones) */
/*==============================================================*/
create table migrations
(
   id                   bigint unsigned not null auto_increment,
   version              varchar(255) not null,
   class                varchar(255) not null,
   `group`              varchar(255) not null,
   namespace            varchar(255) not null,
   time                 int not null,
   batch                int unsigned not null,
   primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*==============================================================*/
/* Insertar datos iniciales                                     */
/*==============================================================*/
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-03-03-000001', 'App\\Database\\Migrations\\CreateTabRecuperacionContrasena', 'default', 'App', 1772554056, 1),
(2, '2026-03-10-000001', 'App\\Database\\Migrations\\CreateTabInscripcionesActividades', 'default', 'App', 1773177893, 2),
(3, '2026-03-15-000001', 'App\\Database\\Migrations\\AddLogoTabInstitucionesConvenios', 'default', 'App', 1773629842, 3);

-- Todas las personas (un solo INSERT por tabla)
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
(10, 'Ana Lucía', 'Martínez Vega', '1004567890', '0954321098', 'Cuenca, Ecuador', 'ana.martinez2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-01', 1, '');

INSERT INTO `TAB_USUARIOS` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(1, 1, 'ycampues', '$2y$10$TIMV8h.jkhNV8CLSitL6gOq7fzNIRKyjrJXejA9E49Zf8.LjdZNdC', '1'),
(2, 2, 'ayandun', '$2y$10$i2tATNDRscPHKmaElhHJfejq2SrmjJ1guv2jCOHKoUlm7NT6YvN0a', '1'),
(3, 3, 'paguirre', '$2y$10$nLujePj1IK/t0Te3WEPNq.iGdBBAty3Oo5xJeSIUrV/s6mmVrExsK', '1'),
(4, 4, 'cmendoza', '123', '1'),
(5, 5, 'aruiz', '123', '1'),
(6, 6, 'mgonzalez', '123', '1'),
(7, 7, 'jperez', '123', '1'),
(8, 8, 'mgarcia', '123', '1'),
(9, 9, 'crodriguez', '123', '1'),
(10, 10, 'amartinez', '123', '1');

-- Tokens de recuperación de contraseña (export producción / pruebas)
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
(4, 4, 2),
(5, 5, 2),
(6, 6, 2),
(7, 7, 3),
(8, 8, 3),
(9, 9, 3),
(10, 10, 3);

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
('PPR-004', 'Solicitud Institucional Valorada', 'Documento de solicitud formal dirigido al Sr. Rector, Dr. Mario Montenegro, pidiendo la aprobación institucional para la realización de las prácticas de servicio comunitario.', 4, true),
('PPR-005', 'Certificado de Culminación de Horas', 'Certificado emitido por la entidad receptora "Instituto Tecnológico Superior Ibarra" que acredita que el estudiante ha completado las 60 horas requeridas de prácticas de servicio comunitario.', 5, true),
('PPR-006', 'Hojas de Asistencia', 'Registro físico de la asistencia del estudiante en la entidad receptora. Incluye las firmas y sellos para validar las horas de trabajo.', 6, true),
('PPR-007', 'Ficha de Registro de Actividades', 'Documento detallado en el que el estudiante registra las actividades específicas realizadas durante sus prácticas, incluyendo fechas y descripciones.', 7, true),
('PPR-008', 'Rúbrica de Evaluación de Entidad', 'Formulario de evaluación del desempeño del estudiante, llenado y sellado por la entidad receptora. Valora aspectos como la responsabilidad, la proactividad y la calidad del trabajo.', 8, true),
('PPR-009', 'Ficha de Control y Seguimiento Docente', 'Documento utilizado por el tutor docente para registrar el seguimiento académico del estudiante durante las prácticas. Incluye visitas o revisiones periódicas.', 9, true),
('PPR-010', 'Rúbrica de Evaluación Docente', 'Rúbrica de evaluación llenada y firmada por el tutor docente. Califica el desempeño del estudiante en base a los criterios académicos del programa.', 10, true),
('PPR-011', 'Rúbrica de Evaluación de Resultados', 'Evaluación final realizada por el Departamento de Vinculación con la Sociedad, que valora los resultados y el impacto del proyecto de servicio comunitario en su conjunto.', 11, true),
('PPR-012', 'Evidencia Fotográfica y Digital', 'Material de apoyo visual y digital, como fotos, capturas, videos o impresiones, que documenta y comprueba la realización de las actividades y trabajos del proyecto.', 12, true);

-- Insertar tipos de documentos para Servicio Comunitario
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
('PSC-012', 'Evidencia Fotográfica y Digital', 'Material de apoyo visual y digital, como fotos, capturas, videos o impresiones, que documenta y comprueba la realización de las actividades y trabajos del proyecto.', 12, 1, 1);

-- Insertar estados de revisión
INSERT INTO TAB_ESTADOS_REVISIONES (ID_ESTADO_REVISION, ESTADO, DESCRIPCION, COLOR, ORDEN) VALUES
(1, 'Pendiente', 'Documento pendiente de revisión', '#ffc107', 1),
(2, 'En Revisión', 'Documento siendo revisado por el docente', '#17a2b8', 2),
(3, 'Aprobado', 'Documento aprobado por el revisor', '#28a745', 3),
(4, 'Rechazado', 'Documento rechazado por el revisor', '#dc3545', 4),
(5, 'Requiere Corrección', 'Documento que requiere correcciones', '#fd7e14', 5);

-- Insertar estados para Prácticas Preprofesionales
INSERT INTO `TAB_ESTADOS_PRACTICAS_PREPROFESIONALES` (`ID_ESTADO_PREPROFESIONAL`, `ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
(1, 'Pendiente', 'Práctica pendiente de inicio', '#ffc107'),
(2, 'En Progreso', 'Práctica en desarrollo', '#17a2b8'),
(3, 'Pausada', 'Práctica temporalmente pausada', '#6c757d'),
(4, 'Completada', 'Práctica finalizada exitosamente', '#28a745'),
(5, 'Cancelada', 'Práctica cancelada', '#dc3545'),
(6, 'Evaluada', 'Práctica evaluada y aprobada', '#20c997');

-- Insertar estados para Servicio Comunitario
INSERT INTO `TAB_ESTADOS_SERVICIO_COMUNITARIO` (`ID_ESTADO_SERVICIO`, `ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
(1, 'Pendiente', 'Servicio pendiente de inicio', '#ffc107'),
(2, 'En Progreso', 'Servicio en desarrollo', '#17a2b8'),
(3, 'Pausado', 'Servicio temporalmente pausado', '#6c757d'),
(4, 'Completado', 'Servicio finalizado exitosamente', '#28a745'),
(5, 'Cancelado', 'Servicio cancelado', '#dc3545'),
(6, 'Evaluado', 'Servicio evaluado y aprobado', '#20c997');

-- Insertar tipos de modalidades
INSERT INTO `TAB_TIPOS_MODALIDADES` (`ID_TIPO_MODALIDAD`, `MODALIDAD`) VALUES
(1, 'Presencial'),
(2, 'Virtual'),
(3, 'Híbrida');

-- Insertar tipos de instructor
INSERT INTO `TAB_TIPOS_INSTRUCTORES` (`ID_TIPO_INSTRUCTOR`, `TIPO`) VALUES
(1, 'Interno'),
(2, 'Externo');

-- Insertar instructores
INSERT INTO `TAB_INSTRUCTORES` (`ID_INSTRUCTOR`, `ID_TIPO_INSTRUCTOR`, `ID_DATO_PERSONA`, `ESPECIALIDAD`, `TITULO_PROFESIONAL`) VALUES
(1, 1, 4, 'Desarrollo de Software', 'Ingeniero en Sistemas'),
(2, 1, 5, 'Hardware y Redes', 'Técnico en Electrónica'),
(3, 2, 6, 'Inteligencia Artificial', 'Doctora en Ciencias de la Computación');

-- Insertar datos de períodos académicos
INSERT INTO `TAB_PERIODOS_ACADEMICOS` (`ID_PERIODO_ACADEMICO`, `MES_INICIO`, `AÑO_INICIO`, `MES_FIN`, `AÑO_FIN`) VALUES
(1, 1, 2024, 6, 2024),
(2, 7, 2024, 12, 2024),
(3, 1, 2025, 6, 2025),
(4, 7, 2025, 12, 2025),
(5, 1, 2026, 6, 2026),
(6, 7, 2026, 12, 2026),
(7, 12, 2025, 1, 2026),
(8, 6, 2025, 7, 2025);

-- Insertar actividades educativas de ejemplo
INSERT INTO `TAB_ACTIVIDADES_EDUCACION` (`ID_ACTIVIDAD_EDUCACION`, `ID_INSTRUCTOR`, `ID_TIPO_MODALIDAD`, `ID_TIPO_ACTIVIDAD`, `ID_USUARIO`, `ID_PERIODO_ACADEMICO`, `NOMBRE_ACTIVIDAD`, `DESCRIPCION`, `OBJETIVOS`, `DURACION_HORAS`, `FECHA_INICIO`, `FECHA_FIN`, `LUGAR`, `HORARIO`, `INCLUYE_CERTIFICADO`, `PROGRAMA_DETALLADO`) VALUES
(1, 1, 1, 1, 1, 4, 'Desarrollo Web Full Stack', 'Curso completo de desarrollo web con tecnologías modernas como React, Node.js, MongoDB y más.', 'Formar desarrolladores full stack competentes en tecnologías web modernas', 4, '2025-08-18', '2025-08-19', 'Laboratorio de Programación', 'Lunes a Martess 16:00-18:00', 1, 'Módulo 1: HTML/CSS/JavaScript\r\nMódulo 2: React.js\r\nMódulo 3: Node.js\r\nMódulo 4: Base de datos\r\nMódulo 5: Proyecto final'),
(2, 2, 2, 2, 1, 4, 'Reparación de Equipos de Cómputo', 'Taller práctico de mantenimiento y reparación de hardware de computadoras.', 'Capacitar en técnicas de diagnóstico y reparación de equipos', 40, '2025-10-01', '2025-10-31', 'Plataforma Virtual', 'Sábados 9:00-13:00', 1, 'Diagnóstico de problemas\nReparación de hardware\nMantenimiento preventivo\nInstalación de software'),
(3, 3, 1, 3, 1, 4, 'Inteligencia Artificial y Machine Learning', 'Seminario sobre tendencias actuales en IA y aplicaciones prácticas.', 'Actualizar conocimientos en inteligencia artificial y sus aplicaciones', 16, '2025-12-15', '2025-12-16', 'Auditorio Principal', '8:00-17:00', 1, 'Introducción a la IA\nMachine Learning básico\nDeep Learning\nAplicaciones prácticas\nCasos de estudio'),
(4, 1, 1, 1, 1, 4, 'Programación en Python', 'Curso introductorio de programación usando Python como lenguaje principal.', 'Enseñar los fundamentos de programación usando Python', 80, '2025-08-01', '2025-09-30', 'Laboratorio de Programación', 'Martes y Jueves 18:00-20:00', 1, 'Variables y tipos de datos\nEstructuras de control\nFunciones\nPOO\nLibrerías básicas'),
(5, 2, 2, 2, 1, 4, 'Configuración de Redes', 'Taller de configuración y administración de redes de computadoras.', 'Capacitar en configuración y administración de redes', 32, '2025-11-01', '2025-11-30', 'Laboratorio de Redes', 'Sábados 8:00-12:00', 1, 'Protocolos de red\nConfiguración de routers\nSwitches y VLANs\nSeguridad en redes'),
-- Actividades educativas adicionales (para que el estudiante vea más opciones)
(6, 1, 2, 1, 1, 5, 'React para Producción', 'Curso enfocado en buenas prácticas para aplicaciones React listas para producción.', 'Desarrollar habilidades para crear aplicaciones React mantenibles y eficientes', 24, '2026-04-15', '2026-05-15', 'Plataforma Virtual', 'Lunes a Viernes 18:00-20:00', 1, 'Módulo 1: Estructura de proyecto\r\nMódulo 2: Manejo de estado\r\nMódulo 3: Optimización y performance\r\nMódulo 4: Testing y buenas prácticas\r\nMódulo 5: Proyecto final'),
(7, 2, 1, 2, 1, 5, 'Taller de Ciberseguridad Básica', 'Taller práctico con fundamentos de seguridad y buenas prácticas para usuarios y equipos.', 'Comprender riesgos y aplicar medidas básicas de ciberseguridad', 12, '2026-04-20', '2026-05-05', 'Laboratorio de Redes', 'Martes y Jueves 15:00-17:00', 1, 'Módulo 1: Amenazas comunes\r\nMódulo 2: OWASP básico\r\nMódulo 3: Configuración segura\r\nMódulo 4: Higiene digital'),
(8, 3, 2, 3, 1, 5, 'Conferencia: Tendencias en IA', 'Conferencia de actualización sobre tendencias y casos reales de uso de IA.', 'Conocer tendencias y oportunidades de IA en distintos sectores', 4, '2026-05-10', '2026-05-10', 'Plataforma Virtual', 'Domingo 09:00-13:00', 1, 'Agenda: Tendencias\r\nCasos de uso\r\nSesión Q&A'),
(9, 1, 1, 4, 1, 5, 'Capacitación en Gestión de Proyectos', 'Capacitación para organización de proyectos con metodologías ágiles y planificación.', 'Mejorar habilidades de gestión y planificación de proyectos', 16, '2026-04-22', '2026-05-08', 'Aula Taller', 'Miércoles 18:00-20:00', 1, 'Módulo 1: Fundamentos\r\nMódulo 2: Scrum/Kanban\r\nMódulo 3: Planificación\r\nMódulo 4: Métricas y retrospectivas'),
-- Actividades culminadas (para que aparezca el enlace de evaluación/encuesta)
(10, 1, 1, 1, 1, 4, 'Curso de Bases de Datos', 'Curso de fundamentos y modelado de datos con enfoque práctico.', 'Comprender diseño de bases de datos y consultas', 20, '2026-01-10', '2026-02-10', 'Laboratorio de Programación', 'Lunes a Jueves 16:00-18:00', 1, 'Módulo 1: Modelado\r\nMódulo 2: SQL\r\nMódulo 3: Normalización\r\nMódulo 4: Casos prácticos'),
(11, 2, 2, 2, 1, 4, 'Taller de Soporte Técnico', 'Taller práctico sobre atención de tickets y resolución básica de problemas.', 'Resolver incidencias comunes con enfoque en soporte técnico', 10, '2026-01-20', '2026-02-20', 'Plataforma Virtual', 'Viernes 16:00-18:00', 1, 'Módulo 1: Flujo de soporte\r\nMódulo 2: Diagnóstico\r\nMódulo 3: Herramientas\r\nMódulo 4: Casos'),
(12, 3, 1, 3, 1, 4, 'Conferencia: IA en la práctica', 'Conferencia aplicada sobre cómo implementar soluciones de IA.', 'Entender cómo llevar IA a casos reales', 3, '2026-01-25', '2026-02-05', 'Auditorio Principal', 'Sábado 09:00-12:00', 1, 'Introducción\r\nArquitecturas\r\nImplementación y retos'),
(13, 1, 2, 4, 1, 4, 'Capacitación: Ética y Datos', 'Capacitación para comprender ética, privacidad y manejo de datos.', 'Aplicar principios de ética y privacidad en proyectos de datos', 14, '2025-12-15', '2026-01-25', 'Plataforma Virtual', 'Lunes y Miércoles 19:00-20:30', 1, 'Módulo 1: Marco ético\r\nMódulo 2: Privacidad\r\nMódulo 3: Buenas prácticas\r\nMódulo 4: Casos');

-- Crear registros de estudiantes con carreras
INSERT INTO `TAB_ESTUDIANTES` (`ID_ESTUDIANTE`, `ID_TIPO_ESTADO`, `ID_DATO_PERSONA`, `ID_CARRERA`, `SEMESTRE_ACTUAL`) VALUES
(1, 1, 7, 1, 3),  -- Juan Carlos - Desarrollo de Software - 3er semestre
(2, 1, 8, 2, 2),  -- María Elena - Diseño Gráfico - 2do semestre
(3, 1, 9, 3, 4),  -- Carlos Alberto - Redes y Telecomunicaciones - 4to semestre
(4, 1, 10, 1, 1), -- Ana Lucía - Desarrollo de Software - 1er semestre
(5, 1, 3, 4, 2); -- Pedro Aguirre - Administración - 2do semestre

-- Tutores: mismos usuarios docente/instructor (4–6); docente adicional usuario 2 (ayandun)
INSERT INTO `TAB_DOCENTES_TUTORES` (`ID_USUARIO`, `ID_DATO_PERSONA`, `ESPECIALIDAD`, `TITULO_PROFESIONAL`, `AREA_ESPECIALIZACION`, `AÑOS_EXPERIENCIA`) VALUES
(4, 4, 'Desarrollo de Software', 'Ingeniero en Sistemas', 'Tecnologías de la Información', 10),
(5, 5, 'Hardware y Redes', 'Técnico en Electrónica', 'Infraestructura de TI', 8),
(6, 6, 'Inteligencia Artificial', 'Doctora en Ciencias de la Computación', 'Investigación aplicada', 12);

-- Insertar entidades receptoras 
INSERT INTO `TAB_ENTIDADES_RECEPTORAS` (`ID_ENTIDAD_RECEPTORA`, `NOMBRE`, `RUC`, `DIRECCION`, `CIUDAD`, `TELEFONO`, `EMAIL`, `REPRESENTANTE_LEGAL`, `CONTACTO_DIRECTO`, `TELEFONO_CONTACTO`, `EMAIL_CONTACTO`, `TIPO_ENTIDAD`, `ACTIVO`, `FECHA_CREACION`, `FECHA_ACTUALIZACION`) VALUES
(1, 'Hospital San Vicente de Paúl', '1234567890001', 'Av. 17 de Julio, Ibarra', 'Ibarra', '062-123456', 'contacto@hospitalsanvicente.com', 'Dr. Juan Pérez', 'Lic. María González', '0987654321', 'maria.gonzalez@hospitalsanvicente.com', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(2, 'Banco del Pacífico', '0987654321001', 'Av. Amazonas, Quito', 'Quito', '022-987654', 'info@bancodelpacifico.com', 'Sr. Carlos Mendoza', 'Ing. Ana Ruiz', '0912345678', 'ana.ruiz@bancodelpacifico.com', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(3, 'Fundación Niños del Ecuador', '1122334455001', 'Calle 10 de Agosto, Guayaquil', 'Guayaquil', '042-555666', 'info@ninosdelecuador.org', 'Dra. Sofía Morales', 'Lic. Pedro Aguirre', '0999888777', 'pedro.aguirre@ninosdelecuador.org', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(4, 'Municipio de Ibarra', '1760001230001', 'Plaza de la Independencia, Ibarra', 'Ibarra', '062-123456', 'info@municipioibarra.gob.ec', 'Alcalde Juan Carlos', 'Secretaria General', '0987654321', 'secretaria@municipioibarra.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(5, 'Empresa Tecnológica XYZ', '1234567890002', 'Zona Industrial, Ibarra', 'Ibarra', '062-987654', 'info@tecnologiaxyz.com', 'Ing. Director', 'RRHH', '0912345678', 'rrhh@tecnologiaxyz.com', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(6, 'Casa de la Cultura', '1234567890003', 'Calle Bolívar, Ibarra', 'Ibarra', '062-555777', 'info@casaculturaibarra.gob.ec', 'Lic. Director Cultural', 'Coordinador de Proyectos', '0987654321', 'proyectos@casaculturaibarra.gob.ec', 'Pública', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17'),
(7, 'Fundación Telefónica', '1234567890004', 'Av. 6 de Diciembre, Quito', 'Quito', '022-333444', 'info@fundaciontelefonica.org', 'Director Ejecutivo', 'Coordinador Social', '0912345678', 'social@fundaciontelefonica.org', 'Privada', 1, '2025-09-04 11:59:17', '2025-09-04 11:59:17');

-- Insertar algunas instituciones de convenio
INSERT INTO `TAB_INSTITUCIONES_CONVENIOS` (`ID_INSTITUCION_CONVENIO`, `ID_TIPO_INSTITUCION`, `ID_ENTIDAD_RECEPTORA`, `NOMBRE`, `RUC`, `DIRECCION`, `CIUDAD`, `TELEFONO`, `EMAIL`, `REPRESENTANTE_LEGAL`, `CONTACTO`, `TELEFONO_CONTACTO`, `EMAIL_CONTACTO`, `LOGO`) VALUES
(1, 1, 1, 'Hospital San Vicente de Paúl', '1234567890001', 'Av. 17 de Julio, Ibarra', 'Ibarra', '062-123456', 'contacto@hospitalsanvicente.com', 'Dr. Juan Pérez', 'Lic. María González', '0987654321', 'maria.gonzalez@hospitalsanvicente.com', NULL),
(2, 2, 2, 'Banco del Pacífico', '0987654321001', 'Av. Amazonas, Quito', 'Quito', '022-987654', 'info@bancodelpacifico.com', 'Sr. Carlos Mendoza', 'Ing. Ana Ruiz', '0912345678', 'ana.ruiz@bancodelpacifico.com', NULL),
(3, 1, 3, 'Fundación Niños del Ecuador', '1122334455001', 'Calle 10 de Agosto, Guayaquil', 'Guayaquil', '042-555666', 'info@ninosdelecuador.org', 'Dra. Sofía Morales', 'Lic. Pedro Aguirre', '0999888777', 'pedro.aguirre@ninosdelecuador.org', NULL);

-- Asignaciones de prácticas (todas las filas en un solo INSERT)
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

-- Prácticas preprofesionales (un solo INSERT; columnas alineadas al DDL)
INSERT INTO `TAB_PRACTICAS_PREPROFESIONALES` (`ID_PRACTICA_PREPROFESIONAL`, `ID_PERIODO_ACADEMICO`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_INSTRUCTOR`, `ID_INSTITUCION_CONVENIO`, `ID_ESTADO_PREPROFESIONAL`, `AREA_ESPECIALIZACION`, `PROYECTO_ESPECIFICO`, `HORAS_PRACTICAS`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_PRACTICA`, `EVALUACION_FINAL`, `OBSERVACIONES`) VALUES
(1, 4, 1, 1, 1, 1, 2, 'Desarrollo de Software', 'Sistema de gestión de pacientes y citas médicas', 240, '2025-06-01', '2025-08-30', 'En Progreso', NULL, 'Estudiante con buen desempeño en desarrollo web'),
(2, 4, 2, 2, 2, 2, 2, 'Desarrollo Móvil', 'Aplicación móvil para consulta de saldos y transferencias', 240, '2025-07-01', '2025-09-30', 'En Progreso', NULL, 'Proyecto en desarrollo con tecnologías React Native'),
(3, 4, 4, 4, 1, 1, 2, 'Desarrollo de Software', 'Sistema de gestión de historias clínicas digitales', 240, '2025-09-01', '2025-11-30', 'En Progreso', NULL, 'Estudiante con excelente desempeño en desarrollo web'),
(4, 4, 5, 5, 2, 2, 2, 'Administración', 'Apoyo en gestión de proyectos de inclusión financiera y atención al cliente', 240, '2025-10-01', '2025-12-31', 'En Progreso', NULL, 'Práctica preprofesional en entidad financiera');

-- Servicio comunitario (un solo INSERT)
INSERT INTO `TAB_SERVICIO_COMUNITARIO` (`ID_SERVICIO_COMUNITARIO`, `ID_PERIODO_ACADEMICO`, `ID_ASIGNACION_PRACTICA`, `ID_ESTUDIANTE`, `ID_INSTRUCTOR`, `ID_INSTITUCION_CONVENIO`, `ID_ESTADO_SERVICIO`, `PROYECTO_SOCIAL`, `COMUNIDAD_BENEFICIADA`, `HORAS_SERVICIO`, `FECHA_INICIO`, `FECHA_FIN`, `ESTADO_SERVICIO`, `IMPACTO_SOCIAL`, `OBSERVACIONES`) VALUES
(1, 4, 3, 3, 3, 3, 2, 'Plataforma Educativa Digital', 'Niños y adolescentes en situación vulnerable de Guayaquil', 96, '2025-08-01', '2025-10-30', 'En Progreso', 'Mejora en el acceso a educación digital para 200+ niños', 'Proyecto con alto impacto social positivo'),
(2, 4, 6, 4, 3, 3, 2, 'Plataforma Educativa Digital', 'Niños y adolescentes en situación vulnerable de Guayaquil', 96, '2025-11-01', '2026-01-31', 'En Progreso', 'Mejora en el acceso a educación digital para 200+ niños', 'Proyecto con alto impacto social positivo');

-- Asistencias y seguimientos (un INSERT por tabla)
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

-- Insertar datos de ejemplo para la tabla TAB_EXPORTACIONES
INSERT INTO `TAB_EXPORTACIONES` (`ID_USUARIO`, `FECHA_EXPORTACION`, `DESCRIPCION_EXPORTACION`, `TIPO_EXPORTACION`, `ESTADO_EXPORTACION`, `ARCHIVO_EXPORTACION`, `TAMANO_ARCHIVO`) VALUES
(1, NOW() - INTERVAL 1 DAY, 'Backup completo del sistema - Respaldo diario', 'backup', 'completado', 'backup_diario_20250101_120000.sql', 5242880),
(1, NOW() - INTERVAL 2 DAY, 'Backup incremental - Cambios del día anterior', 'backup', 'completado', 'backup_incremental_20250102_120000.sql', 1048576),
(1, NOW() - INTERVAL 3 DAY, 'Backup de emergencia - Antes de actualización', 'backup', 'completado', 'backup_emergencia_20250103_120000.sql', 8388608),
(2, NOW() - INTERVAL 4 DAY, 'Backup semanal completo', 'backup', 'completado', 'backup_semanal_20250104_120000.sql', 15728640),
(1, NOW() - INTERVAL 5 DAY, 'Backup antes de mantenimiento', 'backup', 'completado', 'backup_mantenimiento_20250105_120000.sql', 6291456);

-- Insertar empleados de ejemplo
INSERT INTO `TAB_EMPLEADOS` (`ID_EMPLEADO`, `ID_DEPARTAMENTO`, `ID_DATO_PERSONA`, `ID_TIPO_CONTRATO`, `CARGO`, `FECHA_INGRESO`) VALUES
(1, 1, 4, 1, 'Coordinador de Vinculación con la Sociedad', '2024-01-15'),
(2, 2, 5, 1, 'Director Académico', '2023-08-01'),
(3, 3, 6, 2, 'Investigador Senior', '2024-03-10');

-- Insertar relación empleados-instructores
INSERT INTO `TAB_EMPLEADOS_INSTRUCTORES` (`ID_EMPLEADO_INSTRUCTOR`, `ID_EMPLEADO`, `ID_INSTRUCTOR`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- Insertar detalles de convenios (ID_CARRERA obligatorio: FK a TAB_CARRERAS; alineado con TAB_INSTITUCION_CARRERA)
INSERT INTO `TAB_DETALLES_CONVENIOS` (`ID_DETALLE_CONVENIO`, `ID_TIPO_CONVENIO`, `ID_INSTITUCION_CONVENIO`, `ID_CARRERA`, `FECHA_INICIO`, `FECHA_FIN`, `DURACION`, `OBJETIVO`, `OBSERVACIONES`, `ARCHIVO_CONVENIO`, `RENOVABLE`, `PLAZAS_DISPONIBLES`) VALUES
(1, 1, 1, 1, '2025-01-01', '2025-12-31', '12 meses', 'Establecer convenio para prácticas preprofesionales en el área de salud', 'Convenio renovable anualmente', 'convenio_hospital_2025.pdf', 1, 0),
(2, 2, 2, 2, '2025-02-01', '2026-01-31', '12 meses', 'Convenio para servicio comunitario en el sector financiero', 'Convenio para proyectos de impacto social', 'convenio_banco_2025.pdf', 1, 0),
(3, 3, 3, 3, '2025-03-01', '2025-12-31', '10 meses', 'Convenio mixto para prácticas y servicio comunitario', 'Convenio integral para múltiples actividades', 'convenio_fundacion_2025.pdf', 1, 0);

-- Insertar relación institución-carrera
INSERT INTO `TAB_INSTITUCION_CARRERA` (`ID_INSTITUCION_CARRERA`, `ID_CARRERA`, `ID_INSTITUCION_CONVENIO`) VALUES
(1, 1, 1), -- Desarrollo de Software - Hospital
(2, 2, 2), -- Diseño Gráfico - Banco
(3, 3, 3), -- Redes y Telecomunicaciones - Fundación
(4, 4, 1), -- Administración - Hospital
(5, 5, 3), -- Atención Integral a Adultos Mayores - Fundación
(6, 6, 2); -- Marketing Digital - Banco

-- Documentos de servicio comunitario (IDs 7–12 y 15–28 en un solo INSERT)
INSERT INTO `TAB_DOCUMENTOS_SERVICIO_COMUNITARIO` (
    `ID_DOCUMENTO_SERVICIO`, 
    `ID_SERVICIO_COMUNITARIO`, 
    `ID_TIPO_DOCUMENTO`, 
    `ID_ESTADO_REVISION`,
    `NOMBRE_ARCHIVO`, 
    `NOMBRE_ORIGINAL`,
    `TIPO_ARCHIVO`, 
    `TAMANO_ARCHIVO`,
    `RUTA_ARCHIVO`,
    `FECHA_SUBIDA`, 
    `FECHA_REVISION`,
    `ID_REVISOR`,
    `OBSERVACIONES`,
    `OBSERVACIONES_REVISOR`,
    `VERSION`
) VALUES
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

-- Insertar evaluaciones enlaces
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

-- Comentarios sobre las tablas
ALTER TABLE `TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO` COMMENT = 'Tipos de documentos requeridos para servicio comunitario';

-- Insertar asignaciones de docentes tutores
INSERT INTO `TAB_ASIGNACIONES_DOCENTES_PRACTICAS` (`ID_ASIGNACION_DOCENTE`, `ID_PRACTICA_PREPROFESIONAL`, `ID_SERVICIO_COMUNITARIO`, `ID_DOCENTE_TUTOR`, `TIPO_ASIGNACION`, `FECHA_ASIGNACION`, `FECHA_FIN`, `OBSERVACIONES`, `ACTIVO`) VALUES
-- Prácticas preprofesionales
(1, 1, NULL, 1, 'Principal', '2025-06-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(2, 2, NULL, 2, 'Principal', '2025-07-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(3, 3, NULL, 1, 'Principal', '2025-09-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(4, 4, NULL, 2, 'Principal', '2025-10-01 05:00:00', NULL, 'Tutor principal asignado', 1),
-- Servicios comunitarios
(5, NULL, 1, 3, 'Principal', '2025-08-01 05:00:00', NULL, 'Tutor principal asignado', 1),
(6, NULL, 2, 3, 'Principal', '2025-11-01 05:00:00', NULL, 'Tutor principal asignado', 1);

-- Documentos de prácticas preprofesionales (un solo INSERT)
INSERT INTO `TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES` (
    `ID_DOCUMENTO_PREPROFESIONAL`, 
    `ID_PRACTICA_PREPROFESIONAL`, 
    `ID_TIPO_DOCUMENTO`, 
    `ID_ESTADO_REVISION`, 
    `NOMBRE_ARCHIVO`, 
    `NOMBRE_ORIGINAL`, 
    `TIPO_ARCHIVO`, 
    `TAMANO_ARCHIVO`, 
    `RUTA_ARCHIVO`, 
    `FECHA_SUBIDA`, 
    `FECHA_REVISION`, 
    `ID_REVISOR`, 
    `OBSERVACIONES`, 
    `OBSERVACIONES_REVISOR`, 
    `VERSION`
) VALUES
(1, 1, 1, 3, 'oficio_asignacion_tutor_001_20250601.pdf', 'Oficio Asignación Tutor - Juan Carlos.pdf', 'application/pdf', 245760, '/uploads/documentos-practicas/', '2025-06-01 10:00:00', '2025-06-01 14:30:00', 4, 'Documento oficial de asignación', 'Documento aprobado correctamente', 1),
(2, 1, 2, 3, 'oficio_entidad_receptora_001_20250602.pdf', 'Oficio Entidad Receptora - Hospital.pdf', 'application/pdf', 189440, '/uploads/documentos-practicas/', '2025-06-02 14:30:00', '2025-06-02 16:45:00', 4, 'Oficio enviado a la entidad receptora', 'Oficio bien redactado y formal', 1),
(3, 1, 3, 3, 'carta_aceptacion_001_20250603.pdf', 'Carta Aceptación - Hospital.pdf', 'application/pdf', 156672, '/uploads/documentos-practicas/', '2025-06-03 09:15:00', '2025-06-03 11:20:00', 4, 'Carta de aceptación de la entidad', 'Carta oficial con sello institucional', 1),
(4, 2, 1, 3, 'oficio_asignacion_tutor_002_20250701.pdf', 'Oficio Asignación Tutor - María Elena.pdf', 'application/pdf', 234880, '/uploads/documentos-practicas/', '2025-07-01 11:00:00', '2025-07-01 13:15:00', 5, 'Documento oficial de asignación', 'Asignación correcta del tutor', 1),
(5, 2, 2, 3, 'oficio_entidad_receptora_002_20250702.pdf', 'Oficio Entidad Receptora - Banco.pdf', 'application/pdf', 178944, '/uploads/documentos-practicas/', '2025-07-02 15:45:00', '2025-07-02 17:20:00', 5, 'Oficio enviado a la entidad receptora', 'Oficio formal y bien estructurado', 1),
(14, 1, 6, 5, 'hojas_asistencia_001_20250830.pdf', 'Hojas de Asistencia - Juan Carlos.pdf', 'application/pdf', 123456, '/uploads/documentos-practicas/', '2025-08-30 16:15:00', '2025-08-30 17:45:00', 4, 'Hojas de asistencia completas', 'Faltan firmas en algunas fechas, corregir y volver a subir', 1),
(15, 2, 7, 5, 'ficha_registro_actividades_002_20250915.pdf', 'Ficha Registro Actividades - María.pdf', 'application/pdf', 98765, '/uploads/documentos-practicas/', '2025-09-15 11:30:00', '2025-09-15 14:20:00', 5, 'Ficha de registro de actividades', 'Descripción de actividades muy general, especificar más detalles', 1),
(16, 1, 8, 4, 'rubrica_evaluacion_entidad_001_20250825.pdf', 'Rúbrica Evaluación Entidad - Juan.pdf', 'application/pdf', 87654, '/uploads/documentos-practicas/', '2025-08-25 15:00:00', '2025-08-25 16:30:00', 4, 'Rúbrica de evaluación de entidad', 'Documento no tiene sello oficial de la entidad, rechazado', 1),
(17, 2, 9, 4, 'ficha_control_seguimiento_002_20250920.pdf', 'Ficha Control Seguimiento - María.pdf', 'application/pdf', 112233, '/uploads/documentos-practicas/', '2025-09-20 10:15:00', '2025-09-20 12:45:00', 5, 'Ficha de control y seguimiento docente', 'Faltan las firmas del tutor docente, documento inválido', 1);

-- Insertar notificaciones de ejemplo
INSERT INTO `TAB_NOTIFICACIONES_DOCUMENTOS` (`ID_DOCUMENTO_PREPROFESIONAL`, `ID_USUARIO_DESTINATARIO`, `TIPO_NOTIFICACION`, `TITULO`, `MENSAJE`, `LEIDA`) VALUES
-- Notificaciones para prácticas preprofesionales
(1, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Asignación Tutor - Juan Carlos.pdf" ha sido aprobado por el revisor.', true),
(2, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Entidad Receptora - Hospital.pdf" ha sido aprobado por el revisor.', true),
(3, 7, 'Aprobado', 'Documento Aprobado', 'El documento "Carta Aceptación - Hospital.pdf" ha sido aprobado por el revisor.', true),
(4, 8, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Asignación Tutor - María Elena.pdf" ha sido aprobado por el revisor.', true),
(5, 8, 'Aprobado', 'Documento Aprobado', 'El documento "Oficio Entidad Receptora - Banco.pdf" ha sido aprobado por el revisor.', true),
(14, 7, 'Requiere Corrección', 'Documento Requiere Corrección', 'El documento "Hojas de Asistencia - Juan Carlos.pdf" requiere correcciones: Faltan firmas en algunas fechas, corregir y volver a subir.', false),
(15, 8, 'Requiere Corrección', 'Documento Requiere Corrección', 'El documento "Ficha Registro Actividades - María.pdf" requiere correcciones: Descripción de actividades muy general, especificar más detalles.', false),
(16, 7, 'Rechazado', 'Documento Rechazado', 'El documento "Rúbrica Evaluación Entidad - Juan.pdf" ha sido rechazado: Documento no tiene sello oficial de la entidad, rechazado.', false),
(17, 8, 'Rechazado', 'Documento Rechazado', 'El documento "Ficha Control Seguimiento - María.pdf" ha sido rechazado: Faltan las firmas del tutor docente, documento inválido.', false);

-- Insertar historial de cambios de ejemplo
INSERT INTO `TAB_HISTORIAL_CAMBIOS_DOCUMENTOS` (`ID_DOCUMENTO_PREPROFESIONAL`, `ID_USUARIO`, `TIPO_CAMBIO`, `VALOR_ANTERIOR`, `VALOR_NUEVO`, `OBSERVACIONES`) VALUES
-- Historial para prácticas preprofesionales
(1, 4, 'Estado', 'Pendiente', 'Aprobado', 'Documento revisado y aprobado correctamente'),
(2, 4, 'Estado', 'Pendiente', 'Aprobado', 'Oficio bien redactado y formal'),
(3, 4, 'Estado', 'Pendiente', 'Aprobado', 'Carta oficial con sello institucional'),
(4, 5, 'Estado', 'Pendiente', 'Aprobado', 'Asignación correcta del tutor'),
(5, 5, 'Estado', 'Pendiente', 'Aprobado', 'Oficio formal y bien estructurado'),
(14, 4, 'Estado', 'Pendiente', 'Requiere Corrección', 'Faltan firmas en algunas fechas, corregir y volver a subir'),
(15, 5, 'Estado', 'Pendiente', 'Requiere Corrección', 'Descripción de actividades muy general, especificar más detalles'),
(16, 4, 'Estado', 'Pendiente', 'Rechazado', 'Documento no tiene sello oficial de la entidad, rechazado'),
(17, 5, 'Estado', 'Pendiente', 'Rechazado', 'Faltan las firmas del tutor docente, documento inválido');

-- ==============================================================
-- VISTAS PARA FACILITAR CONSULTAS
-- ==============================================================

-- Vista para documentos de prácticas preprofesionales
CREATE OR REPLACE VIEW V_DOCUMENTOS_PRACTICAS_COMPLETOS AS
SELECT 
    dp.ID_DOCUMENTO_PREPROFESIONAL,
    dp.ID_PRACTICA_PREPROFESIONAL,
    dp.ID_TIPO_DOCUMENTO,
    dp.ID_ESTADO_REVISION,
    dp.NOMBRE_ARCHIVO,
    dp.NOMBRE_ORIGINAL,
    dp.TIPO_ARCHIVO,
    dp.TAMANO_ARCHIVO,
    dp.RUTA_ARCHIVO,
    dp.FECHA_SUBIDA,
    dp.FECHA_REVISION,
    dp.ID_REVISOR,
    dp.OBSERVACIONES,
    dp.OBSERVACIONES_REVISOR,
    dp.VERSION,
    dp.ACTIVO,
    'PRACTICAS' as TIPO_MODALIDAD,
    
    -- Información del período académico (tabla: solo mes/año inicio-fin; resto derivado)
    pp.ID_PERIODO_ACADEMICO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
    pa.AÑO_INICIO AS AÑO_ACADEMICO,
    pa.MES_INICIO,
    pa.AÑO_INICIO,
    pa.MES_FIN,
    pa.AÑO_FIN,
    STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS PERIODO_FECHA_INICIO,
    LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS PERIODO_FECHA_FIN,
    'N/D' AS TIPO_PERIODO,
    0 AS NUMERO_PERIODO,
    NULL AS PERIODO_ESTADO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
    
    -- Información del tipo de documento
    tdp.CODIGO as TIPO_DOCUMENTO_CODIGO,
    tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
    tdp.DESCRIPCION as TIPO_DOCUMENTO_DESCRIPCION,
    tdp.ORDEN as TIPO_DOCUMENTO_ORDEN,
    tdp.OBLIGATORIO as TIPO_DOCUMENTO_OBLIGATORIO,
    
    -- Información del estado
    er.ESTADO as ESTADO_REVISION,
    er.DESCRIPCION as ESTADO_DESCRIPCION,
    er.COLOR as ESTADO_COLOR,
    
    -- Información del estudiante
    pp.ID_ESTUDIANTE,
    CONCAT(persona.NOMBRE, ' ', persona.APELLIDO) as ESTUDIANTE_NOMBRE,
    persona.NOMBRE as NOMBRE_ESTUDIANTE,
    persona.APELLIDO as APELLIDO_ESTUDIANTE,
    persona.CEDULA as CEDULA_ESTUDIANTE,
    
    -- Información de la entidad receptora
    ic.NOMBRE as ENTIDAD_RECEPTORA,
    ic.RUC as ENTIDAD_RUC,
    ic.CIUDAD as ENTIDAD_CIUDAD,
    
    -- Información del revisor
    CONCAT(rev_persona.NOMBRE, ' ', rev_persona.APELLIDO) as REVISOR_NOMBRE,
    rev_persona.NOMBRE as NOMBRE_REVISOR,
    rev_persona.APELLIDO as APELLIDO_REVISOR,
    
    -- Información del docente tutor
    CONCAT(dt_persona.NOMBRE, ' ', dt_persona.APELLIDO) as DOCENTE_TUTOR,
    dt_persona.NOMBRE as NOMBRE_DOCENTE,
    dt_persona.APELLIDO as APELLIDO_DOCENTE,
    dt.ESPECIALIDAD as DOCENTE_ESPECIALIDAD,
    dt.TITULO_PROFESIONAL as DOCENTE_TITULO

FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp
LEFT JOIN TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp ON dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL
LEFT JOIN TAB_ESTADOS_REVISIONES er ON dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
LEFT JOIN TAB_PRACTICAS_PREPROFESIONALES pp ON dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL
LEFT JOIN TAB_PERIODOS_ACADEMICOS pa ON pp.ID_PERIODO_ACADEMICO = pa.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ESTUDIANTES e ON pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE
LEFT JOIN TAB_DATOS_PERSONAS persona ON e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA
LEFT JOIN TAB_INSTITUCIONES_CONVENIOS ic ON pp.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO
LEFT JOIN TAB_USUARIOS rev_usuario ON dp.ID_REVISOR = rev_usuario.ID_USUARIO
LEFT JOIN TAB_DATOS_PERSONAS rev_persona ON rev_usuario.ID_DATO_PERSONA = rev_persona.ID_DATO_PERSONA
LEFT JOIN TAB_ASIGNACIONES_DOCENTES_PRACTICAS adp ON pp.ID_PRACTICA_PREPROFESIONAL = adp.ID_PRACTICA_PREPROFESIONAL AND adp.TIPO_ASIGNACION = 'Principal'
LEFT JOIN TAB_DOCENTES_TUTORES dt ON adp.ID_DOCENTE_TUTOR = dt.ID_DOCENTE_TUTOR
LEFT JOIN TAB_USUARIOS dt_usuario ON dt.ID_USUARIO = dt_usuario.ID_USUARIO
LEFT JOIN TAB_DATOS_PERSONAS dt_persona ON dt_usuario.ID_DATO_PERSONA = dt_persona.ID_DATO_PERSONA
WHERE dp.ACTIVO = true;

-- Vista para documentos de servicio comunitario
CREATE OR REPLACE VIEW V_DOCUMENTOS_SERVICIO_COMPLETOS AS
SELECT 
    ds.ID_DOCUMENTO_SERVICIO as ID_DOCUMENTO_PREPROFESIONAL,
    ds.ID_SERVICIO_COMUNITARIO as ID_PRACTICA_PREPROFESIONAL,
    ds.ID_TIPO_DOCUMENTO,
    ds.ID_ESTADO_REVISION,
    ds.NOMBRE_ARCHIVO,
    ds.NOMBRE_ORIGINAL,
    ds.TIPO_ARCHIVO,
    ds.TAMANO_ARCHIVO,
    ds.RUTA_ARCHIVO,
    ds.FECHA_SUBIDA,
    ds.FECHA_REVISION,
    ds.ID_REVISOR,
    ds.OBSERVACIONES,
    ds.OBSERVACIONES_REVISOR,
    ds.VERSION,
    ds.ACTIVO,
    'SERVICIO_COMUNITARIO' as TIPO_MODALIDAD,
    
    -- Información del período académico (tabla: solo mes/año inicio-fin; resto derivado)
    sc.ID_PERIODO_ACADEMICO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
    pa.AÑO_INICIO AS AÑO_ACADEMICO,
    pa.MES_INICIO,
    pa.AÑO_INICIO,
    pa.MES_FIN,
    pa.AÑO_FIN,
    STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS PERIODO_FECHA_INICIO,
    LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS PERIODO_FECHA_FIN,
    'N/D' AS TIPO_PERIODO,
    0 AS NUMERO_PERIODO,
    NULL AS PERIODO_ESTADO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
    
    -- Información del tipo de documento
    tds.CODIGO as TIPO_DOCUMENTO_CODIGO,
    tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
    tds.DESCRIPCION as TIPO_DOCUMENTO_DESCRIPCION,
    tds.ORDEN as TIPO_DOCUMENTO_ORDEN,
    tds.OBLIGATORIO as TIPO_DOCUMENTO_OBLIGATORIO,
    
    -- Información del estado
    er.ESTADO as ESTADO_REVISION,
    er.DESCRIPCION as ESTADO_DESCRIPCION,
    er.COLOR as ESTADO_COLOR,
    
    -- Información del estudiante
    sc.ID_ESTUDIANTE,
    CONCAT(persona.NOMBRE, ' ', persona.APELLIDO) as ESTUDIANTE_NOMBRE,
    persona.NOMBRE as NOMBRE_ESTUDIANTE,
    persona.APELLIDO as APELLIDO_ESTUDIANTE,
    persona.CEDULA as CEDULA_ESTUDIANTE,
    
    -- Información de la entidad receptora
    ic.NOMBRE as ENTIDAD_RECEPTORA,
    ic.RUC as ENTIDAD_RUC,
    ic.CIUDAD as ENTIDAD_CIUDAD,
    
    -- Información del revisor
    CONCAT(rev_persona.NOMBRE, ' ', rev_persona.APELLIDO) as REVISOR_NOMBRE,
    rev_persona.NOMBRE as NOMBRE_REVISOR,
    rev_persona.APELLIDO as APELLIDO_REVISOR,
    
    -- Información del docente tutor
    CONCAT(dt_persona.NOMBRE, ' ', dt_persona.APELLIDO) as DOCENTE_TUTOR,
    dt_persona.NOMBRE as NOMBRE_DOCENTE,
    dt_persona.APELLIDO as APELLIDO_DOCENTE,
    dt.ESPECIALIDAD as DOCENTE_ESPECIALIDAD,
    dt.TITULO_PROFESIONAL as DOCENTE_TITULO

FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds
LEFT JOIN TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds ON ds.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO
LEFT JOIN TAB_ESTADOS_REVISIONES er ON ds.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
LEFT JOIN TAB_SERVICIO_COMUNITARIO sc ON ds.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO
LEFT JOIN TAB_PERIODOS_ACADEMICOS pa ON sc.ID_PERIODO_ACADEMICO = pa.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ESTUDIANTES e ON sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE
LEFT JOIN TAB_DATOS_PERSONAS persona ON e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA
LEFT JOIN TAB_INSTITUCIONES_CONVENIOS ic ON sc.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO
LEFT JOIN TAB_USUARIOS rev_usuario ON ds.ID_REVISOR = rev_usuario.ID_USUARIO
LEFT JOIN TAB_DATOS_PERSONAS rev_persona ON rev_usuario.ID_DATO_PERSONA = rev_persona.ID_DATO_PERSONA
LEFT JOIN TAB_ASIGNACIONES_DOCENTES_PRACTICAS adp ON sc.ID_SERVICIO_COMUNITARIO = adp.ID_SERVICIO_COMUNITARIO AND adp.TIPO_ASIGNACION = 'Principal'
LEFT JOIN TAB_DOCENTES_TUTORES dt ON adp.ID_DOCENTE_TUTOR = dt.ID_DOCENTE_TUTOR
LEFT JOIN TAB_USUARIOS dt_usuario ON dt.ID_USUARIO = dt_usuario.ID_USUARIO
LEFT JOIN TAB_DATOS_PERSONAS dt_persona ON dt_usuario.ID_DATO_PERSONA = dt_persona.ID_DATO_PERSONA
WHERE ds.ACTIVO = true;

-- Vista unificada para ambos tipos
CREATE OR REPLACE VIEW V_DOCUMENTOS_UNIFICADOS AS
SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS
UNION ALL
SELECT * FROM V_DOCUMENTOS_SERVICIO_COMPLETOS
ORDER BY FECHA_SUBIDA DESC;

-- ==============================================================
-- VISTAS ESPECIALIZADAS PARA PERÍODOS ACADÉMICOS
-- ==============================================================

-- Vista para obtener el período académico actual (el de inicio más reciente por calendario)
CREATE OR REPLACE VIEW V_PERIODO_ACADEMICO_ACTUAL AS
SELECT 
    ID_PERIODO_ACADEMICO,
    CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS NOMBRE_PERIODO,
    AÑO_INICIO AS AÑO_ACADEMICO,
    MES_INICIO,
    AÑO_INICIO,
    MES_FIN,
    AÑO_FIN,
    STR_TO_DATE(CONCAT(AÑO_INICIO, '-', LPAD(MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS FECHA_INICIO,
    LAST_DAY(STR_TO_DATE(CONCAT(AÑO_FIN, '-', LPAD(MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS FECHA_FIN,
    'N/D' AS TIPO_PERIODO,
    0 AS NUMERO_PERIODO,
    'N/D' AS ESTADO,
    NULL AS DESCRIPCION,
    CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS PERIODO_COMPLETO
FROM TAB_PERIODOS_ACADEMICOS
ORDER BY AÑO_INICIO DESC, MES_INICIO DESC
LIMIT 1;

-- Vista para obtener todos los períodos académicos ordenados
CREATE OR REPLACE VIEW V_PERIODOS_ACADEMICOS_ORDENADOS AS
SELECT 
    ID_PERIODO_ACADEMICO,
    CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS NOMBRE_PERIODO,
    AÑO_INICIO AS AÑO_ACADEMICO,
    MES_INICIO,
    AÑO_INICIO,
    MES_FIN,
    AÑO_FIN,
    STR_TO_DATE(CONCAT(AÑO_INICIO, '-', LPAD(MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS FECHA_INICIO,
    LAST_DAY(STR_TO_DATE(CONCAT(AÑO_FIN, '-', LPAD(MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS FECHA_FIN,
    'N/D' AS TIPO_PERIODO,
    0 AS NUMERO_PERIODO,
    'N/D' AS ESTADO,
    NULL AS DESCRIPCION,
    CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS PERIODO_COMPLETO,
    0 AS ORDEN_ESTADO
FROM TAB_PERIODOS_ACADEMICOS
ORDER BY AÑO_INICIO DESC, MES_INICIO DESC;

-- Vista para estadísticas por período académico
CREATE OR REPLACE VIEW V_ESTADISTICAS_PERIODOS AS
SELECT 
    pa.ID_PERIODO_ACADEMICO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
    pa.AÑO_INICIO AS AÑO_ACADEMICO,
    'N/D' AS TIPO_PERIODO,
    NULL AS ESTADO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
    
    -- Estadísticas de prácticas preprofesionales
    COUNT(DISTINCT pp.ID_PRACTICA_PREPROFESIONAL) as TOTAL_PRACTICAS_PREPROFESIONALES,
    COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'En Progreso' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_EN_PROGRESO,
    COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'Completada' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_COMPLETADAS,
    COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'Cancelada' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_CANCELADAS,
    
    -- Estadísticas de servicio comunitario
    COUNT(DISTINCT sc.ID_SERVICIO_COMUNITARIO) as TOTAL_SERVICIOS_COMUNITARIOS,
    COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'En Progreso' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_EN_PROGRESO,
    COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'Completado' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_COMPLETADOS,
    COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'Cancelado' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_CANCELADOS,
    
    -- Estadísticas de actividades educativas
    COUNT(DISTINCT ae.ID_ACTIVIDAD_EDUCACION) as TOTAL_ACTIVIDADES_EDUCATIVAS,
    
    -- Estadísticas de estudiantes
    COUNT(DISTINCT e.ID_ESTUDIANTE) as TOTAL_ESTUDIANTES_PARTICIPANTES,
    
    -- Estadísticas de documentos
    COUNT(DISTINCT dp.ID_DOCUMENTO_PREPROFESIONAL) as TOTAL_DOCUMENTOS_PRACTICAS,
    COUNT(DISTINCT ds.ID_DOCUMENTO_SERVICIO) as TOTAL_DOCUMENTOS_SERVICIO,
    
    -- Rango del período (derivado de mes/año; útil para reportes)
    STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS FECHA_INICIO,
    LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS FECHA_FIN,
    DATEDIFF(
        LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')),
        STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d')
    ) + 1 AS DIAS_DURACION

FROM TAB_PERIODOS_ACADEMICOS pa
LEFT JOIN TAB_PRACTICAS_PREPROFESIONALES pp ON pa.ID_PERIODO_ACADEMICO = pp.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_SERVICIO_COMUNITARIO sc ON pa.ID_PERIODO_ACADEMICO = sc.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ACTIVIDADES_EDUCACION ae ON pa.ID_PERIODO_ACADEMICO = ae.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ESTUDIANTES e ON (pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE OR sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE)
LEFT JOIN TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp ON pp.ID_PRACTICA_PREPROFESIONAL = dp.ID_PRACTICA_PREPROFESIONAL
LEFT JOIN TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds ON sc.ID_SERVICIO_COMUNITARIO = ds.ID_SERVICIO_COMUNITARIO
GROUP BY pa.ID_PERIODO_ACADEMICO, pa.MES_INICIO, pa.AÑO_INICIO, pa.MES_FIN, pa.AÑO_FIN
ORDER BY pa.AÑO_INICIO DESC, pa.MES_INICIO DESC;

-- Vista para documentos filtrados por período académico
-- vd.* ya incluye columnas de período; no duplicar con pa.* (error #1060 en MySQL/MariaDB).
CREATE OR REPLACE VIEW V_DOCUMENTOS_POR_PERIODO AS
SELECT vd.*
FROM V_DOCUMENTOS_UNIFICADOS vd
INNER JOIN TAB_PERIODOS_ACADEMICOS pa ON vd.ID_PERIODO_ACADEMICO = pa.ID_PERIODO_ACADEMICO
ORDER BY pa.AÑO_INICIO DESC, pa.MES_INICIO DESC, vd.FECHA_SUBIDA DESC;

-- Vista para prácticas por período académico
CREATE OR REPLACE VIEW V_PRACTICAS_POR_PERIODO AS
SELECT 
    pp.ID_PRACTICA_PREPROFESIONAL,
    pp.ID_ASIGNACION_PRACTICA,
    pp.ID_ESTUDIANTE,
    pp.ID_INSTRUCTOR,
    pp.ID_INSTITUCION_CONVENIO,
    pp.AREA_ESPECIALIZACION,
    pp.PROYECTO_ESPECIFICO,
    pp.HORAS_PRACTICAS,
    pp.FECHA_INICIO,
    pp.FECHA_FIN,
    pp.ESTADO_PRACTICA,
    pp.EVALUACION_FINAL,
    pp.OBSERVACIONES,
    pp.ID_PERIODO_ACADEMICO,
    
    -- Información del período académico (derivada de mes/año)
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
    pa.AÑO_INICIO AS AÑO_ACADEMICO,
    'N/D' AS TIPO_PERIODO,
    NULL AS PERIODO_ESTADO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
    
    -- Información del estudiante
    CONCAT(persona.NOMBRE, ' ', persona.APELLIDO) as ESTUDIANTE_NOMBRE,
    persona.CEDULA as CEDULA_ESTUDIANTE,
    c.NOMBRE as CARRERA_NOMBRE,
    e.SEMESTRE_ACTUAL,
    
    -- Información de la entidad receptora
    ic.NOMBRE as ENTIDAD_RECEPTORA,
    ic.CIUDAD as ENTIDAD_CIUDAD,
    
    -- Información del instructor
    CONCAT(inst_persona.NOMBRE, ' ', inst_persona.APELLIDO) as INSTRUCTOR_NOMBRE,
    inst.ESPECIALIDAD as INSTRUCTOR_ESPECIALIDAD

FROM TAB_PRACTICAS_PREPROFESIONALES pp
LEFT JOIN TAB_PERIODOS_ACADEMICOS pa ON pp.ID_PERIODO_ACADEMICO = pa.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ESTUDIANTES e ON pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE
LEFT JOIN TAB_DATOS_PERSONAS persona ON e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA
LEFT JOIN TAB_CARRERAS c ON e.ID_CARRERA = c.ID_CARRERA
LEFT JOIN TAB_INSTITUCIONES_CONVENIOS ic ON pp.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO
LEFT JOIN TAB_INSTRUCTORES inst ON pp.ID_INSTRUCTOR = inst.ID_INSTRUCTOR
LEFT JOIN TAB_DATOS_PERSONAS inst_persona ON inst.ID_DATO_PERSONA = inst_persona.ID_DATO_PERSONA
ORDER BY pa.AÑO_INICIO DESC, pa.MES_INICIO DESC, pp.FECHA_INICIO DESC;

-- Vista para servicios comunitarios por período académico
CREATE OR REPLACE VIEW V_SERVICIOS_POR_PERIODO AS
SELECT 
    sc.ID_SERVICIO_COMUNITARIO,
    sc.ID_ASIGNACION_PRACTICA,
    sc.ID_ESTUDIANTE,
    sc.ID_INSTRUCTOR,
    sc.ID_INSTITUCION_CONVENIO,
    sc.PROYECTO_SOCIAL,
    sc.COMUNIDAD_BENEFICIADA,
    sc.HORAS_SERVICIO,
    sc.FECHA_INICIO,
    sc.FECHA_FIN,
    sc.ESTADO_SERVICIO,
    sc.IMPACTO_SOCIAL,
    sc.OBSERVACIONES,
    sc.ID_PERIODO_ACADEMICO,
    
    -- Información del período académico (derivada de mes/año)
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
    pa.AÑO_INICIO AS AÑO_ACADEMICO,
    'N/D' AS TIPO_PERIODO,
    NULL AS PERIODO_ESTADO,
    CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
    
    -- Información del estudiante
    CONCAT(persona.NOMBRE, ' ', persona.APELLIDO) as ESTUDIANTE_NOMBRE,
    persona.CEDULA as CEDULA_ESTUDIANTE,
    c.NOMBRE as CARRERA_NOMBRE,
    e.SEMESTRE_ACTUAL,
    
    -- Información de la entidad receptora
    ic.NOMBRE as ENTIDAD_RECEPTORA,
    ic.CIUDAD as ENTIDAD_CIUDAD,
    
    -- Información del instructor
    CONCAT(inst_persona.NOMBRE, ' ', inst_persona.APELLIDO) as INSTRUCTOR_NOMBRE,
    inst.ESPECIALIDAD as INSTRUCTOR_ESPECIALIDAD

FROM TAB_SERVICIO_COMUNITARIO sc
LEFT JOIN TAB_PERIODOS_ACADEMICOS pa ON sc.ID_PERIODO_ACADEMICO = pa.ID_PERIODO_ACADEMICO
LEFT JOIN TAB_ESTUDIANTES e ON sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE
LEFT JOIN TAB_DATOS_PERSONAS persona ON e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA
LEFT JOIN TAB_CARRERAS c ON e.ID_CARRERA = c.ID_CARRERA
LEFT JOIN TAB_INSTITUCIONES_CONVENIOS ic ON sc.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO
LEFT JOIN TAB_INSTRUCTORES inst ON sc.ID_INSTRUCTOR = inst.ID_INSTRUCTOR
LEFT JOIN TAB_DATOS_PERSONAS inst_persona ON inst.ID_DATO_PERSONA = inst_persona.ID_DATO_PERSONA
ORDER BY pa.AÑO_INICIO DESC, pa.MES_INICIO DESC, sc.FECHA_INICIO DESC;

-- ==============================================================
-- PROCEDIMIENTOS ALMACENADOS PARA PERÍODOS ACADÉMICOS
-- ==============================================================

-- Procedimiento para obtener el período académico actual
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SP_OBTENER_PERIODO_ACTUAL()
BEGIN
    SELECT 
        ID_PERIODO_ACADEMICO,
        CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS NOMBRE_PERIODO,
        AÑO_INICIO AS AÑO_ACADEMICO,
        MES_INICIO,
        AÑO_INICIO,
        MES_FIN,
        AÑO_FIN,
        STR_TO_DATE(CONCAT(AÑO_INICIO, '-', LPAD(MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS FECHA_INICIO,
        LAST_DAY(STR_TO_DATE(CONCAT(AÑO_FIN, '-', LPAD(MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS FECHA_FIN,
        'N/D' AS TIPO_PERIODO,
        0 AS NUMERO_PERIODO,
        'N/D' AS ESTADO,
        NULL AS DESCRIPCION,
        CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS PERIODO_COMPLETO
    FROM TAB_PERIODOS_ACADEMICOS
    ORDER BY AÑO_INICIO DESC, MES_INICIO DESC
    LIMIT 1;
END //
DELIMITER ;

-- Procedimiento para obtener estadísticas de un período específico
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SP_ESTADISTICAS_PERIODO(
    IN p_id_periodo INT
)
BEGIN
    SELECT 
        pa.ID_PERIODO_ACADEMICO,
        CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS NOMBRE_PERIODO,
        pa.AÑO_INICIO AS AÑO_ACADEMICO,
        'N/D' AS TIPO_PERIODO,
        NULL AS ESTADO,
        CONCAT(LPAD(pa.MES_INICIO, 2, '0'), '/', pa.AÑO_INICIO, ' - ', LPAD(pa.MES_FIN, 2, '0'), '/', pa.AÑO_FIN) AS PERIODO_COMPLETO,
        
        -- Estadísticas de prácticas preprofesionales
        COUNT(DISTINCT pp.ID_PRACTICA_PREPROFESIONAL) as TOTAL_PRACTICAS_PREPROFESIONALES,
        COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'En Progreso' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_EN_PROGRESO,
        COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'Completada' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_COMPLETADAS,
        COUNT(DISTINCT CASE WHEN pp.ESTADO_PRACTICA = 'Cancelada' THEN pp.ID_PRACTICA_PREPROFESIONAL END) as PRACTICAS_CANCELADAS,
        
        -- Estadísticas de servicio comunitario
        COUNT(DISTINCT sc.ID_SERVICIO_COMUNITARIO) as TOTAL_SERVICIOS_COMUNITARIOS,
        COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'En Progreso' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_EN_PROGRESO,
        COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'Completado' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_COMPLETADOS,
        COUNT(DISTINCT CASE WHEN sc.ESTADO_SERVICIO = 'Cancelado' THEN sc.ID_SERVICIO_COMUNITARIO END) as SERVICIOS_CANCELADOS,
        
        -- Estadísticas de actividades educativas
        COUNT(DISTINCT ae.ID_ACTIVIDAD_EDUCACION) as TOTAL_ACTIVIDADES_EDUCATIVAS,
        
        -- Estadísticas de estudiantes
        COUNT(DISTINCT e.ID_ESTUDIANTE) as TOTAL_ESTUDIANTES_PARTICIPANTES,
        
        -- Estadísticas de documentos
        COUNT(DISTINCT dp.ID_DOCUMENTO_PREPROFESIONAL) as TOTAL_DOCUMENTOS_PRACTICAS,
        COUNT(DISTINCT ds.ID_DOCUMENTO_SERVICIO) as TOTAL_DOCUMENTOS_SERVICIO,
        
        STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d') AS FECHA_INICIO,
        LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')) AS FECHA_FIN,
        DATEDIFF(
            LAST_DAY(STR_TO_DATE(CONCAT(pa.AÑO_FIN, '-', LPAD(pa.MES_FIN, 2, '0'), '-01'), '%Y-%m-%d')),
            STR_TO_DATE(CONCAT(pa.AÑO_INICIO, '-', LPAD(pa.MES_INICIO, 2, '0'), '-01'), '%Y-%m-%d')
        ) + 1 AS DIAS_DURACION

    FROM TAB_PERIODOS_ACADEMICOS pa
    LEFT JOIN TAB_PRACTICAS_PREPROFESIONALES pp ON pa.ID_PERIODO_ACADEMICO = pp.ID_PERIODO_ACADEMICO
    LEFT JOIN TAB_SERVICIO_COMUNITARIO sc ON pa.ID_PERIODO_ACADEMICO = sc.ID_PERIODO_ACADEMICO
    LEFT JOIN TAB_ACTIVIDADES_EDUCACION ae ON pa.ID_PERIODO_ACADEMICO = ae.ID_PERIODO_ACADEMICO
    LEFT JOIN TAB_ESTUDIANTES e ON (pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE OR sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE)
    LEFT JOIN TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp ON pp.ID_PRACTICA_PREPROFESIONAL = dp.ID_PRACTICA_PREPROFESIONAL
    LEFT JOIN TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds ON sc.ID_SERVICIO_COMUNITARIO = ds.ID_SERVICIO_COMUNITARIO
    WHERE pa.ID_PERIODO_ACADEMICO = p_id_periodo
    GROUP BY pa.ID_PERIODO_ACADEMICO, pa.MES_INICIO, pa.AÑO_INICIO, pa.MES_FIN, pa.AÑO_FIN;
END //
DELIMITER ;

-- Procedimiento legado: la tabla ya no tiene ESTADO; no modifica filas.
DROP PROCEDURE IF EXISTS SP_CAMBIAR_ESTADO_PERIODO;
DELIMITER //
CREATE PROCEDURE SP_CAMBIAR_ESTADO_PERIODO(
    IN p_id_periodo INT,
    IN p_nuevo_estado VARCHAR(20)
)
BEGIN
    SELECT 'TAB_PERIODOS_ACADEMICOS solo guarda mes/año inicio-fin; no hay estado que actualizar.' AS RESULTADO,
           p_id_periodo AS ID_PERIODO_SOLICITADO,
           p_nuevo_estado AS ESTADO_IGNORADO;
END //
DELIMITER ;

-- Procedimiento para obtener documentos por período académico
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SP_DOCUMENTOS_POR_PERIODO(
    IN p_id_periodo INT,
    IN p_tipo_modalidad VARCHAR(50)
)
BEGIN
    IF p_tipo_modalidad = 'PRACTICAS' OR p_tipo_modalidad IS NULL THEN
        SELECT * FROM V_DOCUMENTOS_PRACTICAS_COMPLETOS 
        WHERE ID_PERIODO_ACADEMICO = p_id_periodo
        ORDER BY FECHA_SUBIDA DESC;
    END IF;
    
    IF p_tipo_modalidad = 'SERVICIO_COMUNITARIO' OR p_tipo_modalidad IS NULL THEN
        SELECT * FROM V_DOCUMENTOS_SERVICIO_COMPLETOS 
        WHERE ID_PERIODO_ACADEMICO = p_id_periodo
        ORDER BY FECHA_SUBIDA DESC;
    END IF;
    
    IF p_tipo_modalidad IS NULL THEN
        SELECT * FROM V_DOCUMENTOS_UNIFICADOS 
        WHERE ID_PERIODO_ACADEMICO = p_id_periodo
        ORDER BY FECHA_SUBIDA DESC;
    END IF;
END //
DELIMITER ;

-- ==============================================================
-- PROCEDIMIENTOS ALMACENADOS ORIGINALES
-- ==============================================================

-- Procedimiento para cambiar estado de documento preprofesional
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SP_CAMBIAR_ESTADO_DOCUMENTO_PRACTICAS(
    IN p_id_documento INT,
    IN p_nuevo_estado INT,
    IN p_id_revisor INT,
    IN p_observaciones TEXT
)
BEGIN
    DECLARE v_estado_anterior INT;
    DECLARE v_estado_anterior_texto VARCHAR(50);
    DECLARE v_nuevo_estado_texto VARCHAR(50);
    
    -- Obtener estado anterior
    SELECT ID_ESTADO_REVISION, (SELECT ESTADO FROM TAB_ESTADOS_REVISIONES WHERE ID_ESTADO_REVISION = dp.ID_ESTADO_REVISION)
    INTO v_estado_anterior, v_estado_anterior_texto
    FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp
    WHERE ID_DOCUMENTO_PREPROFESIONAL = p_id_documento;
    
    -- Obtener nombre del nuevo estado
    SELECT ESTADO INTO v_nuevo_estado_texto
    FROM TAB_ESTADOS_REVISIONES
    WHERE ID_ESTADO_REVISION = p_nuevo_estado;
    
    -- Actualizar documento
    UPDATE TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES 
    SET 
        ID_ESTADO_REVISION = p_nuevo_estado,
        ID_REVISOR = p_id_revisor,
        OBSERVACIONES_REVISOR = p_observaciones,
        FECHA_REVISION = CURRENT_TIMESTAMP
    WHERE ID_DOCUMENTO_PREPROFESIONAL = p_id_documento;
    
    -- Registrar en historial
    INSERT INTO TAB_HISTORIAL_CAMBIOS_DOCUMENTOS (
        ID_DOCUMENTO_PREPROFESIONAL,
        ID_USUARIO,
        TIPO_CAMBIO,
        VALOR_ANTERIOR,
        VALOR_NUEVO,
        OBSERVACIONES
    ) VALUES (
        p_id_documento,
        p_id_revisor,
        'Estado',
        v_estado_anterior_texto,
        v_nuevo_estado_texto,
        p_observaciones
    );
    
END //
DELIMITER ;

-- Procedimiento para cambiar estado de documento servicio comunitario
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SP_CAMBIAR_ESTADO_DOCUMENTO_SERVICIO(
    IN p_id_documento INT,
    IN p_nuevo_estado INT,
    IN p_id_revisor INT,
    IN p_observaciones TEXT
)
BEGIN
    DECLARE v_estado_anterior INT;
    DECLARE v_estado_anterior_texto VARCHAR(50);
    DECLARE v_nuevo_estado_texto VARCHAR(50);
    
    -- Obtener estado anterior
    SELECT ID_ESTADO_REVISION, (SELECT ESTADO FROM TAB_ESTADOS_REVISIONES WHERE ID_ESTADO_REVISION = ds.ID_ESTADO_REVISION)
    INTO v_estado_anterior, v_estado_anterior_texto
    FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds
    WHERE ID_DOCUMENTO_SERVICIO = p_id_documento;
    
    -- Obtener nombre del nuevo estado
    SELECT ESTADO INTO v_nuevo_estado_texto
    FROM TAB_ESTADOS_REVISIONES
    WHERE ID_ESTADO_REVISION = p_nuevo_estado;
    
    -- Actualizar documento
    UPDATE TAB_DOCUMENTOS_SERVICIO_COMUNITARIO 
    SET 
        ID_ESTADO_REVISION = p_nuevo_estado,
        ID_REVISOR = p_id_revisor,
        OBSERVACIONES_REVISOR = p_observaciones,
        FECHA_REVISION = CURRENT_TIMESTAMP
    WHERE ID_DOCUMENTO_SERVICIO = p_id_documento;
    
    -- Registrar en historial
    INSERT INTO TAB_HISTORIAL_CAMBIOS_DOCUMENTOS (
        ID_DOCUMENTO_SERVICIO,
        ID_USUARIO,
        TIPO_CAMBIO,
        VALOR_ANTERIOR,
        VALOR_NUEVO,
        OBSERVACIONES
    ) VALUES (
        p_id_documento,
        p_id_revisor,
        'Estado',
        v_estado_anterior_texto,
        v_nuevo_estado_texto,
        p_observaciones
    );
    
END //
DELIMITER ;

-- ==============================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ==============================================================

-- Índices para mejorar rendimiento de consultas
CREATE INDEX IDX_DOCUMENTOS_FECHA_ESTADO ON TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (FECHA_SUBIDA, ID_ESTADO_REVISION);
CREATE INDEX IDX_DOCUMENTOS_TIPO_ESTADO ON TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (ID_TIPO_DOCUMENTO, ID_ESTADO_REVISION);
CREATE INDEX IDX_DOCUMENTOS_PRACTICA_ESTADO ON TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL, ID_ESTADO_REVISION);

-- ==============================================================
-- COMENTARIOS DE TABLAS
-- ==============================================================

ALTER TABLE TAB_ESTADOS_REVISIONES COMMENT = 'Estados de revisión de documentos de prácticas preprofesionales';
ALTER TABLE TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES COMMENT = 'Tipos de documentos requeridos para prácticas preprofesionales';
ALTER TABLE TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES COMMENT = 'Documentos subidos por estudiantes para prácticas preprofesionales';
ALTER TABLE TAB_ENTIDADES_RECEPTORAS COMMENT = 'Entidades receptoras donde se realizan las prácticas preprofesionales';
ALTER TABLE TAB_DOCENTES_TUTORES COMMENT = 'Docentes tutores asignados a las prácticas preprofesionales';
ALTER TABLE TAB_ASIGNACIONES_DOCENTES_PRACTICAS COMMENT = 'Asignaciones de docentes tutores a prácticas específicas';
ALTER TABLE TAB_HISTORIAL_CAMBIOS_DOCUMENTOS COMMENT = 'Historial de cambios realizados en los documentos';
ALTER TABLE TAB_NOTIFICACIONES_DOCUMENTOS COMMENT = 'Notificaciones relacionadas con documentos de prácticas';

-- TAB_PERIODOS_ACADEMICOS, columnas ID_PERIODO_ACADEMICO y FK asociadas: definidas en el DDL principal (inicio del script).

-- ==============================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- ==============================================================

SELECT 'Datos de ejemplo insertados exitosamente para ambas modalidades' as RESULTADO;

-- Verificar documentos por estado - Prácticas Preprofesionales
SELECT 
    'PRACTICAS_PREPROFESIONALES' as MODALIDAD,
    er.ESTADO,
    COUNT(*) as CANTIDAD
FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp
LEFT JOIN TAB_ESTADOS_REVISIONES er ON dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
GROUP BY er.ESTADO, er.ORDEN
ORDER BY er.ORDEN;

-- Verificar documentos por estado - Servicio Comunitario
SELECT 
    'SERVICIO_COMUNITARIO' as MODALIDAD,
    er.ESTADO,
    COUNT(*) as CANTIDAD
FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ds
LEFT JOIN TAB_ESTADOS_REVISIONES er ON ds.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
GROUP BY ds.ID_ESTADO_REVISION, er.ESTADO, er.ORDEN
ORDER BY er.ORDEN;

-- Verificar notificaciones por tipo
SELECT 
    TIPO_NOTIFICACION,
    COUNT(*) as CANTIDAD,
    SUM(CASE WHEN LEIDA = true THEN 1 ELSE 0 END) as LEIDAS,
    SUM(CASE WHEN LEIDA = false THEN 1 ELSE 0 END) as NO_LEIDAS
FROM TAB_NOTIFICACIONES_DOCUMENTOS
GROUP BY TIPO_NOTIFICACION;

-- Verificar historial de cambios
SELECT 
    TIPO_CAMBIO,
    COUNT(*) as CANTIDAD
FROM TAB_HISTORIAL_CAMBIOS_DOCUMENTOS
GROUP BY TIPO_CAMBIO;

-- Verificar asignaciones de docentes
SELECT 
    CASE 
        WHEN ID_PRACTICA_PREPROFESIONAL IS NOT NULL THEN 'PRACTICAS_PREPROFESIONALES'
        ELSE 'SERVICIO_COMUNITARIO'
    END as MODALIDAD,
    COUNT(*) as CANTIDAD
FROM TAB_ASIGNACIONES_DOCENTES_PRACTICAS
GROUP BY MODALIDAD;

-- Verificar que las tablas se crearon correctamente
SELECT 'Sistema de documentos de prácticas y servicio comunitario actualizado exitosamente' as RESULTADO;
SELECT COUNT(*) as TIPOS_DOCUMENTOS_SERVICIO FROM TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO;
SELECT COUNT(*) as ESTADOS_REVISION FROM TAB_ESTADOS_REVISIONES;
SELECT COUNT(*) as TIPOS_DOCUMENTOS FROM TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES;
SELECT COUNT(*) as ENTIDADES_RECEPTORAS FROM TAB_ENTIDADES_RECEPTORAS;
SELECT COUNT(*) as DOCENTES_TUTORES FROM TAB_DOCENTES_TUTORES;
SELECT COUNT(*) as DOCUMENTOS_PRACTICAS FROM TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES;
SELECT COUNT(*) as DOCUMENTOS_SERVICIO FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO;
SELECT COUNT(*) as NOTIFICACIONES FROM TAB_NOTIFICACIONES_DOCUMENTOS;
SELECT COUNT(*) as HISTORIAL_CAMBIOS FROM TAB_HISTORIAL_CAMBIOS_DOCUMENTOS;

-- ==============================================================
-- VERIFICACIÓN DEL SISTEMA DE PERÍODOS ACADÉMICOS
-- ==============================================================

SELECT 'Sistema de períodos académicos implementado exitosamente' as RESULTADO;
SELECT COUNT(*) as PERIODOS_ACADEMICOS FROM TAB_PERIODOS_ACADEMICOS;

-- Verificar períodos (solo mes/año en tabla; etiqueta derivada)
SELECT 
    ID_PERIODO_ACADEMICO,
    MES_INICIO,
    AÑO_INICIO,
    MES_FIN,
    AÑO_FIN,
    CONCAT(LPAD(MES_INICIO, 2, '0'), '/', AÑO_INICIO, ' - ', LPAD(MES_FIN, 2, '0'), '/', AÑO_FIN) AS ETIQUETA
FROM TAB_PERIODOS_ACADEMICOS 
ORDER BY AÑO_INICIO DESC, MES_INICIO DESC;

-- Verificar que las vistas se crearon correctamente
SELECT 'Vistas de períodos académicos creadas exitosamente' as RESULTADO;
SELECT COUNT(*) as VISTA_PERIODO_ACTUAL FROM information_schema.views WHERE table_name = 'V_PERIODO_ACADEMICO_ACTUAL';
SELECT COUNT(*) as VISTA_PERIODOS_ORDENADOS FROM information_schema.views WHERE table_name = 'V_PERIODOS_ACADEMICOS_ORDENADOS';
SELECT COUNT(*) as VISTA_ESTADISTICAS_PERIODOS FROM information_schema.views WHERE table_name = 'V_ESTADISTICAS_PERIODOS';
SELECT COUNT(*) as VISTA_DOCUMENTOS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_POR_PERIODO';
SELECT COUNT(*) as VISTA_PRACTICAS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_PRACTICAS_POR_PERIODO';
SELECT COUNT(*) as VISTA_SERVICIOS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_SERVICIOS_POR_PERIODO';

-- Verificar procedimientos almacenados de períodos académicos
SELECT 'Procedimientos almacenados de períodos académicos creados exitosamente' as RESULTADO;
SELECT COUNT(*) as PROC_PERIODO_ACTUAL FROM information_schema.routines WHERE routine_name = 'SP_OBTENER_PERIODO_ACTUAL';
SELECT COUNT(*) as PROC_ESTADISTICAS_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_ESTADISTICAS_PERIODO';
SELECT COUNT(*) as PROC_CAMBIAR_ESTADO_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_PERIODO';
SELECT COUNT(*) as PROC_DOCUMENTOS_POR_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_DOCUMENTOS_POR_PERIODO';

-- Consultas de ejemplo (opcional; ejecutar por separado, no en un solo lote con CALL):
-- SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL;
-- CALL SP_ESTADISTICAS_PERIODO(1);   -- sustituir por un ID_PERIODO_ACADEMICO existente
-- SELECT * FROM V_PERIODOS_ACADEMICOS_ORDENADOS LIMIT 5;

-- Verificar vistas creadas
SELECT 'Vistas creadas exitosamente' as RESULTADO;
SELECT COUNT(*) as VISTA_PRACTICAS FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_PRACTICAS_COMPLETOS';
SELECT COUNT(*) as VISTA_SERVICIO FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_SERVICIO_COMPLETOS';
SELECT COUNT(*) as VISTA_UNIFICADA FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_UNIFICADOS';

-- Verificar procedimientos almacenados
SELECT 'Procedimientos almacenados creados exitosamente' as RESULTADO;
SELECT COUNT(*) as PROC_PRACTICAS FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_DOCUMENTO_PRACTICAS';
SELECT COUNT(*) as PROC_SERVICIO FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_DOCUMENTO_SERVICIO';

-- Tabla de notificaciones del sistema

CREATE TABLE IF NOT EXISTS `TAB_NOTIFICACIONES` (
  `ID_NOTIFICACION` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO_DESTINATARIO` int(11) NOT NULL COMMENT 'Usuario que recibe la notificación',
  `ID_USUARIO_REMITENTE` int(11) DEFAULT NULL COMMENT 'Usuario que envía la notificación (puede ser NULL para notificaciones del sistema)',
  `TITULO` varchar(200) NOT NULL COMMENT 'Título de la notificación',
  `MENSAJE` text NOT NULL COMMENT 'Mensaje de la notificación',
  `TIPO_NOTIFICACION` enum('asignacion_practica','tutoria_asignada','recordatorio','general') NOT NULL DEFAULT 'general' COMMENT 'Tipo de notificación',
  `ID_REFERENCIA` int(11) DEFAULT NULL COMMENT 'ID de la entidad relacionada (ej: ID de práctica)',
  `TABLA_REFERENCIA` varchar(100) DEFAULT NULL COMMENT 'Tabla de la entidad relacionada',
  `LEIDA` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la notificación ha sido leída',
  `FECHA_LEIDA` datetime DEFAULT NULL COMMENT 'Fecha y hora cuando fue leída',
  `PRIORIDAD` enum('alta','media','baja') NOT NULL DEFAULT 'media' COMMENT 'Prioridad de la notificación',
  `ACTIVA` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Indica si la notificación está activa (soft delete)',
  `FECHA_CREACION` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
  `FECHA_ACTUALIZACION` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`ID_NOTIFICACION`),
  KEY `idx_usuario_destinatario` (`ID_USUARIO_DESTINATARIO`),
  KEY `idx_usuario_remitente` (`ID_USUARIO_REMITENTE`),
  KEY `idx_tipo_notificacion` (`TIPO_NOTIFICACION`),
  KEY `idx_leida` (`LEIDA`),
  KEY `idx_activa` (`ACTIVA`),
  KEY `idx_fecha_creacion` (`FECHA_CREACION`),
  KEY `idx_prioridad` (`PRIORIDAD`),
  CONSTRAINT `fk_notificaciones_destinatario` FOREIGN KEY (`ID_USUARIO_DESTINATARIO`) REFERENCES `TAB_USUARIOS` (`ID_USUARIO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notificaciones_remitente` FOREIGN KEY (`ID_USUARIO_REMITENTE`) REFERENCES `TAB_USUARIOS` (`ID_USUARIO`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar notificaciones del sistema';

-- Insertar algunos datos de ejemplo (opcional)
-- INSERT INTO `TAB_NOTIFICACIONES` (`ID_USUARIO_DESTINATARIO`, `ID_USUARIO_REMITENTE`, `TITULO`, `MENSAJE`, `TIPO_NOTIFICACION`, `PRIORIDAD`) VALUES
-- (1, 1, 'Bienvenido al Sistema', 'Bienvenido al sistema de gestión de prácticas ITSI. Aquí recibirás notificaciones importantes sobre tus prácticas.', 'general', 'media'),
-- (2, 1, 'Nueva Tutoria Asignada', 'Has sido asignado como tutor de una nueva práctica preprofesional.', 'tutoria_asignada', 'alta');

-- Crear índices adicionales para optimizar consultas frecuentes
CREATE INDEX `idx_notificaciones_usuario_tipo` ON `TAB_NOTIFICACIONES` (`ID_USUARIO_DESTINATARIO`, `TIPO_NOTIFICACION`, `ACTIVA`);
CREATE INDEX `idx_notificaciones_usuario_leida` ON `TAB_NOTIFICACIONES` (`ID_USUARIO_DESTINATARIO`, `LEIDA`, `ACTIVA`);
CREATE INDEX `idx_notificaciones_fecha_tipo` ON `TAB_NOTIFICACIONES` (`FECHA_CREACION`, `TIPO_NOTIFICACION`, `ACTIVA`);
