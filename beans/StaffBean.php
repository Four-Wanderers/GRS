<?php
    declare(strict_types = 1);

    namespace grs\beans;
    require 'UserAbsBean.php';
    use grs\beans\User as User;

    class StaffBean extends User
    {
        private $year,$mgr;
        
        function getYear():string
        {
            return $this->year;
        }
        function setYear(string $year)
        {
            $this->year = year;
        }
        
        function getMgr():string
        {
            return $this->mgr;
        }
        function setMgr(string $mgr)
        {
            $this->mgr = mgr;
        }
    }
?>