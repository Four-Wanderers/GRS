DROP DATABASE IF EXISTS kmit;


-- creating a user for easy portability of code

create user 'admin'@'localhost' identified by 'pass@123';
grant all privileges on kmit.* to 'admin'@'localhost';

-- Creating Database with college name
create database kmit;
use kmit;
-- Creating Department table
create table department(
dept_id INT AUTO_INCREMENT NOT NULL,
dept_name varchar(50) not null,
constraint PK_dept_id primary key (dept_id),
constraint UN_dept_name unique (dept_name)
) auto_increment = 1;

-- CREATING OFFICER TABLE 
create table officer(
id int auto_increment not null,
username varchar(20) not null,
password varchar(20) not null,
email varchar(50) not null,
dept_id int,
constraint UN_username unique (username),
constraint UN_email unique (email),
constraint PK_id Primary key (id),
constraint FK_dept_id foreign key (dept_id) references department(dept_id)
) auto_increment = 101;

--  CREATING STAFF TABLE
create table staff(
id int auto_increment not null,
username varchar(20) not null,
password varchar(20) not null,
email varchar(50) not null,
mgr_id int not null,
year varchar(5),
constraint UN_username unique (username),
constraint UN_email unique (email),
constraint PK_id Primary key (id),
constraint FK_mgr_id foreign key (mgr_id) references officer(id) on delete cascade,
constraint CHK_year check (year in ("I", "II", "III", "IV", "V", "ALL"))
) auto_increment = 501;

--  CREATING STUDENT TABLE
create table student(
id varchar(50) not null,
username varchar(20),
old_password varchar(20),
password varchar(20),
email varchar(50) not null,
dept_id int, -- Can later be changed to not null 
act_code int,
constraint UN_username unique (username),
constraint UN_email unique (email),
constraint PK_id Primary key (id),
constraint FK_std_dept_id foreign key (dept_id) references department(dept_id)
);

--  CREATING GTICKET TABLE
create table gticket(
ticket_id varchar(8) not null, -- Primary Key
title varchar(100) not null,
dept_id int not null, -- FK on dept
std_id varchar(50) not null, -- FK on Student
year varchar(5) not null, -- Check on year
description longtext not null, 
handler_id int, -- FK to (Staff, Officer)
time_created timestamp not null default current_timestamp(), -- Default
time_assigned timestamp, 
time_completed timestamp,
scope varchar(10) not null default "private", -- Check private or public, default private

constraint PK_ticket_id primary key(ticket_id),
constraint FK_gt_dept_id foreign key(dept_id) references department(dept_id),
constraint FK_std_id foreign key(std_id) references student(id),
constraint CHK_gt_year check (year in ("I", "II", "III", "IV", "V")),
constraint FK_handler_id_staff foreign key(handler_id) references staff(id),
constraint FK_handler_id_officer foreign key(handler_id) references officer(id),
constraint CHK_scope check (scope in ("public", "private"))
);