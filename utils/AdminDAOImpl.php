<?php
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
            $custom_table = "(select 
                    g.ticket_id 'ticket_id',
                    g.title 'title',
                    d.dept_name 'dept_name',
                    o.username 'handler_name',
                    g.year 'year',
                    if(g.time_assigned is null, 'Unassigned', IF(g.time_completed is null, 'InProgress', 'Completed')) 'status'
                    from ".DBConstants::$DEPT_TABLE." d, ".DBConstants::$GTICKET_TABLE." g, ".DBConstants::$OFFICER_TABLE." o 
                    where d.dept_id = g.dept_id and g.handler_id = o.id and d.dept_id = '$dept_id')";
            $sql = "select * from ".$custom_table." ct ".$status_query.";";
            $result = $conn->query($sql);

            //returns array of rows if they exist.
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
            $sql = "Insert into ".DBConstants::$DEPT_TABLE."(dept_name) values($dept_name)";
            
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
        
    }
?>