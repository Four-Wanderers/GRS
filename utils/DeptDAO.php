<?php
    namespace grs\utils;

    abstract class DeptDAO
    {
        abstract function getAllDepts():array; //consists of dept_name and its HOD 
    }
?>