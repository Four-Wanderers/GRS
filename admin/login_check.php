<?php
    require_once("..\\utils\\DBConnection.php");
    
    $umail = $_POST["umail"];
    $password = $_POST["pwd"];
    $conn = (new DBConnection())->getConn();
    $result = $conn->query("SELECT * FROM ".DBConstants::$OFFICER_TABLE." WHERE (email = '$umail' or username = '$umail') and password = '".$password."'");
    session_start();
    if($row=mysqli_fetch_assoc($result))
    {
        session_destroy();
        session_start();
        $_SESSION["id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        $location = "Location: ";
        if($row['dept_id'] == null)
        {    
            $location .= "\\grs\\admin\\Dashboard.php";
        }
        else{
            $_SESSION["dept_id"] = $row["dept_id"];
            $location .= "\\grs\\HOD\\Dashboard.php";
        }
        // redirect to the desired page
        header($location);
    }
    else
    {
        $_SESSION["invalid_login"] = true;
        header("location:admin_hod_login.php");
    }
?>