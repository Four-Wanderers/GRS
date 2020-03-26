<?php
    session_start();
    require_once '..\\utils\\HodDAOImpl.php';
    
    $hod_id = $_SESSION["id"];
    $action = $_REQUEST['action'];
    $dept_id = $_SESSION['dept_id'];
    
    function genPwd($length)
    {
        $data = '0132456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pwd=substr(str_shuffle($data),0,$length);
        return $pwd;
    }
    
    if($action=="allgrievaces")
    {
        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->getAllGrievances($hod_id));
    }
    elseif($action == 'grievance'){
        $status = explode("_",$_REQUEST['status']);
        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->getGrievances($dept_id, $_REQUEST['staff_id'], $status));
    }
    elseif($action == 'getStaffs')
    {
        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->getStaffs($dept_id));
    }
    elseif($action == 'unassignedGriev')
    {
        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->getUnassignedGriev($dept_id));
    }
    elseif($action == 'assignGriev'){
        $handler_id = $_REQUEST["handler_id"];
        $ticket_id = $_REQUEST["ticket_id"];

        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->assignGrievance($ticket_id, $handler_id));
    }
    else if($action=='removeStaff')
    {
        $id=$_REQUEST['staffId'];
        header("Content-Type: text/plain");
        echo (new HodDAOImpl())->removeStaff($id);
    }
    else if($action=='addStaff')
    {
        $email = $_REQUEST['email'];
        $year = $_REQUEST['year'];
        $username_arr = explode('@',$email);
        $username = $username_arr[0];
        $pwd = genPwd(10);

        header("Content-Type: application/json");
        echo json_encode((new HodDAOImpl())->addStaff($email,$year,$username,$pwd,$dept_id));
    }
?>