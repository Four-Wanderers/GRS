<?php
session_start();
require_once "AdminDAO.php";
require_once "DBConnection.php";
require_once "Mailing.php";
require_once "DeptDAOImpl.php";

class AdminDAOImpl extends AdminDAO
{
    public function removeHOD(string $uname): bool
    {
        $conn = (new DBConnection())->getConn();
        $sql = "select id,email from ".DBConstants::$OFFICER_TABLE." where username= '$uname';";
        $ack = false;
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $to_address = $row['email'];
            $sql = "delete from ".DBConstants::$OFFICER_TABLE." where username = '$uname' ;";
            if($conn->query($sql) === true)
            {
                //setting his gtickets as unassigned
                $sql = "update ".DBConstants::$GTICKET_TABLE." set handler_id=null, time_assigned=null where handler_id =".$row['id'];
                $mail = new Mailing();
                $ack = $mail->sendMail($to_address,MailingConstants::$REMOVE_HOD_SUBJECT,MailingConstants::$REMOVE_HOD_MSG);
            }
        }
        $conn->close();
        return $ack;
    }
    public function getGrievances($dept_id, $status): array
    {
        $conn = (new DBConnection())->getConn();
        $temp = join("','",$status);
        $status_query = ((count($status) != 0) ? "where ct.status in ('$temp')" : "");

        $custom_table_1 = "(select 
                g.ticket_id 'ticket_id',
                g.title 'title',
                d.dept_name 'dept_name',
                m.username 'handler_name',
                g.year 'year',
                if(g.ticket_id in (select r.ticket_id from ".DBConstants::$REDIRECT_LOG_TABLE." r),'Redirected',IF(g.time_assigned is null, 'Unassigned', IF(g.time_completed is null, 'InProgress', 'Completed'))) 'status'
                from ".DBConstants::$DEPT_TABLE." d, ".DBConstants::$GTICKET_TABLE." g, ".DBConstants::$MEMBERS_LOG_TABLE." m 
                where d.dept_id = g.dept_id and g.handler_id = m.id and d.dept_id = '$dept_id')";
        
        $custom_table_2 = "(select 
                g.ticket_id 'ticket_id',
                g.title 'title',
                d.dept_name 'dept_name',
                (select '-') 'handler_name',
                g.year 'year',
                (select 'Unassigned') 'status'
                from ".DBConstants::$DEPT_TABLE." d, ".DBConstants::$GTICKET_TABLE." g 
                where d.dept_id = g.dept_id and g.time_assigned is null and d.dept_id = '$dept_id')";

        $custom_table = "($custom_table_1 union all $custom_table_2)";
        // echo $custom_table;

        $sql = "select * from ".$custom_table." ct ".$status_query.";";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        {
            return $result->fetch_all(MYSQLI_ASSOC);  
        }
        else
        {
            return [];
        }
    }

    public function addDept($dept_name): bool
    {
        $conn = (new DBConnection())->getConn();
        $sql = "Insert into ".DBConstants::$DEPT_TABLE."(dept_name) values('$dept_name')";
        echo "$sql";
        $result = $conn->query($sql);
        $conn->close();
        
        return $result;
    }

    public function insertStudentDetails(array $rollnos,array $depts,array $emails):array
    {
        $missing_depts = [];
        $successful_insertions = 0;
        $duplicates = 0;
        $conn = (new DBConnection())->getConn();
        $sql = "insert into ".DBConstants::$STUDENT_TABLE."(id,dept_id,email) values(?,?,?);";
        
        for($i=0;$i<count($rollnos);$i++)
        {
            $dept_id = DeptDAOImpl::getDept_id($depts[$i]);
            if($dept_id == -1)
            {
                array_push($missing_depts,$depts[$i]);
                continue;
            }
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sis',$rollnos[$i],$dept_id,$emails[$i]);
            if($stmt->execute())
                $successful_insertions++;
            else    
                $duplicates++;
        }
        $info['missing_depts']=$missing_depts;
        $info['successful_insertions'] = $successful_insertions;
        $info['duplicates'] = $duplicates;

        $conn->close();
        return $info;
    }
    
    public function assignHOD(string $uname,string $email, string $pass,string $dept_name):bool
    {
        $conn = (new DBConnection())->getConn();
        $dept_id = DeptDAOImpl::getDept_id($dept_name);
        $sql = "insert into ".DBConstants::$OFFICER_TABLE."(username,email,password,dept_id) values('$uname','$email','$pass',$dept_id);";
        $ack = $conn->query($sql);
        if($ack)
        {
            //get the id of this hod ,since we have to assign him the grievances of prev HOD
            $sql = "select id from ".DBConstants::$OFFICER_TABLE." where dept_id=$dept_id;";
            $result = $conn->query($sql);
            if($row = $result->fetch_assoc())
            {
                $sql = "update ".DBConstants::$GTICKET_TABLE." set handler_id=".$row['id'].", time_assigned=current_timestamp() where handler_id is null and dept_id=$dept_id";
                $conn->query($sql);
                $mail = new Mailing();
                $body = MailingConstants::assignHODMsg($dept_name,$uname,$pass);
                $ack = $mail->sendMail($email,MailingConstants::$ASSIGN_HOD_SUBJECT,$body);
            }
        }
        $conn->close();
        return $ack;
    }  
    public function getMyGrievances() :array
    {
        $conn = (new DBConnection())->getConn();
        $admin_id=$_SESSION['id'];
        $sql = "select ticket_id,title,dept_name,username,year,
        if(g.ticket_id in(select r.ticket_id from redirect_log r),'redirected',
        if(g.time_assigned is null,'unassigned',if(g.time_completed is null,'InProgress','Completed'))) 'status'
        from ".DBConstants::$MEMBERS_LOG_TABLE." m,".DBConstants::$DEPT_TABLE." d,"
        .DBConstants::$GTICKET_TABLE." g where m.id=g.handler_id and 
        d.dept_id=g.dept_id and g.handler_id=$admin_id";
    
        $result = $conn->query($sql);
        //var_dump( $result);
        if($result->num_rows > 0)
        {
            return $result->fetch_all(MYSQLI_ASSOC); //would return an array of rows 
        }
        else
        {
            return [];
        }
        $conn->close();
    }

    public function getMyStatistics():array
    {
        $admin_id=$_SESSION['id'];
        $conn = (new DBConnection())->getConn();
        $sql = "select count(*) 'count',
        if(g.ticket_id in(select r.ticket_id from redirect_log r),'redirected',
        if(g.time_assigned is null,'unassigned',if(g.time_completed is null,'InProgress','Completed'))) 'status'
        from ".DBConstants::$GTICKET_TABLE." g group by status";
        
        $result = $conn->query($sql);
        $conn->close();

        if($result->num_rows > 0)
        {
            return $result->fetch_all(MYSQLI_ASSOC); 
        }
        else
        {
            return [];
        }
    }
}
?>