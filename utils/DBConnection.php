<?php
    require_once "DatabaseConstants.php";

    class DBConnection
    {
        private $conn ;
        
        public function __construct()
        {
            global $conn;
            $conn = new mysqli(DBConstants::$DB_HOST,DBConstants::$DB_USER,DBConstants::$DB_PASSWORD,DBConstants::$DB_SCHEMA);
        }
        public function getConn()
        {
            global $conn;
            return $conn;
        }
    }
?>