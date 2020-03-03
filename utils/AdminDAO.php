<?php
    namespace grs\utils;
    
    // require '..\\beans\\AdminHodBean.php';
    use grs\beans\AdminHodBean as AdminHodBean;
    
    abstract class AdminDAO
    {
        abstract function getProfile():AdminHodBean;
        
        // abstract public function addDep($dept_name):bool;
        abstract public function removeDept($dept_name):bool;
    }
?>