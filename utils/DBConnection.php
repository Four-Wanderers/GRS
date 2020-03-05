<?php
    require "DatabaseConstants.php";

    function getConn():mysqli
    {
        $conn =  new mysqli(DBConstants::$DB_HOST,DBConstants::$DB_USER,DBConstants::$DB_PASSWORD,DBConstants::$DB_SCHEMA);
        return $conn;
    }
?>