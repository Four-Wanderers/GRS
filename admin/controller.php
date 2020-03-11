<?php

require_once "..\\utils\\DeptDAOImpl.php";
require_once "..\\utils\\AdminDAOImpl.php";


$action = $_REQUEST['action'];

if($action == 'departments')
{
    header("Content-Type: application/json");
    
    echo json_encode((new DeptDAOImpl())->getAllDepts());
}

elseif($action == 'removeHOD')
{
    header("Content-Type: text/plain");
    $uname = $_REQUEST['uname'];
    
    $ack = (new AdminDAOImpl())->removeHOD($uname);
    echo $ack;
}

?>