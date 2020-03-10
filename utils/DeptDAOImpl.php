<?php
    require_once "DBConnection.php";
    require_once "DeptDAO.php";
    
    class DeptDAOImpl extends DeptDAO
    {
        public function getAllDepts(): array
        {
            $conn = (new DBConnection())->getConn();
            
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
        public static function getDept_id(string $dept_name):int
        {
            $conn = (new DBConnection())->getConn();
            
            $sql = "select dept_id from ".DBConstants::$DEPT_TABLE." where dept_name='$dept_name'";
            $result = $conn->query($sql);
            $dept_id = -1;
            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $dept_id = $row['dept_id'];
            }
            $conn->close();
            return $dept_id;
        }
    }
?>