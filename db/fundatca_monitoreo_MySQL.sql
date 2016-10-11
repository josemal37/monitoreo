/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     10/10/2016 09:20:33                          */
/*==============================================================*/

/*==============================================================*/
/* Table: ACTIVIDAD                                             */
/*==============================================================*/
create table ACTIVIDAD
(
   ID_ACTIVIDAD         int not null auto_increment,
   ID_PROYECTO          int not null,
   NOMBRE_ACTIVIDAD     varchar(128) not null,
   DESCRIPCION_ACTIVIDAD text,
   FECHA_INICIO_ACTIVIDAD date not null,
   FECHA_FIN_ACTIVIDAD  date not null,
   PRESUPUESTO_ACTIVIDAD decimal(9,2),
   primary key (ID_ACTIVIDAD)
);

/*==============================================================*/
/* Table: AVANCE_HITO_CUALITATIVO                               */
/*==============================================================*/
create table AVANCE_HITO_CUALITATIVO
(
   ID_AVANCE_HITO_CL    int not null auto_increment,
   ID_HITO_CL           int,
   FECHA_AVANCE_HITO_CL date not null,
   TITULO_AVANCE_HITO_CL varchar(128) not null,
   DESCRIPCION_AVANCE_HITO_CL text not null,
   DOCUMENTO_AVANCE_HITO_CL varchar(128) not null,
   APROBADO_AVANCE_HITO_CL bool not null,
   EN_REVISION_AVANCE_HITO_CL bool not null,
   primary key (ID_AVANCE_HITO_CL)
);

/*==============================================================*/
/* Table: AVANCE_HITO_CUANTITATIVO                              */
/*==============================================================*/
create table AVANCE_HITO_CUANTITATIVO
(
   ID_AVANCE_HITO_CN    int not null auto_increment,
   ID_HITO_CN           int,
   CANTIDAD_AVANCE_HITO_CN decimal(9,2) not null,
   FECHA_AVANCE_HITO_CN date not null,
   DESCRIPCION_AVANCE_HITO_CN text not null,
   APROBADO_AVANCE_HITO_CN bool not null,
   EN_REVISION_AVANCE_HITO_CN bool not null,
   primary key (ID_AVANCE_HITO_CN)
);

/*==============================================================*/
/* Table: DOCUMENTO_ACTIVIDAD                                   */
/*==============================================================*/
create table DOCUMENTO_ACTIVIDAD
(
   ID_DOCUMENTO_ACTIVIDAD int not null auto_increment,
   ID_ACTIVIDAD         int not null,
   TITULO_DOCUMENTO_ACTIVIDAD varchar(64) not null,
   DESCRIPCION_DOCUMENTO_ACTIVIDAD text not null,
   ARCHIVO_DOCUMENTO_ACTIVIDAD varchar(128) not null,
   primary key (ID_DOCUMENTO_ACTIVIDAD)
);

/*==============================================================*/
/* Table: DOCUMENTO_AVANCE_HITO_CUANTITATIVO                    */
/*==============================================================*/
create table DOCUMENTO_AVANCE_HITO_CUANTITATIVO
(
   ID_DOCUMENTO_AVANCE_HITO_CN int not null auto_increment,
   ID_AVANCE_HITO_CN    int not null,
   TITULO_DOCUMENTO_AVANCE_HITO_CN varchar(64) not null,
   DESCRIPCION_DOCUMENTO_AVANCE_HITO_CN text not null,
   ARCHIVO_DOCUMENTO_AVANCE_HITO_CN varchar(128) not null,
   primary key (ID_DOCUMENTO_AVANCE_HITO_CN)
);

/*==============================================================*/
/* Table: EFECTO                                                */
/*==============================================================*/
create table EFECTO
(
   ID_EFECTO            int not null auto_increment,
   ID_PRODOC            int not null,
   NOMBRE_EFECTO        varchar(1024) not null,
   DESCRIPCION_EFECTO   text,
   primary key (ID_EFECTO)
);

/*==============================================================*/
/* Table: GASTO_ACTIVIDAD                                       */
/*==============================================================*/
create table GASTO_ACTIVIDAD
(
   ID_GASTO_ACTIVIDAD   int not null auto_increment,
   ID_ACTIVIDAD         int not null,
   FECHA_GASTO_ACTIVIDAD date not null,
   CONCEPTO_GASTO_ACTIVIDAD varchar(512) not null,
   IMPORTE_GASTO_ACTIVIDAD decimal(9,2) not null,
   RESPALDO_GASTO_ACTIVIDAD varchar(128) not null,
   primary key (ID_GASTO_ACTIVIDAD)
);

/*==============================================================*/
/* Table: GASTO_PROYECTO                                        */
/*==============================================================*/
create table GASTO_PROYECTO
(
   ID_GASTO_PROYECTO    int not null auto_increment,
   ID_PROYECTO          int not null,
   FECHA_GASTO_PROYECTO date not null,
   CONCEPTO_GASTO_PROYECTO varchar(512) not null,
   IMPORTE_GASTO_PROYECTO decimal(9,2) not null,
   RESPALDO_GASTO_PROYECTO varchar(128) not null,
   primary key (ID_GASTO_PROYECTO)
);

/*==============================================================*/
/* Table: HITO_CUALITATIVO                                      */
/*==============================================================*/
create table HITO_CUALITATIVO
(
   ID_HITO_CL           int not null auto_increment,
   ID_ACTIVIDAD         int,
   NOMBRE_HITO_CL       varchar(128) not null,
   DESCRIPCION_HITO_CL  text not null,
   primary key (ID_HITO_CL)
);

/*==============================================================*/
/* Table: HITO_CUANTITATIVO                                     */
/*==============================================================*/
create table HITO_CUANTITATIVO
(
   ID_HITO_CN           int not null auto_increment,
   ID_ACTIVIDAD         int not null,
   NOMBRE_HITO_CN       varchar(128) not null,
   DESCRIPCION_HITO_CN  text not null,
   META_HITO_CN         numeric(8,0) not null,
   UNIDAD_HITO_CN       varchar(32) not null,
   primary key (ID_HITO_CN)
);

/*==============================================================*/
/* Table: INDICADOR_CUANTITATIVO                                */
/*==============================================================*/
create table INDICADOR_CUANTITATIVO
(
   ID_INDICADOR_CN      int not null auto_increment,
   ID_TIPO_INDICADOR_CN int not null,
   ID_HITO_CN           int,
   NOMBRE_INDICADOR_CN  varchar(128) not null,
   ACEPTABLE_CN         decimal(9,2) not null,
   LIMITADO_CN          decimal(9,2) not null,
   NO_ACEPTABLE_CN      decimal(9,2) not null,
   primary key (ID_INDICADOR_CN)
);

/*==============================================================*/
/* Table: INSTITUCION                                           */
/*==============================================================*/
create table INSTITUCION
(
   ID_INSTITUCION       int not null auto_increment,
   NOMBRE_INSTITUCION   varchar(128) not null,
   SIGLA_INSTITUCION    varchar(8) not null,
   PRESUPUESTO_INSTITUCION decimal(9,2) not null,
   CARPETA_INSTITUCION  varchar(32),
   ACTIVA_INSTITUCION   bool not null,
   primary key (ID_INSTITUCION)
);

/*==============================================================*/
/* Table: META_ACTIVIDAD_APORTA_META_PRODUCTO_CL                */
/*==============================================================*/
create table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL
(
   ID_HITO_CL           int not null,
   ID_META_PRODUCTO_CUALITATIVA int not null,
   primary key (ID_HITO_CL, ID_META_PRODUCTO_CUALITATIVA)
);

/*==============================================================*/
/* Table: META_ACTIVIDAD_APORTA_META_PRODUCTO_CN                */
/*==============================================================*/
create table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
(
   ID_HITO_CN           int not null,
   ID_META_PRODUCTO_CUANTITATIVA int not null,
   primary key (ID_HITO_CN, ID_META_PRODUCTO_CUANTITATIVA)
);

/*==============================================================*/
/* Table: META_PRODUCTO_CUALITATIVA                             */
/*==============================================================*/
create table META_PRODUCTO_CUALITATIVA
(
   ID_META_PRODUCTO_CUALITATIVA int not null auto_increment,
   ID_PRODUCTO          int not null,
   NOMBRE_META_PRODUCTO_CUALITATIVA varchar(256) not null,
   DESCRIPCION_META_PRODUCTO_CUALITATIVA text,
   primary key (ID_META_PRODUCTO_CUALITATIVA)
);

/*==============================================================*/
/* Table: META_PRODUCTO_CUANTITATIVA                            */
/*==============================================================*/
create table META_PRODUCTO_CUANTITATIVA
(
   ID_META_PRODUCTO_CUANTITATIVA int not null auto_increment,
   ID_PRODUCTO          int not null,
   CANTIDAD_META_PRODUCTO_CUANTITATIVA decimal(10,2) not null,
   UNIDAD_META_PRODUCTO_CUANTITATIVA varchar(128) not null,
   NOMBRE_META_PRODUCTO_CUANTITATIVA varchar(1024) not null,
   DESCRIPCION_META_PRODUCTO_CUANTITATIVA text not null,
   primary key (ID_META_PRODUCTO_CUANTITATIVA)
);

/*==============================================================*/
/* Table: PRODOC                                                */
/*==============================================================*/
create table PRODOC
(
   ID_PRODOC            int not null auto_increment,
   NOMBRE_PRODOC        varchar(1024) not null,
   DESCRIPCION_PRODOC   text,
   OBJETIVO_GLOBAL_PRODOC text,
   OBJETIVO_PROYECTO_PRODOC text,
   primary key (ID_PRODOC)
);

/*==============================================================*/
/* Table: PRODUCTO                                              */
/*==============================================================*/
create table PRODUCTO
(
   ID_PRODUCTO          int not null auto_increment,
   ID_EFECTO            int,
   NOMBRE_PRODUCTO      varchar(1024) not null,
   DESCRIPCION_PRODUCTO text,
   primary key (ID_PRODUCTO)
);

/*==============================================================*/
/* Table: PRODUCTO_RECIBE_ACTIVIDAD                             */
/*==============================================================*/
create table PRODUCTO_RECIBE_ACTIVIDAD
(
   ID_ACTIVIDAD         int not null,
   ID_PRODUCTO          int not null,
   primary key (ID_ACTIVIDAD, ID_PRODUCTO)
);

/*==============================================================*/
/* Table: PROYECTO                                              */
/*==============================================================*/
create table PROYECTO
(
   ID_PROYECTO          int not null auto_increment,
   ID_INSTITUCION       int not null,
   NOMBRE_PROYECTO      varchar(128) not null,
   DESCRIPCION_PROYECTO text,
   PRESUPUESTO_PROYECTO decimal(9,2),
   EN_EDICION           bool not null,
   primary key (ID_PROYECTO)
);

/*==============================================================*/
/* Table: ROL                                                   */
/*==============================================================*/
create table ROL
(
   ID_ROL               int not null auto_increment,
   NOMBRE_ROL           varchar(32) not null,
   primary key (ID_ROL)
);

/*==============================================================*/
/* Table: TIPO_INDICADOR_CUANTITATIVO                           */
/*==============================================================*/
create table TIPO_INDICADOR_CUANTITATIVO
(
   ID_TIPO_INDICADOR_CN int not null auto_increment,
   NOMBRE_TIPO_INDICADOR_CN varchar(128) not null,
   DESCRIPCION_TIPO_INDICADOR_CN text,
   primary key (ID_TIPO_INDICADOR_CN)
);

/*==============================================================*/
/* Table: USUARIO                                               */
/*==============================================================*/
create table USUARIO
(
   ID_USUARIO           int not null auto_increment,
   ID_INSTITUCION       int not null,
   ID_ROL               int not null,
   NOMBRE_USUARIO       varchar(64) not null,
   APELLIDO_PATERNO_USUARIO varchar(32) not null,
   APELLIDO_MATERNO_USUARIO varchar(32) not null,
   LOGIN_USUARIO        varchar(32) not null,
   PASSWORD_USUARIO     varchar(32) not null,
   TELEFONO_USUARIO     int,
   CORREO_USUARIO       varchar(64),
   ACTIVO_USUARIO       bool not null,
   primary key (ID_USUARIO)
);

alter table ACTIVIDAD add constraint FK_PROYECTO_TIENE_ACTIVIDAD foreign key (ID_PROYECTO)
      references PROYECTO (ID_PROYECTO) on delete restrict on update restrict;

alter table AVANCE_HITO_CUALITATIVO add constraint FK_HITO_CL_TIENE_AVANCE foreign key (ID_HITO_CL)
      references HITO_CUALITATIVO (ID_HITO_CL) on delete restrict on update restrict;

alter table AVANCE_HITO_CUANTITATIVO add constraint FK_HITO_CN_TIENE_AVANCE foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete restrict on update restrict;

alter table DOCUMENTO_ACTIVIDAD add constraint FK_ACTIVIDAD_TIENE_DOCUMENTO foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete restrict on update restrict;

alter table DOCUMENTO_AVANCE_HITO_CUANTITATIVO add constraint FK_AVANCE_CN_TIENE_RESPALDO foreign key (ID_AVANCE_HITO_CN)
      references AVANCE_HITO_CUANTITATIVO (ID_AVANCE_HITO_CN) on delete restrict on update restrict;

alter table EFECTO add constraint FK_PRODOC_TIENE_EFECTO foreign key (ID_PRODOC)
      references PRODOC (ID_PRODOC) on delete restrict on update restrict;

alter table GASTO_ACTIVIDAD add constraint FK_ACTIVIDAD_TIENE_GASTO foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete restrict on update restrict;

alter table GASTO_PROYECTO add constraint FK_PROYECTO_TIENE_GASTO foreign key (ID_PROYECTO)
      references PROYECTO (ID_PROYECTO) on delete restrict on update restrict;

alter table HITO_CUALITATIVO add constraint FK_ACTIVIDAD_TIENE_HITO_CL foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete restrict on update restrict;

alter table HITO_CUANTITATIVO add constraint FK_ACTIVIDAD_TIENE_HITO_CN foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete restrict on update restrict;

alter table INDICADOR_CUANTITATIVO add constraint FK_HITO_TIENE_INDICADOR_OP foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete restrict on update restrict;

alter table INDICADOR_CUANTITATIVO add constraint FK_INDICADOR_OP_ES_DE_TIPO foreign key (ID_TIPO_INDICADOR_CN)
      references TIPO_INDICADOR_CUANTITATIVO (ID_TIPO_INDICADOR_CN) on delete restrict on update restrict;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CL foreign key (ID_HITO_CL)
      references HITO_CUALITATIVO (ID_HITO_CL) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CL2 foreign key (ID_META_PRODUCTO_CUALITATIVA)
      references META_PRODUCTO_CUALITATIVA (ID_META_PRODUCTO_CUALITATIVA) on delete restrict on update restrict;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CN foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CN2 foreign key (ID_META_PRODUCTO_CUANTITATIVA)
      references META_PRODUCTO_CUANTITATIVA (ID_META_PRODUCTO_CUANTITATIVA) on delete restrict on update restrict;

alter table META_PRODUCTO_CUALITATIVA add constraint FK_PRODUCTO_TIENE_META_CL foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete restrict on update restrict;

alter table META_PRODUCTO_CUANTITATIVA add constraint FK_PRODUCTO_TIENE_META_CN foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete restrict on update restrict;

alter table PRODUCTO add constraint FK_EFECTO_TIENE_PRODUCTO foreign key (ID_EFECTO)
      references EFECTO (ID_EFECTO) on delete restrict on update restrict;

alter table PRODUCTO_RECIBE_ACTIVIDAD add constraint FK_PRODUCTO_RECIBE_ACTIVIDAD foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete restrict on update restrict;

alter table PRODUCTO_RECIBE_ACTIVIDAD add constraint FK_PRODUCTO_RECIBE_ACTIVIDAD2 foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete restrict on update restrict;

alter table PROYECTO add constraint FK_INSTITUCION_TIENE_PROYECTO foreign key (ID_INSTITUCION)
      references INSTITUCION (ID_INSTITUCION) on delete restrict on update restrict;

alter table USUARIO add constraint FK_INSTITUCION_TIENE_USUARIO foreign key (ID_INSTITUCION)
      references INSTITUCION (ID_INSTITUCION) on delete restrict on update restrict;

alter table USUARIO add constraint FK_USUARIO_TIENE_ROL foreign key (ID_ROL)
      references ROL (ID_ROL) on delete restrict on update restrict;

INSERT INTO ROL (nombre_rol) VALUES ('administrador');
INSERT INTO ROL (nombre_rol) VALUES ('financiador');
INSERT INTO ROL (nombre_rol) VALUES ('coordinador');
INSERT INTO ROL (nombre_rol) VALUES ('socio');

INSERT INTO INSTITUCION (nombre_institucion, sigla_institucion, presupuesto_institucion, carpeta_institucion, activa_institucion) VALUES ('Fundación Atica', 'ATICA', '0', 'atica', '1');

INSERT INTO USUARIO (id_institucion, id_rol, nombre_usuario, apellido_paterno_usuario, apellido_materno_usuario, login_usuario, password_usuario, telefono_usuario, correo_usuario, activo_usuario) VALUES ('1', '1', 'Jose Manuel', 'Arandia', 'Luna', 'admin1', 'admin1123', '70349697', 'jose.arandia.luna@gmail.com', '1');

INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Acumulativo', 'Acumulativo');
INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Porcentaje', 'Porcentaje');
INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Promedio menor que', 'Promedio menor que');
