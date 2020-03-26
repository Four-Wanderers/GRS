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
    
    switch($action){
        case "allgrievaces":{
            
            echo json_encode((new HodDAOImpl())->getAllGrievances($hod_id));
            break;
        }
        case 'customstats':{
            $staff_id = $_REQUEST['staff_id'];
            
            echo json_encode((new HodDAOImpl())->getStats($dept_id, $staff_id));
            break;
        }
        case 'totalstats':{
            
            echo json_encode((new HodDAOImpl())->getStats($dept_id));
            break;
        }
        case 'grievance':{
            $status = explode("_",$_REQUEST['status']);
            
            echo json_encode((new HodDAOImpl())->getGrievances($dept_id, $_REQUEST['staff_id'], $status));
            break;
        }
        case 'getStaffs':{
            
            echo json_encode((new HodDAOImpl())->getStaffs($dept_id));
            break;
        }
        case 'unassignedGriev':{
            
            echo json_encode((new HodDAOImpl())->getUnassignedGriev($dept_id));
            break;
        }
        case 'assignGriev':{
            $handler_id = $_REQUEST["handler_id"];
            $ticket_id = $_REQUEST["ticket_id"];
            
            echo json_encode((new HodDAOImpl())->assignGrievance($ticket_id, $handler_id));
            break;
        }
        case 'removeStaff':{
            $id=$_REQUEST['staffId'];
            
            echo (new HodDAOImpl())->removeStaff($id);
            break;
        }
        case 'addStaff':{
            $email = $_REQUEST['email'];
            $year = $_REQUEST['year'];
            $username_arr = explode('@',$email);
            $username = $username_arr[0];
            $pwd = genPwd(10);
    
            echo json_encode((new HodDAOImpl())->addStaff($email,$year,$username,$pwd,$dept_id));
            break;  
        }
    }
?>