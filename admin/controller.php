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

elseif($action == 'addDept')
{
    header("Content-Type: text/plain");
    $dept_name = $_REQUEST['dept_name'];
    
    $ack = (new AdminDAOImpl())->addDept($dept_name);
    echo $ack;
}

elseif($action == 'assignhod')
{
    header("Content-Type: text/plain");
    $uname = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $dept_name = $_REQUEST['dept_name'];
    
    $pass = genPass(10);

    $ack = (new AdminDAOImpl())->assignHOD($uname,$email,$pass,$dept_name);
    echo $ack;
}

function genPass(int $len):string
{
    $data = '1234567890abcdefghijklmnopqrstuvwxyzALSKDJFPQOWIEURYTMZNXBCV';
    return substr(str_shuffle($data),0,$len);
}

?>