use kmit;
desc officer;

-- this alter is intended to remove not null constraint from the below three columns
-- alter table officer
-- modify column username varchar(20),
-- modify column password varchar(20),
-- modify column email varchar(20);

-- desc gticket;

-- removing the Foreign keys for handler_id as found that a column cannot have more than one fks referring to column in different tables
-- alter table gticket
-- drop foreign key fk_handler_id_staff;

-- alter table gticket
-- drop foreign key fk_handler_id_officer;

-- using check with function to implement fks

DROP TRIGGER IF EXISTS before_insert_gticket;
delimiter $$
create trigger before_insert_gticket before insert on gticket
FOR EACH ROW begin
	set NEW.ticket_id = left(UUID(),8);
end $$
delimiter ;


-- this alter is intended to change the upper limit of email length
alter table officer
modify email varchar(100);

alter table staff
modify email varchar(100) not null;

alter table student
modify email varchar(100) not null;

/*
creating members_log table
*/

create table members_log(
id int not null,
username varchar(20),
email varchar(100),
status varchar(10),
constraint pk_id primary key (id),
constraint chk_status check (status in ('active','inactive'))
);

create table redirect_log(
ticket_id varchar(10) not null,
from_id int not null,
old_assignment_date timestamp not null,

constraint fk_from_id foreign key (from_id) references members_log(id)
);

alter table gticket
add constraint fk_handler_id foreign key (handler_id) references members_log(id);

alter table members_log
alter status set default 'active';

-- triggers on officer and staff

DELIMITER $$
create trigger after_insert_officer after insert on officer
FOR EACH ROW
begin
	insert into members_log(id,username,email) values(new.id,new.username,new.email);
end $$
Delimiter ;

DELIMITER $$
create trigger after_insert_staff after insert on staff
FOR EACH ROW
begin
	insert into members_log(id,username,email) values(new.id,new.username,new.email);
end $$
Delimiter ;

DELIMITER $$
create trigger after_delete_officer after delete on officer
FOR EACH ROW
begin
	update members_log set status='inactive' where id=old.id;
end $$
Delimiter ;

DELIMITER $$
create trigger after_delete_staff after delete on staff
FOR EACH ROW
begin
	update members_log set status='inactive' where id=old.id;
end $$
Delimiter ;

DELIMITER $$
create trigger after_update_officer after update on officer
FOR EACH ROW
begin
	update members_log set username=new.username, email=new.email where id=old.id;
end $$
Delimiter ;

DELIMITER $$
create trigger after_update_staff after update on staff
FOR EACH ROW
begin
	update members_log set username=new.username, email=new.email where id=old.id;
end $$
Delimiter ;

alter table staff
modify mgr_id int; -- to remove not null constraint

alter table staff
add column dept_id int, -- this would be required when HOD is removed
add constraint fk_dept_id_staff foreign key(dept_id) references Department(dept_id) on delete cascade;

alter table staff
drop foreign key fk_mgr_id;

alter table staff
add constraint fk_mgr_id foreign key(mgr_id) references officer(id) on delete set null;
