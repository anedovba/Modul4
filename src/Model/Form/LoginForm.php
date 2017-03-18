<?php

namespace Model\Form;

use Library\Request;
class LoginForm
{
    public $email;
    public $password;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->email = $request->post('email');
        $this->password = $request->post('password');
    }

    /**
     * @return bool
     */
    function isValid()
    {
        $res = $this->email !== '' && $this->password !== '';
        return $res;
    }
}