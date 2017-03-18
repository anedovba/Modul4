<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Library\Session;

class CssController extends Controller
{
    public function editAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        return $this->render('edit.phtml');
    }

    public function editbAction(Request $request)
    {

        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $color=$request->get('id');

        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Css')
            ->change($color);

    }

    public function editNavAction(Request $request)
    {

        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $color=$request->get('id');

        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Css')
            ->changeNav($color);

    }

}