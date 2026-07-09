/*==============================================================*/
/* ITSI — Esquema (tablas, vistas, procedimientos)               */
/* Motor: MySQL 5.7+ / MariaDB 10.2+ · utf8mb4_unicode_ci      */
/* Uso: importar primero este archivo, luego bddITSI_datos.sql  */
/*                                                              */
/* IMPORTANTE (hosting compartido / InfinityFree / etc.):       */
/*   1. En el panel elige la BD que te asignaron.                */
/*   2. En phpMyAdmin selecciona ESA base (clic a la izquierda).*/
/*   3. Importa este archivo, luego bddITSI_datos.sql.          */
/*   4. NO importes bddITSI_vistas_local.sql (CREATE VIEW #1142)*/
/*                                                              */
/* Local (XAMPP): crea la BD `itsi` a mano o descomenta abajo.  */
/*   Opcional local: después importa bddITSI_vistas_local.sql.  */
/*==============================================================*/

-- Solo en local con permisos de root (descomentar si hace falta):
-- CREATE DATABASE IF NOT EXISTS `itsi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `itsi`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- TAB_PERIODOS_ACADEMICOS: únicamente ID + MES_INICIO, AÑO_INICIO, MES_FIN, AÑO_FIN.
-- Sin nombre, tipo, número, estado, descripción ni auditoría en esta tabla.
-- En local opcional: importar bddITSI_vistas_local.sql (vistas/SP).
-- En hosting compartido: la app usa TAB_PERIODOS_ACADEMICOS (sin vistas).
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
drop table if exists TAB_DOCUMENTOS_HABILITANTES_INSTITUCION;
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
   ID_DOCENTE_TUTOR     int,
   FECHA_INSCRIPCION    date,
   ESTADO               varchar(30) default 'Inscrito',
   primary key (ID_INSCRIPCION),
   unique key UK_INSCRIPCION_ACTIVIDAD_ESTUDIANTE (ID_ACTIVIDAD_EDUCACION, ID_ESTUDIANTE),
   unique key UK_INSCRIPCION_ACTIVIDAD_DOCENTE (ID_ACTIVIDAD_EDUCACION, ID_DOCENTE_TUTOR),
   key IDX_INSCRIPCION_ACTIVIDAD (ID_ACTIVIDAD_EDUCACION),
   key IDX_INSCRIPCION_ESTUDIANTE (ID_ESTUDIANTE),
   key IDX_INSCRIPCION_DOCENTE (ID_DOCENTE_TUTOR)
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
/* Table: TAB_DOCUMENTOS_HABILITANTES_INSTITUCION               */
/*==============================================================*/
create table TAB_DOCUMENTOS_HABILITANTES_INSTITUCION
(
   ID_DOCUMENTO_HABILITANTE int not null auto_increment,
   ID_INSTITUCION_CONVENIO int not null,
   NOMBRE_ARCHIVO         varchar(255) not null,
   NOMBRE_ORIGINAL        varchar(255) not null,
   TIPO_ARCHIVO           varchar(100) not null default 'application/pdf',
   TAMANO_BYTES           int unsigned null,
   FECHA_SUBIDA           datetime not null,
   primary key (ID_DOCUMENTO_HABILITANTE),
   key IDX_DOC_HAB_INST (ID_INSTITUCION_CONVENIO)
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
alter table TAB_INSCRIPCIONES_ACTIVIDADES add constraint FK_INSCRIPCIONES_DOCENTE foreign key (ID_DOCENTE_TUTOR)
      references TAB_DOCENTES_TUTORES (ID_DOCENTE_TUTOR) on delete cascade on update cascade;

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

alter table TAB_DOCUMENTOS_HABILITANTES_INSTITUCION add constraint FK_DOC_HAB_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete cascade on update restrict;

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

alter table TAB_DOCENTES_TUTORES add constraint FK_DOCENTES_USUARIO 
      foreign key (ID_USUARIO) 
      references TAB_USUARIOS (ID_USUARIO) 
      on delete restrict on update restrict;

alter table TAB_DOCENTES_TUTORES add constraint FK_DOCENTES_PERSONA 
      foreign key (ID_DATO_PERSONA) 
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) 
      on delete restrict on update restrict;

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


ALTER TABLE `TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO` COMMENT = 'Tipos de documentos requeridos para servicio comunitario';

-- ==============================================================
-- Vistas y procedimientos: ver bddITSI_vistas_local.sql (solo local)
-- InfinityFree deniega CREATE VIEW / CREATE PROCEDURE (#1142).
-- ==============================================================

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
-- SELECT 'Vistas de períodos académicos creadas exitosamente' as RESULTADO;
-- SELECT COUNT(*) as VISTA_PERIODO_ACTUAL FROM information_schema.views WHERE table_name = 'V_PERIODO_ACADEMICO_ACTUAL';
-- SELECT COUNT(*) as VISTA_PERIODOS_ORDENADOS FROM information_schema.views WHERE table_name = 'V_PERIODOS_ACADEMICOS_ORDENADOS';
-- SELECT COUNT(*) as VISTA_ESTADISTICAS_PERIODOS FROM information_schema.views WHERE table_name = 'V_ESTADISTICAS_PERIODOS';
-- SELECT COUNT(*) as VISTA_DOCUMENTOS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_POR_PERIODO';
-- SELECT COUNT(*) as VISTA_PRACTICAS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_PRACTICAS_POR_PERIODO';
-- SELECT COUNT(*) as VISTA_SERVICIOS_POR_PERIODO FROM information_schema.views WHERE table_name = 'V_SERVICIOS_POR_PERIODO';

-- Verificar procedimientos almacenados de períodos académicos
-- SELECT 'Procedimientos almacenados de períodos académicos creados exitosamente' as RESULTADO;
-- SELECT COUNT(*) as PROC_PERIODO_ACTUAL FROM information_schema.routines WHERE routine_name = 'SP_OBTENER_PERIODO_ACTUAL';
-- SELECT COUNT(*) as PROC_ESTADISTICAS_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_ESTADISTICAS_PERIODO';
-- SELECT COUNT(*) as PROC_CAMBIAR_ESTADO_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_PERIODO';
-- SELECT COUNT(*) as PROC_DOCUMENTOS_POR_PERIODO FROM information_schema.routines WHERE routine_name = 'SP_DOCUMENTOS_POR_PERIODO';

-- Consultas de ejemplo (opcional; ejecutar por separado, no en un solo lote con CALL):
-- SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL;
-- CALL SP_ESTADISTICAS_PERIODO(1);   -- sustituir por un ID_PERIODO_ACADEMICO existente
-- SELECT * FROM V_PERIODOS_ACADEMICOS_ORDENADOS LIMIT 5;

-- Verificar vistas creadas
-- SELECT 'Vistas creadas exitosamente' as RESULTADO;
-- SELECT COUNT(*) as VISTA_PRACTICAS FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_PRACTICAS_COMPLETOS';
-- SELECT COUNT(*) as VISTA_SERVICIO FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_SERVICIO_COMPLETOS';
-- SELECT COUNT(*) as VISTA_UNIFICADA FROM information_schema.views WHERE table_name = 'V_DOCUMENTOS_UNIFICADOS';

-- Verificar procedimientos almacenados
-- SELECT 'Procedimientos almacenados creados exitosamente' as RESULTADO;
-- SELECT COUNT(*) as PROC_PRACTICAS FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_DOCUMENTO_PRACTICAS';
-- SELECT COUNT(*) as PROC_SERVICIO FROM information_schema.routines WHERE routine_name = 'SP_CAMBIAR_ESTADO_DOCUMENTO_SERVICIO';

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

SET FOREIGN_KEY_CHECKS = 1;
