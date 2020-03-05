<?php
declare(strict_types = 1);
abstract class User
{
    private $id,$username,$email;
    
    function getId():int
    {
        return $this->id;
    }
    function setId(int $id)
    {
        $this->id = $id;
    }
    
    function getUsername():string
    {
        return $this->username;
    }
    function setUsername(string $username)
    {
        $this->username = username;
    }
    
    function getEmail():string
    {
        return $this->email;
    }
    function setEmail(string $email)
    {
        $this->email = email;
    }   
}
?>