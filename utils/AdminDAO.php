<?php
    
    abstract class AdminDAO
    {
        abstract function removeHOD(string $uname):bool;
        abstract public function addDept($dept_name):bool;
        // abstract public function assignHOD($username,$email):bool;
        abstract function insertStudentDetails(array $rollnos,array $depts,array $email):array;
    }
?>