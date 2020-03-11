<?php
    require "AdminDAO.php";
    require_once "DBConnection.php";
    
    class AdminDAOImpl extends AdminDAO
    {
        public function removeHOD(string $uname): bool
        {
            $conn = getConn();
            $sql = "select email from ".DBConstants::$OFFICER_TABLE." where username= '$uname';";

            $result = $conn->query($sql);
            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                echo implode($row);
                $to_email = $row['email'];
                $sql = "update ".DBConstants::$OFFICER_TABLE." set username=null, password = null, email = null where username = '$uname' ;";
                if($conn->query($sql) === true)
                {
                    //mail hod
                    $conn->close();
                    return true;
                }
            }
            $conn->close();
            return false;   
        }
        public function getGrievances($dept_id, $status): array
        {
            $conn = getConn();
            // var_dump($status);
            $temp = join("','",$status);
            $status_query = ((count($status) != 0) ? "where ct.status in ('$temp')" : "");
            // $status_query = "";

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
            // print $sql;
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
    }
?>