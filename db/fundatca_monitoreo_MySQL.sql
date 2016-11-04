/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     17/10/2016 09:35:56                          */
/*==============================================================*/

/*==============================================================*/
/* Table: ACTIVIDAD                                             */
/*==============================================================*/
create table ACTIVIDAD
(
   ID_ACTIVIDAD         int not null auto_increment,
   ID_PROYECTO          int not null,
   NOMBRE_ACTIVIDAD     varchar(1024),
   DESCRIPCION_ACTIVIDAD text,
   FECHA_INICIO_ACTIVIDAD date,
   FECHA_FIN_ACTIVIDAD  date,
   PRESUPUESTO_ACTIVIDAD decimal(12,2),
   EN_EDICION_ACTIVIDAD bool,
   CONTRAPARTE_ACTIVIDAD bool,
   EN_REFORMULACION_ACTIVIDAD bool,
   GASTO_ACTIVIDAD      decimal(12,2),
   primary key (ID_ACTIVIDAD)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: ANIO                                                  */
/*==============================================================*/
create table ANIO
(
   ID_ANIO              int not null auto_increment,
   VALOR_ANIO           int,
   ACTIVO_ANIO          bool,
   primary key (ID_ANIO)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: AVANCE_HITO_CUALITATIVO                               */
/*==============================================================*/
create table AVANCE_HITO_CUALITATIVO
(
   ID_AVANCE_HITO_CL    int not null auto_increment,
   ID_HITO_CL           int,
   FECHA_AVANCE_HITO_CL date,
   TITULO_AVANCE_HITO_CL varchar(1024),
   DESCRIPCION_AVANCE_HITO_CL text,
   DOCUMENTO_AVANCE_HITO_CL varchar(128),
   APROBADO_AVANCE_HITO_CL bool,
   EN_REVISION_AVANCE_HITO_CL bool,
   COSTO_AVANCE_HITO_CL decimal(8,2),
   FECHA_REGISTRO_AVANCE_HITO_CL date,
   primary key (ID_AVANCE_HITO_CL)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: AVANCE_HITO_CUANTITATIVO                              */
/*==============================================================*/
create table AVANCE_HITO_CUANTITATIVO
(
   ID_AVANCE_HITO_CN    int not null auto_increment,
   ID_HITO_CN           int,
   CANTIDAD_AVANCE_HITO_CN decimal(9,2),
   FECHA_AVANCE_HITO_CN date,
   DESCRIPCION_AVANCE_HITO_CN text,
   APROBADO_AVANCE_HITO_CN bool,
   EN_REVISION_AVANCE_HITO_CN bool,
   FECHA_REGISTRO_AVANCE_HITO_CN date,
   COSTO_AVANCE_HITO_CN decimal(9,2),
   primary key (ID_AVANCE_HITO_CN)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: DOCUMENTO_AVANCE_HITO_CUANTITATIVO                    */
/*==============================================================*/
create table DOCUMENTO_AVANCE_HITO_CUANTITATIVO
(
   ID_DOCUMENTO_AVANCE_HITO_CN int not null auto_increment,
   ID_AVANCE_HITO_CN    int not null,
   TITULO_DOCUMENTO_AVANCE_HITO_CN varchar(1024) not null,
   DESCRIPCION_DOCUMENTO_AVANCE_HITO_CN text not null,
   ARCHIVO_DOCUMENTO_AVANCE_HITO_CN varchar(128) not null,
   primary key (ID_DOCUMENTO_AVANCE_HITO_CN)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: HITO_CUALITATIVO                                      */
/*==============================================================*/
create table HITO_CUALITATIVO
(
   ID_HITO_CL           int not null auto_increment,
   ID_ACTIVIDAD         int,
   NOMBRE_HITO_CL       varchar(1024),
   DESCRIPCION_HITO_CL  text,
   primary key (ID_HITO_CL)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: HITO_CUANTITATIVO                                     */
/*==============================================================*/
create table HITO_CUANTITATIVO
(
   ID_HITO_CN           int not null auto_increment,
   ID_ACTIVIDAD         int not null,
   NOMBRE_HITO_CN       varchar(1024),
   DESCRIPCION_HITO_CN  text,
   META_HITO_CN         numeric(8,0),
   UNIDAD_HITO_CN       varchar(32),
   primary key (ID_HITO_CN)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: INDICADOR_CUANTITATIVO                                */
/*==============================================================*/
create table INDICADOR_CUANTITATIVO
(
   ID_INDICADOR_CN      int not null auto_increment,
   ID_TIPO_INDICADOR_CN int not null,
   ID_HITO_CN           int,
   NOMBRE_INDICADOR_CN  varchar(1024) not null,
   ACEPTABLE_CN         decimal(9,2) not null,
   LIMITADO_CN          decimal(9,2) not null,
   NO_ACEPTABLE_CN      decimal(9,2) not null,
   primary key (ID_INDICADOR_CN)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: INSTITUCION                                           */
/*==============================================================*/
create table INSTITUCION
(
   ID_INSTITUCION       int not null auto_increment,
   NOMBRE_INSTITUCION   varchar(128) not null,
   SIGLA_INSTITUCION    varchar(8) not null,
   CARPETA_INSTITUCION  varchar(32),
   ACTIVA_INSTITUCION   bool not null,
   primary key (ID_INSTITUCION)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: META_ACTIVIDAD_APORTA_META_PRODUCTO_CL                */
/*==============================================================*/
create table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL
(
   ID_HITO_CL           int not null,
   ID_META_PRODUCTO_CUALITATIVA int not null,
   primary key (ID_HITO_CL, ID_META_PRODUCTO_CUALITATIVA)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: META_ACTIVIDAD_APORTA_META_PRODUCTO_CN                */
/*==============================================================*/
create table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
(
   ID_HITO_CN           int not null,
   ID_META_PRODUCTO_CUANTITATIVA int not null,
   primary key (ID_HITO_CN, ID_META_PRODUCTO_CUANTITATIVA)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: META_PRODUCTO_CUALITATIVA                             */
/*==============================================================*/
create table META_PRODUCTO_CUALITATIVA
(
   ID_META_PRODUCTO_CUALITATIVA int not null auto_increment,
   ID_PRODUCTO          int not null,
   NOMBRE_META_PRODUCTO_CUALITATIVA varchar(1024) not null,
   DESCRIPCION_META_PRODUCTO_CUALITATIVA text,
   primary key (ID_META_PRODUCTO_CUALITATIVA)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: PRODUCTO_RECIBE_ACTIVIDAD                             */
/*==============================================================*/
create table PRODUCTO_RECIBE_ACTIVIDAD
(
   ID_ACTIVIDAD         int not null,
   ID_PRODUCTO          int not null,
   primary key (ID_ACTIVIDAD, ID_PRODUCTO)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: PROYECTO                                              */
/*==============================================================*/
create table PROYECTO
(
   ID_PROYECTO          int not null auto_increment,
   ID_PROYECTO_GLOBAL   int not null,
   NOMBRE_PROYECTO      varchar(1024),
   DESCRIPCION_PROYECTO text,
   PRESUPUESTO_PROYECTO decimal(12,2),
   EN_EDICION           bool,
   CONCLUIDO            bool,
   primary key (ID_PROYECTO)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: PROYECTO_GLOBAL                                       */
/*==============================================================*/
create table PROYECTO_GLOBAL
(
   ID_PROYECTO_GLOBAL   int not null auto_increment,
   ID_INSTITUCION       int not null,
   NOMBRE_PROYECTO_GLOBAL varchar(1024),
   DESCRIPCION_PROYECTO_GLOBAL text,
   PRESUPUESTO_PROYECTO_GLOBAL decimal(12,2),
   primary key (ID_PROYECTO_GLOBAL)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: PROYECTO_TIENE_ANIO                                   */
/*==============================================================*/
create table PROYECTO_TIENE_ANIO
(
   ID_PROYECTO          int not null,
   ID_ANIO              int not null,
   primary key (ID_PROYECTO, ID_ANIO)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: ROL                                                   */
/*==============================================================*/
create table ROL
(
   ID_ROL               int not null auto_increment,
   NOMBRE_ROL           varchar(32) not null,
   primary key (ID_ROL)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: TIPO_INDICADOR_CUANTITATIVO                           */
/*==============================================================*/
create table TIPO_INDICADOR_CUANTITATIVO
(
   ID_TIPO_INDICADOR_CN int not null auto_increment,
   NOMBRE_TIPO_INDICADOR_CN varchar(128) not null,
   DESCRIPCION_TIPO_INDICADOR_CN text,
   primary key (ID_TIPO_INDICADOR_CN)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
   PASSWORD_USUARIO     varchar(1024) not null,
   TELEFONO_USUARIO     int,
   CORREO_USUARIO       varchar(64),
   ACTIVO_USUARIO       bool not null,
   primary key (ID_USUARIO)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table ACTIVIDAD add constraint FK_PROYECTO_TIENE_ACTIVIDAD foreign key (ID_PROYECTO)
      references PROYECTO (ID_PROYECTO) on delete cascade on update cascade;

alter table AVANCE_HITO_CUALITATIVO add constraint FK_HITO_CL_TIENE_AVANCE foreign key (ID_HITO_CL)
      references HITO_CUALITATIVO (ID_HITO_CL) on delete cascade on update cascade;

alter table AVANCE_HITO_CUANTITATIVO add constraint FK_HITO_CN_TIENE_AVANCE foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete cascade on update cascade;

alter table DOCUMENTO_AVANCE_HITO_CUANTITATIVO add constraint FK_AVANCE_CN_TIENE_RESPALDO foreign key (ID_AVANCE_HITO_CN)
      references AVANCE_HITO_CUANTITATIVO (ID_AVANCE_HITO_CN) on delete cascade on update cascade;

alter table EFECTO add constraint FK_PRODOC_TIENE_EFECTO foreign key (ID_PRODOC)
      references PRODOC (ID_PRODOC) on delete cascade on update cascade;

alter table HITO_CUALITATIVO add constraint FK_ACTIVIDAD_TIENE_HITO_CL foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete cascade on update cascade;

alter table HITO_CUANTITATIVO add constraint FK_ACTIVIDAD_TIENE_HITO_CN foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete cascade on update cascade;

alter table INDICADOR_CUANTITATIVO add constraint FK_HITO_TIENE_INDICADOR_OP foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete cascade on update cascade;

alter table INDICADOR_CUANTITATIVO add constraint FK_INDICADOR_OP_ES_DE_TIPO foreign key (ID_TIPO_INDICADOR_CN)
      references TIPO_INDICADOR_CUANTITATIVO (ID_TIPO_INDICADOR_CN) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CL foreign key (ID_HITO_CL)
      references HITO_CUALITATIVO (ID_HITO_CL) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CL add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CL2 foreign key (ID_META_PRODUCTO_CUALITATIVA)
      references META_PRODUCTO_CUALITATIVA (ID_META_PRODUCTO_CUALITATIVA) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CN foreign key (ID_HITO_CN)
      references HITO_CUANTITATIVO (ID_HITO_CN) on delete cascade on update cascade;

alter table META_ACTIVIDAD_APORTA_META_PRODUCTO_CN add constraint FK_META_ACTIVIDAD_APORTA_META_PRODUCTO_CN2 foreign key (ID_META_PRODUCTO_CUANTITATIVA)
      references META_PRODUCTO_CUANTITATIVA (ID_META_PRODUCTO_CUANTITATIVA) on delete cascade on update cascade;

alter table META_PRODUCTO_CUALITATIVA add constraint FK_PRODUCTO_TIENE_META_CL foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete cascade on update cascade;

alter table META_PRODUCTO_CUANTITATIVA add constraint FK_PRODUCTO_TIENE_META_CN foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete cascade on update cascade;

alter table PRODUCTO add constraint FK_EFECTO_TIENE_PRODUCTO foreign key (ID_EFECTO)
      references EFECTO (ID_EFECTO) on delete cascade on update cascade;

alter table PRODUCTO_RECIBE_ACTIVIDAD add constraint FK_PRODUCTO_RECIBE_ACTIVIDAD foreign key (ID_ACTIVIDAD)
      references ACTIVIDAD (ID_ACTIVIDAD) on delete cascade on update cascade;

alter table PRODUCTO_RECIBE_ACTIVIDAD add constraint FK_PRODUCTO_RECIBE_ACTIVIDAD2 foreign key (ID_PRODUCTO)
      references PRODUCTO (ID_PRODUCTO) on delete cascade on update cascade;

alter table PROYECTO add constraint FK_PROYECTO_GLOBAL_TIENE_PROYECTO foreign key (ID_PROYECTO_GLOBAL)
      references PROYECTO_GLOBAL (ID_PROYECTO_GLOBAL) on delete cascade on update cascade;

alter table PROYECTO_GLOBAL add constraint FK_INSTITUCION_TIENE_PROYECTO_GLOBAL foreign key (ID_INSTITUCION)
      references INSTITUCION (ID_INSTITUCION) on delete cascade on update cascade;

alter table PROYECTO_TIENE_ANIO add constraint FK_PROYECTO_TIENE_ANIO foreign key (ID_PROYECTO)
      references PROYECTO (ID_PROYECTO) on delete cascade on update cascade;

alter table PROYECTO_TIENE_ANIO add constraint FK_PROYECTO_TIENE_ANIO2 foreign key (ID_ANIO)
      references ANIO (ID_ANIO) on delete cascade on update cascade;

alter table USUARIO add constraint FK_INSTITUCION_TIENE_USUARIO foreign key (ID_INSTITUCION)
      references INSTITUCION (ID_INSTITUCION) on delete cascade on update cascade;

alter table USUARIO add constraint FK_USUARIO_TIENE_ROL foreign key (ID_ROL)
      references ROL (ID_ROL) on delete cascade on update cascade;
	  
INSERT INTO ROL (nombre_rol) VALUES ('administrador');
INSERT INTO ROL (nombre_rol) VALUES ('financiador');
INSERT INTO ROL (nombre_rol) VALUES ('coordinador');
INSERT INTO ROL (nombre_rol) VALUES ('socio');
INSERT INTO ROL (nombre_rol) VALUES ('socio observador');

INSERT INTO INSTITUCION (nombre_institucion, sigla_institucion, carpeta_institucion, activa_institucion) VALUES ('Fundación Atica', 'ATICA', 'atica', '1');

INSERT INTO USUARIO (id_institucion, id_rol, nombre_usuario, apellido_paterno_usuario, apellido_materno_usuario, login_usuario, password_usuario, telefono_usuario, correo_usuario, activo_usuario) VALUES ('1', '1', 'Jose Manuel', 'Arandia', 'Luna', 'admin1', 'admin1123', '70349697', 'jose.arandia.luna@gmail.com', '1');

INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Acumulativo', 'Acumulativo');
INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Porcentaje', 'Porcentaje');
INSERT INTO TIPO_INDICADOR_CUANTITATIVO (nombre_tipo_indicador_cn, descripcion_tipo_indicador_cn) VALUES ('Promedio menor que', 'Promedio menor que');
