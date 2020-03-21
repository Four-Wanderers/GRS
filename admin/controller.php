<?php

require_once "..\\utils\\DeptDAOImpl.php";
require_once "..\\utils\\AdminDAOImpl.php";


$action = strtolower($_REQUEST['action']);

if($action=='mygrievances')
{
    header("Content-Type: application/json");
    echo json_encode((new AdminDAOImpl())->getMyGrievances());
}
else if($action==='getstatis')
{
    header("Content-Type: application/json"); 
    echo json_encode((new AdminDAOImpl())->getMyStatistics());
}

else if($action == 'departments')
{
    header("Content-Type: application/json");
    
    echo json_encode((new DeptDAOImpl())->getAllDepts());
}
elseif($action == 'grievance')
{
    $dept_id = $_REQUEST['dept_id'];
    $status = $_REQUEST['status'];
    $status = explode("_", $status);
    header("Content-Type: application/json");
    echo json_encode((new AdminDAOImpl())->getGrievances($dept_id, $status));
}

elseif($action == 'removehod')
{
    header("Content-Type: text/plain");
    $uname = $_REQUEST['uname'];
    
    $ack = (new AdminDAOImpl())->removeHOD($uname);
    echo $ack;
}

elseif($action == 'add_dept')
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

else if($action == 'upload_std_csv')
{
    $file = fopen($_FILES['csv_file']['tmp_name'],"r");

    $roll_idx = $dept_idx = $mail_idx = 0;

    $header = fgetcsv($file);
    $pattern = "/([A-Za-z]*)[^A-Za-z]+([A-Za-z]*)/";
    $replace = "\\1\\2";

    foreach($header as $key => $value)
    {
        $value = strtolower(preg_replace($pattern,$replace,$value));
        
        switch($value){
            case 'department':
                $dept_idx = $key;
                break;
            case 'rollno':
                $roll_idx = $key;
                break;
            case 'email':
                $mail_idx = $key;
                break;
        }
    }

    $rollnos = [];
    $depts = [];
    $emails = [];

    while(!feof($file))
    {
        $info = fgetcsv($file);
        array_push($rollnos, $info[$roll_idx]);
        array_push($depts, $info[$dept_idx]);
        array_push($emails, $info[$mail_idx]);
    }

    $info = (new AdminDAOImpl())->insertStudentDetails($rollnos,$depts,$emails);
    echo json_encode($info);
}

function genPass(int $len):string
{
    $data = '1234567890abcdefghijklmnopqrstuvwxyzALSKDJFPQOWIEURYTMZNXBCV';
    return substr(str_shuffle($data),0,$len);
}

?>