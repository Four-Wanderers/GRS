<?php
    
    abstract class AdminDAO
    {
        abstract public function getMyGrievances():array;
        abstract public function getMyStatistics():array;
        abstract public function addDept($dept_name):bool;
        abstract public function assignHOD(string $uname,string $email, string $pass,string $dept_name):bool;
        abstract public function removeHOD(string $uname):bool;
        abstract public function getGrievances($deptid, $status):array;
        abstract public function insertStudentDetails(array $rollnos,array $depts,array $email):array;
    }
?>