<?php

namespace Model\Form;

use Library\Request;
class RegistrationForm
{
    private $email;
    private $password;
    private $passwordConfirm;

    function __construct(Request $request)
    {
        $this->email = $request->post('email');
        $this->password = $request->post('password');
        $this->passwordConfirm = $request->post('passwordConfirm');
    }

    /**
     * @return bool
     */
    public function passwordsMatch()
    {
        return $this->password == $this->passwordConfirm;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return !empty($this->email) && !empty($this->username) && !empty($this->password) && !empty($this->passwordConfirm) && $this->passwordsMatch();
    }
}