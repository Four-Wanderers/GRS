<?php
    declare(strict_types = 1);

    require 'UserAbsBean.php';

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