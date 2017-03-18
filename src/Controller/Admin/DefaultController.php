<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Session;

class DefaultController extends Controller{
    public function indexAction()
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        return $this->render('index.phtml');
    }
}