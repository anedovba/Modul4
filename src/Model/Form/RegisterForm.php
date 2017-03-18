<?php
namespace Model\Form;
use Library\Request;
class RegisterForm
{
    public $email;
    public $password;
    public $repeatPassword;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->password = $request->post('password');
        $this->repeatPassword = $request->post('repeatPassword');
        $this->email = $request->post('email');
    }

    /**
     * @return bool
     */
    function isValid()
    {
        $res = $this->repeatPassword !== '' && $this->password !== '' && $this->email !== '';
        $res = $this->repeatPassword == $this->password && $res;
        return $res;
    }
}