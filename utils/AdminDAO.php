<?php
    
    abstract class AdminDAO
    {
        abstract function removeHOD(string $uname):bool;
        abstract function getGrievances($deptid, $status):array;
        abstract public function addDept($dept_name):bool;
        abstract function insertStudentDetails(array $rollnos,array $depts,array $email):array;
        // abstract public function assignHOD($username,$email):bool;
    }
?>