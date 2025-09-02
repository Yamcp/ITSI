/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     31/8/2025 10:46:53                           */
/*==============================================================*/

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
drop table if exists TAB_SERVICIO_COMUNITARIO;
drop table if exists TAB_PRACTICAS_PREPROFESIONALES;
drop table if exists TAB_ASISTENCIAS_PRACTICAS;
drop table if exists TAB_SEGUIMIENTO_PRACTICAS;
drop table if exists TAB_DOCUMENTOS_PRACTICAS;
drop table if exists TAB_ACTIVIDADES_EDUCACION;
drop table if exists TAB_ASIGNACIONES_PRACTICAS;
drop table if exists TAB_EMPLEADOS_INTRUCTORES;
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
drop table if exists TAB_USUARIOS;
drop table if exists TAB_DATOS_PERSONAS;
drop table if exists TAB_ESTADOS_REVISIONES;
drop table if exists TAB_ESTADO_PRACTICAS;
drop table if exists TAB_TIPOS_ACTIVIDADES;
drop table if exists TAB_TIPOS_CONVENIOS;
drop table if exists TAB_TIPOS_DOCUMENTOS_PRACTICAS;
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
/* Table: TAB_ACTIVIDADES_EDUCACION                             */
/*==============================================================*/
create table TAB_ACTIVIDADES_EDUCACION
(
   ID_ACTIVIDAD_EDUCACION int not null auto_increment,
   ID_INSTRUCTOR        int,
   ID_TIPO_MODALIDAD    int,
   ID_TIPO_ACTIVIDAD    int,
   ID_USUARIO           int,
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
   primary key (ID_ACTIVIDAD_EDUCACION)
);

/*==============================================================*/
/* Table: TAB_ASIGNACIONES_PRACTICAS                            */
/*==============================================================*/
create table TAB_ASIGNACIONES_PRACTICAS
(
   ID_ASIGNACION_PRACTICA int not null auto_increment,
   ID_TIPO_PRACTICA     int,
   ID_USUARIO           int,
   ID_ESTADO_PRACTICAS  int,
   ID_INSTITUCION_CONVENIO int,
   FECHA_INICIO         date not null,
   FECHA_FIN            date not null,
   HORA_TOTAL           int not null,
   DESCRIPCION          text not null,
   CRONOGRAMA           varchar(255) not null,
   primary key (ID_ASIGNACION_PRACTICA)
);

/*==============================================================*/
/* Table: TAB_ASISTENCIAS_PRACTICAS                             */
/*==============================================================*/
create table TAB_ASISTENCIAS_PRACTICAS
(
   ID_ASISTENCIA        int not null auto_increment,
   ID_ASIGNACION_PRACTICA int,
   FECHA_ASISTENCIA     date,
   HORA_ENTRADA         time not null,
   HORA_SALIDA          time not null,
   ACTIVIDADES_DIA      text not null,
   FECHA_REGISTRO       timestamp not null,
   OBSERVACIONES        text not null,
   primary key (ID_ASISTENCIA)
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
   FECHA_INICIO         date not null,
   FECHA_FIN            date not null,
   DURACION             varchar(20) not null,
   OBJETIVO             text not null,
   OBSERVACIONES        text not null,
   ARCHIVO_CONVENIO     varchar(255) not null,
   RENOVABLE            boolean not null,
   primary key (ID_DETALLE_CONVENIO)
);

/*==============================================================*/
/* Table: TAB_DOCUMENTOS_PRACTICAS                              */
/*==============================================================*/
create table TAB_DOCUMENTOS_PRACTICAS
(
   ID_DOCUMENTO_PRACTICA int not null auto_increment,
   ID_ESTADO_REVISION   int,
   ID_TIPO_DOCUMENTO    int,
   ID_USUARIO           int,
   NOMBRE_ARCHIVO       varchar(255) not null,
   TIPO                 varchar(100) not null,
   FECHA_SUBIDA         timestamp not null,
   OBSERVACIONES        text not null,
   primary key (ID_DOCUMENTO_PRACTICA)
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
/* Table: TAB_EMPLEADOS_INTRUCTORES                             */
/*==============================================================*/
create table TAB_EMPLEADOS_INTRUCTORES
(
   ID_EMPLEADO          int,
   ID_INSTRUCTOR        int
);

/*==============================================================*/
/* Table: TAB_ESTADOS_REVISIONES                                */
/*==============================================================*/
create table TAB_ESTADOS_REVISIONES
(
   ID_ESTADO_REVISION   int not null auto_increment,
   ESTADO               varchar(20) not null,
   primary key (ID_ESTADO_REVISION)
);

/*==============================================================*/
/* Table: TAB_ESTADO_PRACTICAS                                  */
/*==============================================================*/
create table TAB_ESTADO_PRACTICAS
(
   ID_ESTADO_PRACTICAS  int not null auto_increment,
   ESTADO               varchar(20) not null,
   primary key (ID_ESTADO_PRACTICAS)
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
/* Table: TAB_EXPORTACIONES                                     */
/*==============================================================*/
create table TAB_EXPORTACIONES
(
   ID_EXPORTACION       int not null auto_increment,
   ID_USUARIO           int,
   FECHA_EXPORTACION    timestamp not null,
   DESCRIPCION_EXPORTACION varchar(100),
   primary key (ID_EXPORTACION)
);

/*==============================================================*/
/* Table: TAB_INSTITUCIONES_CONVENIOS                           */
/*==============================================================*/
create table TAB_INSTITUCIONES_CONVENIOS
(
   ID_INSTITUCION_CONVENIO int not null auto_increment,
   ID_TIPO_INSTITUCION  int,
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
   primary key (ID_INSTITUCION_CONVENIO)
);

/*==============================================================*/
/* Table: TAB_INSTITUCION_CARRERA                               */
/*==============================================================*/
create table TAB_INSTITUCION_CARRERA
(
   ID_CARRERA           int,
   ID_INSTITUCION_CONVENIO int
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
/* Table: TAB_SEGUIMIENTO_PRACTICAS                             */
/*==============================================================*/
create table TAB_SEGUIMIENTO_PRACTICAS
(
   ID_SEGUIMIENTO       int not null auto_increment,
   ID_ASIGNACION_PRACTICA int,
   HORAS_CUMPLIDAS      int not null,
   ACTIVIDADES_REALIZADAS text not null,
   OBSERVACIONES        text not null,
   ARCHIVO_REPORTE      varchar(255) not null,
   primary key (ID_SEGUIMIENTO)
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
/* Table: TAB_TIPOS_DOCUMENTOS_PRACTICAS                        */
/*==============================================================*/
create table TAB_TIPOS_DOCUMENTOS_PRACTICAS
(
   ID_TIPO_DOCUMENTO    int not null auto_increment,
   CODIGO               varchar(50) not null,
   NOMBRE               varchar(150) not null,
   DESCRIPCION          text not null,
   ORDEN                int not null,
   primary key (ID_TIPO_DOCUMENTO)
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
/* Table: TAB_PRACTICAS_PREPROFESIONALES                        */
/*==============================================================*/
create table TAB_PRACTICAS_PREPROFESIONALES
(
   ID_PRACTICA_PREPROFESIONAL int not null auto_increment,
   ID_ASIGNACION_PRACTICA     int,
   ID_ESTUDIANTE             int,
   ID_INSTRUCTOR             int,
   ID_INSTITUCION_CONVENIO   int,
   AREA_ESPECIALIZACION      varchar(200),
   PROYECTO_ESPECIFICO       text,
   HORAS_PRACTICAS           int,
   FECHA_INICIO              date,
   FECHA_FIN                 date,
   ESTADO_PRACTICA           varchar(50),
   EVALUACION_FINAL          decimal(3,2),
   OBSERVACIONES             text,
   primary key (ID_PRACTICA_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_SERVICIO_COMUNITARIO                              */
/*==============================================================*/
create table TAB_SERVICIO_COMUNITARIO
(
   ID_SERVICIO_COMUNITARIO   int not null auto_increment,
   ID_ASIGNACION_PRACTICA    int,
   ID_ESTUDIANTE             int,
   ID_INSTRUCTOR             int,
   ID_INSTITUCION_CONVENIO   int,
   PROYECTO_SOCIAL           varchar(200),
   COMUNIDAD_BENEFICIADA     text,
   HORAS_SERVICIO            int,
   FECHA_INICIO              date,
   FECHA_FIN                 date,
   ESTADO_SERVICIO           varchar(50),
   IMPACTO_SOCIAL            text,
   OBSERVACIONES             text,
   primary key (ID_SERVICIO_COMUNITARIO)
);

/*==============================================================*/
/* Table: TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES            */
/*==============================================================*/
create table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES
(
   ID_DOCUMENTO_PREPROFESIONAL int not null auto_increment,
   ID_PRACTICA_PREPROFESIONAL  int,
   ID_TIPO_DOCUMENTO           int,
   NOMBRE_ARCHIVO              varchar(255),
   TIPO_ARCHIVO                varchar(100),
   FECHA_SUBIDA                timestamp,
   ESTADO_REVISION             varchar(50),
   OBSERVACIONES               text,
   primary key (ID_DOCUMENTO_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_DOCUMENTOS_SERVICIO_COMUNITARIO                   */
/*==============================================================*/
create table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO
(
   ID_DOCUMENTO_SERVICIO       int not null auto_increment,
   ID_SERVICIO_COMUNITARIO    int,
   ID_TIPO_DOCUMENTO           int,
   NOMBRE_ARCHIVO              varchar(255),
   TIPO_ARCHIVO                varchar(100),
   FECHA_SUBIDA                timestamp,
   ESTADO_REVISION             varchar(50),
   OBSERVACIONES               text,
   primary key (ID_DOCUMENTO_SERVICIO)
);

/*==============================================================*/
/* Table: TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES                 */
/*==============================================================*/
create table TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES
(
   ID_TIPO_DOCUMENTO_PREPROFESIONAL int not null auto_increment,
   CODIGO                           varchar(50),
   NOMBRE                           varchar(150),
   DESCRIPCION                      text,
   ORDEN                            int,
   OBLIGATORIO                      boolean default true,
   primary key (ID_TIPO_DOCUMENTO_PREPROFESIONAL)
);

/*==============================================================*/
/* Table: TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO             */
/*==============================================================*/
create table TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO
(
   ID_TIPO_DOCUMENTO_SERVICIO       int not null auto_increment,
   CODIGO                           varchar(50),
   NOMBRE                           varchar(150),
   DESCRIPCION                      text,
   ORDEN                            int,
   OBLIGATORIO                      boolean default true,
   primary key (ID_TIPO_DOCUMENTO_SERVICIO)
);

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
);

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
);

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

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_REFERENCE_36 foreign key (ID_ESTADO_PRACTICAS)
      references TAB_ESTADO_PRACTICAS (ID_ESTADO_PRACTICAS) on delete restrict on update restrict;

alter table TAB_ASIGNACIONES_PRACTICAS add constraint FK_REFERENCE_48 foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_ASISTENCIAS_PRACTICAS add constraint FK_REFERENCE_9 foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_DETALLES_CONVENIOS add constraint FK_REFERENCE_34 foreign key (ID_TIPO_CONVENIO)
      references TAB_TIPOS_CONVENIOS (ID_TIPO_CONVENIO) on delete restrict on update restrict;

alter table TAB_DETALLES_CONVENIOS add constraint FK_REFERENCE_35 foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;



alter table TAB_DOCUMENTOS_PRACTICAS add constraint FK_REFERENCE_46 foreign key (ID_TIPO_DOCUMENTO)
      references TAB_TIPOS_DOCUMENTOS_PRACTICAS (ID_TIPO_DOCUMENTO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS add constraint FK_REFERENCE_47 foreign key (ID_ESTADO_REVISION)
      references TAB_ESTADOS_REVISIONES (ID_ESTADO_REVISION) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS add constraint FK_REFERENCE_49 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_19 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_24 foreign key (ID_TIPO_CONTRATO)
      references TAB_TIPOS_CONTRATO (ID_TIPO_CONTRATO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS add constraint FK_REFERENCE_4 foreign key (ID_DEPARTAMENTO)
      references TAB_DEPARTAMENTOS (ID_DEPARTAMENTO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS_INTRUCTORES add constraint FK_REFERENCE_42 foreign key (ID_EMPLEADO)
      references TAB_EMPLEADOS (ID_EMPLEADO) on delete restrict on update restrict;

alter table TAB_EMPLEADOS_INTRUCTORES add constraint FK_REFERENCE_43 foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_20 foreign key (ID_TIPO_ESTADO)
      references TAB_TIPOS_ESTADOS (ID_TIPO_ESTADO) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_40 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_ESTUDIANTES add constraint FK_REFERENCE_45 foreign key (ID_CARRERA)
      references TAB_CARRERAS (ID_CARRERA) on delete restrict on update restrict;

alter table TAB_EXPORTACIONES add constraint FK_REFERENCE_17 foreign key (ID_USUARIO)
      references TAB_USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table TAB_INSTITUCIONES_CONVENIOS add constraint FK_REFERENCE_33 foreign key (ID_TIPO_INSTITUCION)
      references TAB_TIPOS_INSTITUCION (ID_TIPO_INSTITUCION) on delete restrict on update restrict;

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

alter table TAB_SEGUIMIENTO_PRACTICAS add constraint FK_REFERENCE_37 foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_USUARIOS add constraint FK_REFERENCE_12 foreign key (ID_DATO_PERSONA)
      references TAB_DATOS_PERSONAS (ID_DATO_PERSONA) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_ASIGNACION foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_INSTRUCTOR foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_PRACTICAS_PREPROFESIONALES add constraint FK_PRACTICAS_PREPROFESIONALES_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ASIGNACION foreign key (ID_ASIGNACION_PRACTICA)
      references TAB_ASIGNACIONES_PRACTICAS (ID_ASIGNACION_PRACTICA) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_ESTUDIANTE foreign key (ID_ESTUDIANTE)
      references TAB_ESTUDIANTES (ID_ESTUDIANTE) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_INSTRUCTOR foreign key (ID_INSTRUCTOR)
      references TAB_INSTRUCTORES (ID_INSTRUCTOR) on delete restrict on update restrict;

alter table TAB_SERVICIO_COMUNITARIO add constraint FK_SERVICIO_COMUNITARIO_INSTITUCION foreign key (ID_INSTITUCION_CONVENIO)
      references TAB_INSTITUCIONES_CONVENIOS (ID_INSTITUCION_CONVENIO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_PRACTICA foreign key (ID_PRACTICA_PREPROFESIONAL)
      references TAB_PRACTICAS_PREPROFESIONALES (ID_PRACTICA_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES add constraint FK_DOCS_PREPROFESIONALES_TIPO foreign key (ID_TIPO_DOCUMENTO)
      references TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES (ID_TIPO_DOCUMENTO_PREPROFESIONAL) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_SERVICIO foreign key (ID_SERVICIO_COMUNITARIO)
      references TAB_SERVICIO_COMUNITARIO (ID_SERVICIO_COMUNITARIO) on delete restrict on update restrict;

alter table TAB_DOCUMENTOS_SERVICIO_COMUNITARIO add constraint FK_DOCS_SERVICIO_TIPO foreign key (ID_TIPO_DOCUMENTO)
      references TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO (ID_TIPO_DOCUMENTO_SERVICIO) on delete restrict on update restrict;

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

/*==============================================================*/
/* Insertar datos iniciales                                     */
/*==============================================================*/
INSERT INTO `TAB_DATOS_PERSONAS` (`ID_DATO_PERSONA`, `NOMBRE`, `APELLIDO`, `CEDULA`, `CELULAR`, `DIRECCION`, `EMAIL`, `GENERO`, `ESTADO_CIVIL`, `NACIONALIDAD`, `FECHA_INGRESO`, `ACTIVO`, `FOTO_URL`) VALUES
(1, 'Yamilex Marisol', 'Campues Angamarca', '1004191845', '0992432078', 'Ibarra', 'yamilex.campues2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-06-05', 1, ''),
(2, 'Ana ', 'Yandun', '1724143290', '0981377492', 'Ibarra', 'ana.yandun2023@itsi.edu.ec', 'Femenino', 'Casada', 'Ecuatoriana', '2025-06-10', 1, ''),
(3, 'Pedro', 'Aguirre', '0123456789', '', '', '', '', '', NULL, '0000-00-00', 0, '');

INSERT INTO `TAB_USUARIOS` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(1, 1, 'ycampues', '123', '1'),
(2, 2, 'ayandun', '123', '1'),
(3, 3, 'paguirre', '123', '1');

INSERT INTO `TAB_TIPOS_ROLES` (`ID_TIPOS_ROLES`, `ROL`) VALUES
(1, 'Administrador'),
(2, 'Docente'),
(3, 'Estudiante');

INSERT INTO `TAB_ROLES` (`ID_ROL`, `ID_USUARIO`, `ID_TIPOS_ROLES`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

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
(3, 'Seminario'),
(4, 'Conferencia'),
(5, 'Capacitación');

INSERT INTO `TAB_TIPOS_CONTRATO` (`ID_TIPO_CONTRATO`, `TIPO_CONTRATO`) VALUES
(1, 'Tiempo Completo'),
(2, 'Medio Tiempo'),
(3, 'Por Horas'),
(4, 'Consultoría');

INSERT INTO `TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES` (`CODIGO`, `NOMBRE`, `DESCRIPCION`, `ORDEN`, `OBLIGATORIO`) VALUES
('PP001', 'Oficio de Asignación de Tutor', 'Oficio de asignación de tutor docente', 1, true),
('PP002', 'Oficio Personal a Entidad', 'Oficio personal a la entidad receptora', 2, true),
('PP003', 'Carta de Aceptación', 'Carta de aceptación de la entidad receptora', 3, true),
('PP004', 'Solicitud Institucional', 'Solicitud institucional valorada', 4, true),
('PP005', 'Certificado de Culminación', 'Certificado de haber culminado las prácticas', 5, true),
('PP006', 'Rúbrica Entidad Receptora', 'Rúbrica de evaluación de la entidad', 6, true),
('PP007', 'Hojas de Asistencia', 'Hojas de asistencia de estudiantes', 7, true),
('PP008', 'Ficha de Actividades', 'Ficha de registro de actividades realizadas', 8, true),
('PP009', 'Ficha de Control Docente', 'Ficha de control y seguimiento docente', 9, true),
('PP010', 'Rúbrica Docente', 'Rúbrica de evaluación del docente', 10, true),
('PP011', 'Rúbrica de Resultados', 'Rúbrica de evaluación de resultados', 11, true),
('PP012', 'Respaldo Fotográfico', 'Respaldo en fotos de trabajos realizados', 12, true);

-- Insertar tipos de documentos para Servicio Comunitario
INSERT INTO `TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO` (`CODIGO`, `NOMBRE`, `DESCRIPCION`, `ORDEN`, `OBLIGATORIO`) VALUES
('SC001', 'Oficio de Asignación de Tutor', 'Oficio de asignación de tutor docente', 1, true),
('SC002', 'Oficio Personal a Entidad', 'Oficio personal a la entidad receptora', 2, true),
('SC003', 'Carta de Aceptación', 'Carta de aceptación de la entidad receptora', 3, true),
('SC004', 'Solicitud Institucional', 'Solicitud institucional valorada', 4, true),
('SC005', 'Certificado de Culminación', 'Certificado de haber culminado las prácticas', 5, true),
('SC006', 'Rúbrica Entidad Receptora', 'Rúbrica de evaluación de la entidad', 6, true),
('SC007', 'Hojas de Asistencia', 'Hojas de asistencia de estudiantes', 7, true),
('SC008', 'Ficha de Actividades', 'Ficha de registro de actividades realizadas', 8, true),
('SC009', 'Ficha de Control Docente', 'Ficha de control y seguimiento docente', 9, true),
('SC010', 'Rúbrica Docente', 'Rúbrica de evaluación del docente', 10, true),
('SC011', 'Rúbrica de Resultados', 'Rúbrica de evaluación de resultados', 11, true),
('SC012', 'Respaldo Fotográfico', 'Respaldo en fotos de trabajos realizados', 12, true);

-- Insertar estados para Prácticas Preprofesionales
INSERT INTO `TAB_ESTADOS_PRACTICAS_PREPROFESIONALES` (`ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
('Pendiente', 'Práctica pendiente de inicio', '#ffc107'),
('En Progreso', 'Práctica en desarrollo', '#17a2b8'),
('Pausada', 'Práctica temporalmente pausada', '#6c757d'),
('Completada', 'Práctica finalizada exitosamente', '#28a745'),
('Cancelada', 'Práctica cancelada', '#dc3545'),
('Evaluada', 'Práctica evaluada y aprobada', '#20c997');

-- Insertar estados para Servicio Comunitario
INSERT INTO `TAB_ESTADOS_SERVICIO_COMUNITARIO` (`ESTADO`, `DESCRIPCION`, `COLOR`) VALUES
('Pendiente', 'Servicio pendiente de inicio', '#ffc107'),
('En Progreso', 'Servicio en desarrollo', '#17a2b8'),
('Pausado', 'Servicio temporalmente pausado', '#6c757d'),
('Completado', 'Servicio finalizado exitosamente', '#28a745'),
('Cancelado', 'Servicio cancelado', '#dc3545'),
('Evaluado', 'Servicio evaluado y aprobado', '#20c997');

-- Insertar tipos de modalidades
INSERT INTO `TAB_TIPOS_MODALIDADES` (`ID_TIPO_MODALIDAD`, `MODALIDAD`) VALUES
(1, 'Presencial'),
(2, 'Virtual'),
(3, 'Híbrida');

-- Insertar tipos de instructores 
INSERT INTO `TAB_TIPOS_INSTRUCTORES` (`ID_TIPO_INSTRUCTOR`, `TIPO`) VALUES
(1, 'Docente'),
(2, 'Especialista'),
(3, 'Consultor'),
(4, 'Investigador');

-- Insertar instructores de ejemplo
INSERT INTO `TAB_DATOS_PERSONAS` (`ID_DATO_PERSONA`, `NOMBRE`, `APELLIDO`, `CEDULA`, `CELULAR`, `DIRECCION`, `EMAIL`, `GENERO`, `ESTADO_CIVIL`, `NACIONALIDAD`, `FECHA_INGRESO`, `ACTIVO`, `FOTO_URL`) VALUES
(4, 'Carlos', 'Mendoza', '1234567890', '0987654321', 'Ibarra, Ecuador', 'carlos.mendoza@itsi.edu.ec', 'Masculino', 'Casado', 'Ecuatoriana', '2025-01-15', 1, ''),
(5, 'Ana', 'Ruiz', '0987654321', '0912345678', 'Quito, Ecuador', 'ana.ruiz@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-01-20', 1, ''),
(6, 'María', 'González', '1122334455', '0999888777', 'Guayaquil, Ecuador', 'maria.gonzalez@itsi.edu.ec', 'Femenino', 'Casada', 'Ecuatoriana', '2025-01-25', 1, '');

INSERT INTO `TAB_INSTRUCTORES` (`ID_INSTRUCTOR`, `ID_TIPO_INSTRUCTOR`, `ID_DATO_PERSONA`, `ESPECIALIDAD`, `TITULO_PROFESIONAL`) VALUES
(1, 1, 4, 'Desarrollo de Software', 'Ingeniero en Sistemas'),
(2, 2, 5, 'Hardware y Redes', 'Técnico en Electrónica'),
(3, 3, 6, 'Inteligencia Artificial', 'Doctora en Ciencias de la Computación');

-- Insertar actividades educativas de ejemplo
INSERT INTO `TAB_ACTIVIDADES_EDUCACION` (`ID_ACTIVIDAD_EDUCACION`, `ID_INSTRUCTOR`, `ID_TIPO_MODALIDAD`, `ID_TIPO_ACTIVIDAD`, `ID_USUARIO`, `NOMBRE_ACTIVIDAD`, `DESCRIPCION`, `OBJETIVOS`, `DURACION_HORAS`, `FECHA_INICIO`, `FECHA_FIN`, `LUGAR`, `HORARIO`, `INCLUYE_CERTIFICADO`, `PROGRAMA_DETALLADO`) VALUES
(1, 1, 1, 1, 1, 'Desarrollo Web Full Stack', 'Curso completo de desarrollo web con tecnologías modernas como React, Node.js, MongoDB y más.', 'Formar desarrolladores full stack competentes en tecnologías web modernas', 120, '2025-09-01', '2025-11-30', 'Laboratorio de Programación', 'Lunes a Viernes 14:00-18:00', 1, 'Módulo 1: HTML/CSS/JavaScript\nMódulo 2: React.js\nMódulo 3: Node.js\nMódulo 4: Base de datos\nMódulo 5: Proyecto final'),
(2, 2, 2, 2, 1, 'Reparación de Equipos de Cómputo', 'Taller práctico de mantenimiento y reparación de hardware de computadoras.', 'Capacitar en técnicas de diagnóstico y reparación de equipos', 40, '2025-10-01', '2025-10-31', 'Plataforma Virtual', 'Sábados 9:00-13:00', 1, 'Diagnóstico de problemas\nReparación de hardware\nMantenimiento preventivo\nInstalación de software'),
(3, 3, 1, 3, 1, 'Inteligencia Artificial y Machine Learning', 'Seminario sobre tendencias actuales en IA y aplicaciones prácticas.', 'Actualizar conocimientos en inteligencia artificial y sus aplicaciones', 16, '2025-12-15', '2025-12-16', 'Auditorio Principal', '8:00-17:00', 1, 'Introducción a la IA\nMachine Learning básico\nDeep Learning\nAplicaciones prácticas\nCasos de estudio'),
(4, 1, 1, 1, 1, 'Programación en Python', 'Curso introductorio de programación usando Python como lenguaje principal.', 'Enseñar los fundamentos de programación usando Python', 80, '2025-08-01', '2025-09-30', 'Laboratorio de Programación', 'Martes y Jueves 18:00-20:00', 1, 'Variables y tipos de datos\nEstructuras de control\nFunciones\nPOO\nLibrerías básicas'),
(5, 2, 2, 2, 1, 'Configuración de Redes', 'Taller de configuración y administración de redes de computadoras.', 'Capacitar en configuración y administración de redes', 32, '2025-11-01', '2025-11-30', 'Laboratorio de Redes', 'Sábados 8:00-12:00', 1, 'Protocolos de red\nConfiguración de routers\nSwitches y VLANs\nSeguridad en redes');

-- Insertar usuarios de ejemplo si no existen
INSERT INTO `TAB_USUARIOS` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(4, 4, 'cmendoza', '123', '1'),
(5, 5, 'aruiz', '123', '1'),
(6, 6, 'mgonzalez', '123', '1');

-- Insertar roles para los instructores
INSERT IGNORE INTO `TAB_ROLES` (`ID_ROL`, `ID_USUARIO`, `ID_TIPOS_ROLES`) VALUES
(4, 4, 2),
(5, 5, 2),
(6, 6, 2);

-- Insertar datos de personas para estudiantes
INSERT INTO `TAB_DATOS_PERSONAS` (`ID_DATO_PERSONA`, `NOMBRE`, `APELLIDO`, `CEDULA`, `CELULAR`, `DIRECCION`, `EMAIL`, `GENERO`, `ESTADO_CIVIL`, `NACIONALIDAD`, `FECHA_INGRESO`, `ACTIVO`, `FOTO_URL`) VALUES
(7, 'Juan Carlos', 'Pérez López', '1001234567', '0987654321', 'Ibarra, Ecuador', 'juan.perez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-01-15', 1, ''),
(8, 'María Elena', 'García Torres', '1002345678', '0976543210', 'Quito, Ecuador', 'maria.garcia2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-01-20', 1, ''),
(9, 'Carlos Alberto', 'Rodríguez Silva', '1003456789', '0965432109', 'Guayaquil, Ecuador', 'carlos.rodriguez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-01-25', 1, ''),
(10, 'Ana Lucía', 'Martínez Vega', '1004567890', '0954321098', 'Cuenca, Ecuador', 'ana.martinez2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-01', 1, ''),
(11, 'Luis Fernando', 'Herrera Castro', '1005678901', '0943210987', 'Ambato, Ecuador', 'luis.herrera2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-05', 1, ''),
(12, 'Sofía Alejandra', 'Morales Jiménez', '1006789012', '0932109876', 'Riobamba, Ecuador', 'sofia.morales2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-10', 1, ''),
(13, 'Diego Armando', 'Vargas Ruiz', '1007890123', '0921098765', 'Loja, Ecuador', 'diego.vargas2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-15', 1, ''),
(14, 'Valentina', 'Castro Mendoza', '1008901234', '0910987654', 'Machala, Ecuador', 'valentina.castro2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-02-20', 1, ''),
(15, 'Andrés Felipe', 'López Sánchez', '1009012345', '0909876543', 'Portoviejo, Ecuador', 'andres.lopez2023@itsi.edu.ec', 'Masculino', 'Soltero', 'Ecuatoriana', '2025-02-25', 1, ''),
(16, 'Camila Estefanía', 'Ramírez Flores', '1010123456', '0998765432', 'Esmeraldas, Ecuador', 'camila.ramirez2023@itsi.edu.ec', 'Femenino', 'Soltera', 'Ecuatoriana', '2025-03-01', 1, '');

-- Insertar usuarios para los estudiantes
INSERT INTO `TAB_USUARIOS` (`ID_USUARIO`, `ID_DATO_PERSONA`, `USUARIO`, `CONTRASENA`, `ESTADO`) VALUES
(7, 7, 'jperez', '123', '1'),
(8, 8, 'mgarcia', '123', '1'),
(9, 9, 'crodriguez', '123', '1'),
(10, 10, 'amartinez', '123', '1'),
(11, 11, 'lherrera', '123', '1'),
(12, 12, 'smorales', '123', '1'),
(13, 13, 'dvargas', '123', '1'),
(14, 14, 'vcastro', '123', '1'),
(15, 15, 'alopez', '123', '1'),
(16, 16, 'cramirez', '123', '1');

-- Asignar roles de estudiante
INSERT INTO `TAB_ROLES` (`ID_ROL`, `ID_USUARIO`, `ID_TIPOS_ROLES`) VALUES
(7, 7, 3),
(8, 8, 3),
(9, 9, 3),
(10, 10, 3),
(11, 11, 3),
(12, 12, 3),
(13, 13, 3),
(14, 14, 3),
(15, 15, 3),
(16, 16, 3);

-- Crear registros de estudiantes con carreras
INSERT INTO `TAB_ESTUDIANTES` (`ID_ESTUDIANTE`, `ID_TIPO_ESTADO`, `ID_DATO_PERSONA`, `ID_CARRERA`, `SEMESTRE_ACTUAL`) VALUES
(1, 1, 7, 1, 3),  -- Juan Carlos - Desarrollo de Software - 3er semestre
(2, 1, 8, 2, 2),  -- María Elena - Diseño Gráfico - 2do semestre
(3, 1, 9, 3, 4),  -- Carlos Alberto - Redes y Telecomunicaciones - 4to semestre
(4, 1, 10, 1, 1), -- Ana Lucía - Desarrollo de Software - 1er semestre
(5, 1, 11, 4, 3), -- Luis Fernando - Administración - 3er semestre
(6, 1, 12, 2, 2), -- Sofía Alejandra - Diseño Gráfico - 2do semestre
(7, 1, 13, 3, 5), -- Diego Armando - Redes y Telecomunicaciones - 5to semestre
(8, 1, 14, 5, 2), -- Valentina - Atención Integral a Adultos Mayores - 2do semestre
(9, 1, 15, 6, 3), -- Andrés Felipe - Marketing Digital y Comercio Electrónico - 3er semestre
(10, 1, 16, 1, 4); -- Camila Estefanía - Desarrollo de Software - 4to semestre