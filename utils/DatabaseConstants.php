<?php
    namespace grs\utils;
    
    class DBConstants
    {
        public static $DB_USER = "admin";
        public static $DB_PORT = ["3306","3307"];
        public static $DB_SCHEMA_KMIT = "kmit";
        public static $DB_PASSWORD = "pass@123";
        public static $DB_HOST = "localhost";
        public static $DEPT_TABLE = "DEPARTMENT";
        public static $ADMINHOD_TABLE = "OFFICER";
        public static $STAFF_TABLE = "STAFF";
        public static $STUDENT_TABLE = "STUDENT";
        public static $G_TICKET_TABLE = "GTICKET";
        public static $NO_DATA_FOUND_MESG = "No data found from the table.";
    }

?>