<?php
namespace grs\admin;
use grs\utils\AdminDAO as AdminDAO;
include "..\\utils\\DeptDAOImpl.php";
use grs\utils\DeptDAOImpl as DeptDAOImpl;

$action = $_REQUEST['action'];


if($action == 'departments')
{
    header("Content-Type: application/json");
    
    echo json_encode((new DeptDAOImpl())->getAllDepts());
}

elseif($action == 'removeHOD')
{
    header("Content-Type: text/plain");
    
}

elseif($action == 'removeDept')
{
    header("Content-Type: text/plain");
    
}
?>