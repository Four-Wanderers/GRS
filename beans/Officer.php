<?php
    declare(strict_types = 1);
    
    require 'UserAbsBean.php';
    class Officer extends User
    {
        private $dept_name;

        function getDept_name():string
        {
            return $this->dept_name;
        }
        function setDept_name(string $dept_name)
        {
            $this->dept_name = $dept_name;
        }
    }
?>