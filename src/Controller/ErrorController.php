<?php
namespace Controller;
use Library\Controller;
use Library\Request;


class ErrorController extends Controller
{
    public function errorAction(Request $request, \Exception $e)
    {
        return $this->render('error.phtml', ['e'=>$e]);
    }
}