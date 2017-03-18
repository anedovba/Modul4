<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\Category;
use Model\Form\CategoryForm;
use Library\Session;
use Library\Pagination\Pagination;

class CategoryController extends Controller
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
        $repos=$this->container->get('repository_manager')->getRepository('Category');
        $count = $repos->countAll();
        $categories = $repos->findAllByPage($page, self::BOOKS_PER_PAGE);
        if (!$categories && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['categories'=>$categories, 'pagination'=>$pagination];
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
            ->getRepository('Category')
            ;

        $category=$repo
            ->find($id);


        //сохраняем данные в get параметры

        $request->set('fTitle','Edit Category');
        $request->set('id',$category->getId());
        $request->set('name',$category->getName());

        //передаем все в форму
        $form=new CategoryForm($request);
        //если форму отправили (подтвердили)
        if($request->isPost())
        {
            if($form->isValid()){
                $category=(new Category())
                    ->setId($request->post('id'))
                    ->setName($request->post('name'))
                ;

             $repo->save($category);
                Session::setFlash("Category \"{$category->getName()}\" updated");
                $this->pageReload('/admin/category');
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
            ->getRepository('Category')
        ;

        //сохраняем данные в get параметры
        $request->set('fTitle','Add Category');
        $request->set('id','');
        $request->set('name','');


        $form=new CategoryForm($request);
        if($request->isPost())
        {
                $category=(new Category())
                    ->setId($request->post('id'))
                    ->setName($request->post('name'))
                ;

                $repo->save($category);
                Session::setFlash("Category \"{$category->getName()}\" added success");
                $this->pageReload('/admin/category');

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

        $category=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Category')
            ->find($id);
        $this
        ->container
        ->get('repository_manager')
        ->getRepository('Category')
        ->removeById($id);


        Session::setFlash("Category \"{$category->getName()}\" deleted");

        $this->pageReload('/admin/category');

    }
}