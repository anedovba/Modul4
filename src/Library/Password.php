<?php
namespace Library;
class Password
{
    const SALT_TEXT = 'This is some string';

    private $password;
    private $hashedPassword;
    private $salt;

    function __construct($password, $saltText = null)
    {
        $this->password = $password;
        $this->salt = md5( is_null($saltText) ? self::SALT_TEXT : $saltText );
        $this->hashedPassword = md5($this->salt . $password);
    }

    public function __toString()
    {
        return $this->hashedPassword;
    }
}