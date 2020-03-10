<?php
    abstract class DeptDAO
    {
        abstract function getAllDepts():array; //consists of dept_name and its HOD 
        abstract static function getDept_id(string $dept_name):int;
    }
?>