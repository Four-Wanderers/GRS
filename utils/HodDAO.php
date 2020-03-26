<?php
    
    abstract class HodDAO
    {
        abstract function getStaffs($deptid):array; // {id, username, year}
        abstract function addStaff($email,$year,$username,$pwd,$dept_id):bool;
        abstract function removeStaff($staffId):bool;
        abstract function getAllGrievances($hod_id):array;
        abstract function getUnassignedGriev($deptid):array; // {ticketid, title, year [,status..]}
        abstract function assignGrievance($ticket_id, $handler_id):bool;
        abstract function getGrievances(int $dept_id,string $staff_id,array $status):array;
        abstract function getStats(int $dept_id, string $staff_id='all'):array;
    }
?>