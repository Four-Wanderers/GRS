<?php
    declare(strict_types = 1);
    namespace grs\beans;
    
    require 'UserAbsBean.php';
    use grs\beans\User as User;

    class AdminHodBean extends User
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