<?php
    class MailingConstants
    {
        public static $FROM_ADDRESS = "youremail@gmail.com";
        public static $PASSWORD = "password";
        public static $ALIAS_NAME = "GRS.com";
        public static $REMOVE_HOD_SUBJECT = "Thank you for your contribution.";
        public static $REMOVE_HOD_MSG = "Thank You for all your services. Your access to the portal has now been disabled";
        public static $ASSIGN_HOD_SUBJECT = "Welcome";
        
        public static function assignHODMsg(string $dept_name,string $username,string $password):string
        {
            $msg = "Welcome to GRS.com. You have been appointed as the HOD of $dept_name department".
            " you can start responding to student grievances by logging into the portal.".
            " Your login credentials are -- Username: $username and Password: $password .";

            return $msg;
        }
    }
?>