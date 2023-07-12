
rt file for user FILEVALIDATOR       --
-- Created by sdo90 on 06/02/2013, 14:55:26 --
----------------------------------------------

spool filevalidator.log

prompt
prompt Creating table ALLOWEDUSERS
prompt ===========================
prompt
create table FILEVALIDATOR.ALLOWEDUSERS
(
  id        INTEGER not null,
  userid    VARCHAR2(50) not null,
  admin     INTEGER,
  email     VARCHAR2(100) not null,
  timestamp TIMESTAMP(6) default CURRENT_TIMESTAMP
)
tablespace USERS_AUTO_01
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FILEVALIDATOR.ALLOWEDUSERS
  add primary key (ID)
  using index 
  tablespace USERS_AUTO_01
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table APPROVALTABLE
prompt ============================
prompt
create table FILEVALIDATOR.APPROVALTABLE
(
  id             INTEGER not null,
  userid         VARCHAR2(50),
  runtime        VARCHAR2(50),
  appadmin       VARCHAR2(50),
  filename       VARCHAR2(100),
  resultlocation VARCHAR2(100),
  status         VARCHAR2(2),
  comments       CLOB,
  timestamp      DATE default (sysdate),
  linemanager    VARCHAR2(100),
  oldtime        VARCHAR2(10)
)
tablespace USERS_AUTO_01
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FILEVALIDATOR.APPROVALTABLE
  add primary key (ID)
  using index 
  tablespace USERS_AUTO_01
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table BATCHFILES
prompt =========================
prompt
create table FILEVALIDATOR.BATCHFILES
(
  id                   INTEGER not null,
  bussinessname        VARCHAR2(50) not null,
  batchname            VARCHAR2(50) not null,
  fileoutputname       VARCHAR2(100) not null,
  expectednumberfields INTEGER not null,
  requiredfields       VARCHAR2(100),
  postcodefields       VARCHAR2(100),
  telephonefields      VARCHAR2(100),
  emailfields          VARCHAR2(100),
  examplefile          CLOB,
  completed            INTEGER not null,
  examplefilename      VARCHAR2(100),
  outputfilelocation   VARCHAR2(100),
  specialrules         VARCHAR2(50),
  digitonly            VARCHAR2(100),
  propertyname         VARCHAR2(100),
  street               VARCHAR2(100),
  town                 VARCHAR2(100)
)
tablespace USERS_AUTO_01
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FILEVALIDATOR.BATCHFILES
  add primary key (ID)
  using index 
  tablespace USERS_AUTO_01
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table UPLOADLOG
prompt ========================
prompt
create table FILEVALIDATOR.UPLOADLOG
(
  id           INTEGER not null,
  userid       VARCHAR2(50) not null,
  timestamp    TIMESTAMP(6) default CURRENT_TIMESTAMP,
  filename     VARCHAR2(100) not null,
  errormessage CLOB not null,
  batchid      INTEGER not null,
  validfile    VARCHAR2(1),
  qas          CLOB
)
tablespace USERS_AUTO_01
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FILEVALIDATOR.UPLOADLOG
  add primary key (ID)
  using index 
  tablespace USERS_AUTO_01
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FILEVALIDATOR.UPLOADLOG
  add foreign key (BATCHID)
  references FILEVALIDATOR.BATCHFILES (ID);

prompt
prompt Creating sequence ALLOWEDUSERS_SEQ
prompt ==================================
prompt
create sequence FILEVALIDATOR.ALLOWEDUSERS_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 61
increment by 1
cache 20;

prompt
prompt Creating sequence APPROVALTABLE_SEQ
prompt ===================================
prompt
create sequence FILEVALIDATOR.APPROVALTABLE_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 21
increment by 1
cache 20;

prompt
prompt Creating sequence APPROVAL_SEQ
prompt ==============================
prompt
create sequence FILEVALIDATOR.APPROVAL_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 1
increment by 1
cache 20;

prompt
prompt Creating sequence BATCHFILES_SEQ
prompt ================================
prompt
create sequence FILEVALIDATOR.BATCHFILES_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 41
increment by 1
cache 20;

prompt
prompt Creating sequence UPLOADLOG_SEQ
prompt ===============================
prompt
create sequence FILEVALIDATOR.UPLOADLOG_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 21
increment by 1
cache 20;

prompt
prompt Creating sequence USERS_SEQ
prompt ===========================
prompt
create sequence FILEVALIDATOR.USERS_SEQ
minvalue 1
maxvalue 9999999999999999999999999999
start with 1
increment by 1
cache 20;

prompt
prompt Creating trigger ALLOWEDUSERS_TRIGGER_AUTOINC
prompt =============================================
prompt
create or replace trigger FILEVALIDATOR.allowedusers_trigger_autoinc
before insert on allowedusers
for each row
begin
select allowedusers_seq.nextval into :new.id from dual;
end;
/

prompt
prompt Creating trigger APPROVALTABLE_TRIGGER_AUTOINC
prompt ==============================================
prompt
create or replace trigger FILEVALIDATOR.approvaltable_trigger_autoinc
before insert on approvaltable
for each row
begin
select approvaltable_seq.nextval into :new.id from dual;
end;
/

prompt
prompt Creating trigger BATCHFILES_TRIGGER_AUTOINC
prompt ===========================================
prompt
create or replace trigger FILEVALIDATOR.batchfiles_trigger_autoinc
before insert on batchfiles
for each row
begin
select batchfiles_seq.nextval into :new.id from dual;
end;
/

prompt
prompt Creating trigger UPLOADLOG_TRIGGER_AUTOINC
prompt ==========================================
prompt
create or replace trigger FILEVALIDATOR.uploadlog_trigger_autoinc
before insert on uploadlog
for each row
begin
select uploadlog_seq.nextval into :new.id from dual;
end;
/


spool off

