<?php
    
    abstract class AdminDAO
    {
        // abstract function getProfile():array;
        abstract function removeHOD(string $uname):bool;
        abstract function getGrievances($deptid, $status):array;
        // abstract public function addDep($dept_name):bool;
        
    }
?>