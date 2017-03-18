<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Pagination\Pagination;
use Library\Request;
use Library\Session;
use Model\Form\MenuForm;
use Model\Menu;

class MenuController extends Controller
{
    const BOOKS_PER_PAGE = 10;
    public function indexAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $page=(int)$request->get('page',1);
        if ($page < 1)
        {
            $page=1;
        }
        $repos=$this->container->get('repository_manager')->getRepository('Menu');
        $count = $repos->countAll();
        $menu = $repos->findAllByPage($page, self::BOOKS_PER_PAGE);
        if (!$menu && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['menu'=>$menu, 'pagination'=>$pagination];
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
            ->getRepository('Menu')
        ;

        $item=$repo
            ->findById($id);

        $menu=$this
        ->container
        ->get('repository_manager')
        ->getRepository('Menu')
        ->findAll();

        //сохраняем данные в get параметры

        $request->set('fTitle','Edit Menu');
        $request->set('id',$item->getId());
        $request->set('title',$item->getTitle());
        $request->set('parent',$item->getParent());
        $request->set('parent_id',$item->getParentId());
        $request->set('menu',$menu);

        //передаем все в форму
        $form=new MenuForm($request);
        //если форму отправили (подтвердили)
        if($request->isPost())
        {
            if($form->isValid()){
                $parent=explode(' ', $request->post('parent'));
                $parent_id=$parent[1];
                $parent=$parent[0];
                $item=(new Menu())
                    ->setId($request->post('id'))
                    ->setTitle($request->post('title'))
                    ->setParent($parent)
                    ->setParentId($parent_id)
                ;
                $repo->save($item);
                Session::setFlash("Menu \"{$item->getTitle()}\" updated");
                $this->pageReload('/admin/menu');
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
            ->getRepository('Menu')
        ;
        $menu=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Menu')
            ->findAll();

        //сохраняем данные в get параметры
        $request->set('fTitle','Add Menu');
        $request->set('id','');
        $request->set('title','');
        $request->set('parent','');
        $request->set('parent_id','');
        $request->set('menu',$menu);


        $form=new MenuForm($request);
        if($request->isPost())
        {
            $parent=explode(' ', $request->post('parent'));
            $parent_id=$parent[1];
            $parent=$parent[0];
            $item=(new Menu())
                ->setId($request->post('id'))
                ->setTitle($request->post('title'))
                ->setParent($parent)
                ->setParentId($parent_id)
            ;

            $repo->saveNew($item);
            Session::setFlash("Menu \"{$item->getTitle()}\" added success");
            $this->pageReload('/admin/menu');

        }
        return $this->render('add.phtml', ['form'=>$form]);
    }

    public function deleteAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $id=$request->get('id');

        $item=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Menu')
            ->findById($id);
        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Menu')
            ->removeById($id);


        Session::setFlash("Menu \"{$item->getTitle()}\" deleted");

        $this->pageReload('/admin/menu');

    }

}