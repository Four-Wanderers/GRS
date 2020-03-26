<?php
    require_once 'HodDAO.php';
    require_once 'Mailing.php';
    require_once 'DBConnection.php';
    require_once 'DatabaseConstants.php';
    class HodDAOImpl extends HodDAO
    {
        public function  getAllGrievances($hod_id) :array
        {
            $conn = (new DBConnection())->getConn();
            $sql = "select ticket_id,title,dept_name,username,year,
            if(g.ticket_id in(select r.ticket_id from redirect_log r),'redirected',
            if(g.time_assigned is null,'unassigned',if(g.time_completed is null,'InProgress','Completed'))) 'status'
            from ".DBConstants::$MEMBERS_LOG_TABLE." m,".DBConstants::$DEPT_TABLE." d,"
            .DBConstants::$GTICKET_TABLE." g where m.id=g.handler_id and
            d.dept_id=g.dept_id and g.handler_id = $hod_id";

            $result = $conn->query($sql);
        
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

        public function getStaffs($deptid): array
        {
            $conn = (new DBConnection())->getConn();
            
            $query = "select s.id, s.username, s.year from ".DBConstants::$STAFF_TABLE." s where s.dept_id = $deptid ;";
            $result = $conn->query($query);
            
            if($result->num_rows > 0)
            {
                return $result->fetch_all(MYSQLI_ASSOC);  
            }
            else
            {
                return [];
            }
        }
        
        public function getUnassignedGriev($dept_id):array
        {
            $conn = (new DBConnection())->getConn();
            
            $custom_table = "(select 
                    g.ticket_id 'ticket_id',
                    g.title 'title',
                    g.year 'year',
                    if(g.ticket_id in (select r.ticket_id from ".DBConstants::$REDIRECT_LOG_TABLE." r),'Redirected',IF(g.time_assigned is null, 'Unassigned', IF(g.time_completed is null, 'InProgress', 'Completed'))) 'status'
                    from ".DBConstants::$GTICKET_TABLE." g where g.dept_id = '$dept_id')";

            $query = "select ct.ticket_id, ct.title, ct.year from $custom_table ct where ct.status in ('Unassigned');";
            $result = $conn->query($query);
            
            if($result->num_rows > 0)
            {
                return $result->fetch_all(MYSQLI_ASSOC);  
            }
            else
            {
                return [];
            }
        }

        public function assignGrievance($ticket_id, $handler_id):bool
        {
            $ack = false;
            $conn = (new DBConnection()) -> getConn();
            // $mail = new Mailing();
            
            $query = "select * from ".DBConstants::$GTICKET_TABLE." where ticket_id = '$ticket_id'";
            $result = $conn->query($query);
            
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                if($row["handler_id"] != null)return false;
                $query = "update ".DBConstants::$GTICKET_TABLE." set handler_id = $handler_id, time_assigned = current_timestamp() where ticket_id = '$ticket_id';";
                $result = $conn -> query($query);
                // if($result){
                //     $query = "SELECT email FROM ".DBConstants::$STAFF_TABLE." where id = $handler_id";
                //     $result = $conn->query($query);
                //     if($result->num_rows > 0){
                //         $to_address = $result["email"];
                //         $ack = $mail->sendMail($to_address,$assign_subject,$assign_msg);
                //         return $ack;
                //     }

                //     $query = "SELECT email FROM ".DBConstants::$OFFICER_TABLE." where id = $handler_id";
                //     $result = $conn->query($query);
                //     if($result->num_rows > 0){
                //         $to_address = $result["email"];
                //         $ack = $mail->sendMail($to_address,$assign_subject,$assign_msg);
                //         return $ack;
                //     }
                // }
            }
            return $result;
        } 
        
        public function addStaff($email,$year,$username,$pwd,$dept_id):bool
        {
            $conn = (new DBConnection())->getConn();
            // $dept_id = $_SESSION["dept_id"];
    
            $sql = "Insert into ".DBConstants::$STAFF_TABLE."(username,password,email,year,dept_id) 
            values('$username','$pwd','$email','$year',$dept_id)";
            
            $result = $conn->query($sql);

            $mail = new Mailing();
            $body = MailingConstants::assignStaffMsg($username,$pwd);
            $ack = $mail->sendMail($email,MailingConstants::$ASSIGN_STAFF_SUBJECT,$body);
            $conn->close();
            
            return $ack;
        }

        public function removeStaff($staffId):bool
        {
            $conn = (new DBConnection())->getConn();
            $sql = "select dept_id,email from ".DBConstants::$STAFF_TABLE." where id= $staffId ;";
            $ack = false;
            $result = $conn->query($sql);
            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $to_address = $row['email'];
                $dept_id = $row['dept_id'];
                $sql = "delete from ".DBConstants::$STAFF_TABLE." where id = $staffId ;";
                if($conn->query($sql) === true)
                {
                    $sql = "update ".DBConstants::$GTICKET_TABLE." set handler_id=(select o.id from "
                    .DBConstants::$OFFICER_TABLE." o
                    where o.dept_id=$dept_id),time_assigned=current_timestamp() where handler_id = $staffId ;";
                    if($conn->query($sql) ===true)
                    {
                        $mail = new Mailing();
                        $ack = $mail->sendMail($to_address,MailingConstants::$REMOVE_SUBJECT,MailingConstants::$REMOVE_MSG);
                    }     
                }
            
            }
            $conn->close();
            
            return $ack;
        }
        function getGrievances(int $dept_id, string $staff_id, array $status): array
        {
            $conn = (new DBConnection())->getConn();
            $staff_query = ($staff_id === 'all')?"":"and g.handler_id = $staff_id";
            $temp = join("','",$status);

            $status_query = (strlen($temp) != 0) ? "where t.status in ('$temp')" : "";
            
            $inner_query = "(select g.ticket_id, g.title, g.year, m.username 'handler_name',
            if(g.ticket_id in (select r.ticket_id from ".DBConstants::$REDIRECT_LOG_TABLE." r),'redirected',
            if(g.time_completed is null,'inprogress','completed')) 'status'
            from ".DBConstants::$GTICKET_TABLE." g, ".DBConstants::$MEMBERS_LOG_TABLE." m 
            where g.handler_id = m.id and g.dept_id = $dept_id $staff_query)";
            
            $sql = "select * from $inner_query t $status_query";
            
            $result = $conn->query($sql);
            $conn->close();
            if($result->num_rows>0)
            {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            else
                return [];
        }
    }
?>
