<?php
    include "DBConnection.php";
    include "DeptDAO.php";
    
    class DeptDAOImpl extends DeptDAO
    {
        public function getAllDepts(): array
        {
            $conn = getConn();
            
            $sql = "select t1.dept_name 'dept_name',t2.username 'hod_name' from ".DBConstants::$DEPT_TABLE." t1 LEFT OUTER JOIN ".DBConstants::$ADMINHOD_TABLE." t2 on t1.dept_id = t2.dept_id";
            
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
        
    }
?>