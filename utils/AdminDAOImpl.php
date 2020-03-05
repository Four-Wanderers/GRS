<?php
    require "AdminDAO.php";
    require "DBConnection.php";
    
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
    }

    if((new AdminDAOImpl())->removeHOD("CSE HOD"))
        ECHO "removed";
    else
        echo "failure";
?>