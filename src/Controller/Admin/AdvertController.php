<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\Advert;
use Model\Form\AdvertForm;
use Library\Session;
use Library\Pagination\Pagination;

class AdvertController extends Controller
{
const BOOKS_PER_PAGE = 5;
    public function indexAction(Request $request){

        if(!Session::has('admin')){
           $this->container->get('router')->redirect('/login');
        }
        $page=(int)$request->get('page',1);
        if ($page < 1)
        {
            $page=1;
        }
        $repos=$this->container->get('repository_manager')->getRepository('Advert');
        $count = $repos->countAll();
        $adverts = $repos->findAllByPage($page, self::BOOKS_PER_PAGE);
        if (!$adverts && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['adverts'=>$adverts, 'pagination'=>$pagination];
        return $this->render('index.phtml', $args);
    }


    public function editAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }

        $id=$request->get('id');

          $repo=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Advert')
            ;

        $advert=$repo
            ->find($id);


        //сохраняем данные в get параметры

        $request->set('fTitle','Edit Advert');
        $request->set('id',$advert->getId());
        $request->set('name',$advert->getName());
        $request->set('price',$advert->getPrice());
        $request->set('company',$advert->getCompany());

        //передаем все в форму
        $form=new AdvertForm($request);
        //если форму отправили (подтвердили)
        if($request->isPost())
        {
            if($form->isValid()){
                $advert=(new Advert())
                    ->setId($request->post('id'))
                    ->setName($request->post('name'))
                    ->setPrice($request->post('price'))
                    ->setCompany($request->post('company'))
                ;

             $repo->save($advert);
                Session::setFlash("Advert \"{$advert->getName()}\" updated");
                $this->pageReload('/admin/advert');
            }
            Session::setFlash('Fill the fields');
        }
        //открываем форму

        return $this->render('edit.phtml', ['form'=>$form]);
    }

    public function addAction(Request $request)
    {

        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $repo=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Advert')
        ;

        //сохраняем данные в get параметры
        $request->set('fTitle','Add Advert');
        $request->set('id','');
        $request->set('name','');
        $request->set('price','');
        $request->set('Company','');


        $form=new AdvertForm($request);
        if($request->isPost())
        {
            $advert=(new Advert())
                ->setId($request->post('id'))
                ->setName($request->post('name'))
                ->setPrice($request->post('price'))
                ->setCompany($request->post('company'))
            ;

                $repo->save($advert);
                Session::setFlash("Advert \"{$advert->getName()}\" added success");
                $this->pageReload('/admin/advert');

        }
        return $this->render('edit.phtml', ['form'=>$form]);
    }

    public function deleteAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $id=$request->get('id');

        $advert=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Advert')
            ->find($id);
        $this
        ->container
        ->get('repository_manager')
        ->getRepository('Advert')
        ->removeById($id);


        Session::setFlash("Advert \"{$advert->getName()}\" deleted");

        $this->pageReload('/admin/advert');

    }
}