/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     31/8/2025 10:46:53                           */
/*==============================================================*/

-- Crear y usar la base de datos (compatible con XAMPP)
CREATE DATABASE IF NOT EXISTS `itsi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `itsi`;

-- Eliminar tablas dependientes primero (en orden inverso de dependencias)
drop table if exists TAB_ASISTENCIAS_SERVICIO_COMUNITARIO;
drop table if exists TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_EVALUACIONES_SERVICIO_COMUNITARIO;
drop table if exists TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO;
drop table if exists TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_DOCUMENTOS_SERVICIO_COMUNITARIO;
drop table if exists TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES;
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
drop table if exists TAB_CARRERAS;
drop table if exists TAB_DEPARTAMENTOS;
drop table if exists TAB_EXPORTACIONES;
drop table if exists TAB_RECUPERACION_CONTRASENA;
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
/* Table: TAB_PERIODOS_ACADEMICOS                               */
/*==============================================================*/
create table TAB_PERIODOS_ACADEMICOS
(
   ID_PERIODO_ACADEMICO int not null auto_increment,
   NOMBRE_PERIODO       varchar(100) not null,
   AÑO_ACADEMICO        int not null comment 'Año de referencia del período (p. ej. año lectivo)',
   MES_INICIO           tinyint unsigned not null comment 'Mes inicio 1-12',
   AÑO_INICIO           int not null,
   MES_FIN              tinyint unsigned not null comment 'Mes fin 1-12',
   AÑO_FIN              int not null,
   TIPO_PERIODO         enum('Semestre', 'Trimestre', 'Cuatrimestre', 'Anual') not null default 'Semestre',
   NUMERO_PERIODO       int not null,
   ESTADO               enum('Activo', 'Inactivo', 'Finalizado', 'Planificado') not null default 'Planificado',
   DESCRIPCION          text,
   FECHA_CREACION       timestamp default current_timestamp,
   FECHA_ACTUALIZACION  timestamp default current_timestamp on update current_timestamp,
   ACTIVO               boolean default true,
   primary key (ID_PERIODO_ACADEMICO),
   unique key UK_PERIODO_ANIO (AÑO_ACADEMICO, NUMERO_PERIODO, TIPO_PERIODO),
   key IDX_AÑO_ACADEMICO (AÑO_ACADEMICO),
   key IDX_ESTADO_PERIODO (ESTADO),
   key IDX_PERIODO_MES_ANIO_INICIO (AÑO_INICIO, MES_INICIO),
   key IDX_ACTIVO_PERIODO (ACTIVO),
   check (MES_INICIO between 1 and 12),
   check (MES_FIN between 1 and 12)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Períodos académicos (inicio/fin por mes y año)';

/*==============================================================*/
/* Table: TAB_ACTIVIDADES_EDUCACION                             */
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
   FECHA_FIN            date not null,
   HORA_TOTAL           int not null,
   DESCRIPCION          text not null,
   CRONOGRAMA           varchar(255) not null,
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
   ID_INSTRUCTOR             int,
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
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO)
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
   ID_INSTRUCTOR             int,
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
   key IDX_PERIODO_ACADEMICO (ID_PERIODO_ACADEMICO)
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

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_INSTRUCTOR foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_ESTADO_PREPROFESIONAL foreign key (ID_ESTADO_PREPROFESIONAL)
      references TAB_ESTADOS_PRACTICAS_PREPROFESIONALES (ID_ESTADO_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ASIGNACION foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_INSTRUCTOR foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_ESTADO_SERVICIO_COMUNITARIO foreign key (ID_ESTADO_SERVICIO)
      references TAB_ESTADOS_SERVICIO_COMUNITARIO (ID_ESTADO_SERVICIO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_PERIODO foreign key (ID_PERIODO_ACADEMICO)
      references TAB_PERIODOS_ACADEMICOS (ID_PERIODO_ACADEMICO) on delete restrict on update restrict;

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
