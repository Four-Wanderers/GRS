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
                    $ack = $mail->sendMail($to_address,'Gratitude',MailingConstants::$REMOVE_HOD_MSG);
                }
            }
            $conn->close();
            return $ack;
        }

        public function addDept($dept_name): bool
        {
            $conn = (new DBConnection())->getConn();
            $sql = "Insert into ".DBConstants::$DEPT_TABLE."(dept_name) values($dept_name)";
            
            $result = $conn->query($sql);
            $conn->close();
            
            return result;
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