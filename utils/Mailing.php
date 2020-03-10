<?php
require "..\\..\\PHPMailer_5.2.4\\class.phpmailer.php";
require "MailingConstants.php";
class Mailing
{
    private $mail;
    function __construct($use_alias = true)
    {
        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = 1;
        $this->mail->isSMTP();
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPAuth = true;
        $this->mail->isHtml(true);
        
        $this->mail->Username = MailingConstants::$FROM_ADDRESS;
        $this->mail->Password = MailingConstants::$PASSWORD;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = 465; //or 587
        
        if($use_alias)
            $this->mail->setFrom(MailingConstants::$FROM_ADDRESS,MailingConstants::$ALIAS_NAME);
        else
            $this->mail->setFrom(MailingConstants::$FROM_ADDRESS);
    }
    
    function sendMail(string $to_address,string $subject,string $body):bool
    {
        $this->mail->addAddress($to_address);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        
        return $this->mail->send();
    }
}
?>