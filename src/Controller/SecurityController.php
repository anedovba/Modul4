<?php

namespace Controller;

use Library\Controller;
use Library\Password;
use Library\Request;
use Library\Session;
use Model\Form\LoginForm;
use Model\Form\RegisterForm;



class SecurityController extends Controller
{
    /**
     * @param Request $request
     */
    public function loginAction(Request $request)
    {
        $form= new LoginForm($request);//что б залогиниться надо юзер репозитори и модель юзера
        if($request->isPost()){
            if($form->isValid()){
                $password = new Password($form->password);
                $email = $form->email;

                $repos=$this->container->get('repository_manager')->getRepository('User');
                if($user=$repos->find($email,$password)){
                    //log
                    $this->container->get('logger')->addInfo('Successfull login',['user'=>$user->getEmail()]);
//                    dump($user);
//                    die;
                    if($user->getRole()=='ROLE_USER'){

                    Session::set('user', $user->getEmail());
                    Session::set('user_id', $user->getId());
                    Session::setFlash('Добро пожаловать');

//                    Router::redirect('/');
                    $this->pageReload('/');}
                    elseif($user->getRole()=='ROLE_ADMIN'){
                        Session::set('admin', $user->getEmail());
                        Session::set('admin_id', $user->getId());
                        Session::set('user', $user->getEmail());
                        Session::set('user_id', $user->getId());
                        Session::setFlash('Добро пожаловать, удачно поадминить)');
                        $this->pageReload('/admin');
                    }
                }
                Session::setFlash('Fail. No user found');
//                Router::redirect('/index.php?route=security/login');
                // todo: make helpers like $this->redirect('/blah'), $this->pageReload() DONE
//                $this->container->get('router')->redirect('/login');
                $this->pageReload('/login');
            }
            Session::setFlash('Fill the fields');
        }
        return $this->render('login.phtml', ['form'=>$form]);
    }

    public function logoutAction(Request $request)
    {
        if(Session::has('admin')){
            Session::remove('admin');
            Session::remove('user');
        }
        Session::remove('user');
        $this->pageReload('/');
    }

    public function registerAction(Request $request)
    {
        $form = new RegisterForm($request);

        if ($request->isPost()) {
            if ($form->isValid()) {
                //данные для создания и сохранения юзера
                $password = new Password($form->password);
//                $activationHash = md5(uniqid());
                $activationHash = NULL;
                $user = (new \Model\User())
                    ->setEmail($form->email)
                    ->setPassword($password)
                    ->setActivationHash($activationHash)
                ;

                $repo = $this->container->get('repository_manager')->getRepository('User');
                $repo->save($user);
//                $emailContent = "
//
//                    Go there: <a href='http://mvc/activation/{$activationHash}'>Activate</a>
//
//                ";
//                // ОТПРАВЛЯЕМ ПИСЬМО
//                mail($form->email, 'Activation', $emailContent);

//                Session::setFlash('Check your email for activation');
                Session::setFlash('Ура, вы с нами!');
                $this->container->get('router')->redirect('/');
            }

            Session::setFlash('Invalid form');
        }


        return $this->render('register.phtml', ['form' => $form]);
    }

    public function activateAction(Request $request)
    {
        $hash = $request->get('hash');
        echo ($hash);
    }
}