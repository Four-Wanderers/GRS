-- Triggers on OFFICER table

DELIMITER $$
create trigger after_insert_officer after insert on officer
FOR EACH ROW
begin
	insert into members_log(id,username,email) values(new.id,new.username,new.email);
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
create trigger after_delete_officer after delete on officer
FOR EACH ROW
begin
	update members_log set status='inactive' where id=old.id;
end $$
Delimiter ;

-- Triggers on STAFF table

DELIMITER $$
create trigger after_insert_staff after insert on staff
FOR EACH ROW
begin
	insert into members_log(id,username,email) values(new.id,new.username,new.email);
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
create trigger after_update_staff after update on staff
FOR EACH ROW
begin
	update members_log set username=new.username, email=new.email where id=old.id;
end $$
Delimiter ;

-- Triggers on GTICKETS table
DROP TRIGGER IF EXISTS before_insert_gticket;
delimiter $$
create trigger before_insert_gticket before insert on gticket
FOR EACH ROW begin
	set NEW.ticket_id = left(UUID(),8);
end $$
delimiter ;