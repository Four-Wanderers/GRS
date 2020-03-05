use kmit;
desc officer;

-- this alter is intended to remove not null constraint from the below three columns
alter table officer
modify column username varchar(20),
modify column password varchar(20),
modify column email varchar(20);

desc gticket;

-- removing the Foreign keys for handler_id as found that a column cannot have more than one fks referring to column in different tables
alter table gticket
drop foreign key fk_handler_id_staff;

alter table gticket
drop foreign key fk_handler_id_officer;

-- using check with function to implement fks

/*
Do no execute this...
drop function if exists isValidHandler;
delimiter $$
create function isValidHandler(handler_id int)
returns varchar(5)
reads sql data
begin
	declare result varchar(5);
	if (exists (select id from officer where id=handler_id) or exists(select id from staff where id=handler_id))
		then set result = 'true';
	else
		set result = 'false';
	end if;
    return result;
end ;
$$
Delimiter ;

ALTER TABLE gticket
add constraint chk_handler_id check (isValidHandler(handler_id) = 'true');
*/

-- This is to assign a unique value to every ticket generated using UUID() function
DROP TRIGGER IF EXISTS before_insert_gticket;
delimiter $$
create trigger before_insert_gticket before insert on gticket
FOR EACH ROW begin
	set NEW.handler_id = left(UUID(),8);
end $$
delimiter ;