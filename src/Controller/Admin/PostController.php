<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\Category;
use Library\Session;
use Library\Pagination\Pagination;
use Model\Form\PostForm;
use Model\Post;

class PostController extends Controller
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
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $count = $repos->countAll();
        $posts = $repos->findAllByPage($page, self::BOOKS_PER_PAGE);
        if (!$posts && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['posts'=>$posts, 'pagination'=>$pagination];
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
            ->getRepository('Post')
            ;

        $post=$repo
            ->find($id);
        //вытягиваем с базы все категории
        $categories=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Category')
            ->findAll();


        //сохраняем данные в get параметры

        $request->set('fTitle','Edit Post');
        $request->set('id',$post->getId());
        $request->set('title',$post->getTitle());
        $request->set('post',$post->getPost());
        $request->set('date',$post->getDate());
        $request->set('views',$post->getViews());
        $request->set('analitics',$post->getAnalitics());
        $request->set('picture',$post->getPicture());
        $request->set('tag',$post->getTag());
        $request->set('categories',$categories);
        $request->set('categoryId',$post->getCategory()->getId());

        //сохраняем данные в get параметры


        //передаем все в форму
        $form=new PostForm($request);
        //если форму отправили (подтвердили)
        if($request->isPost())
        {
            if($form->isValid()){
                $key=null;
                foreach ($categories as $category){
                    if ($category->getName()===$request->post('categories')){
                        $key=$category->getId();
                    }
                }

                $category=(new Category())
                ->setId($key)
                ->setName($request->post('categories'));

                $flag=0;
                if($request->post('analitics')==='on'){
                    $flag=1;
                }

                $post=(new Post())
                    ->setId($request->post('id'))
                    ->setTitle($request->post('title'))
                    ->setPost($request->post('post'))
                    ->setDate($request->post('date'))
                    ->setViews($request->post('views'))
                    ->setCategory($category)
                    ->setAnalitics($flag)
                    ->setPicture($request->post('picture'))
                    ->setTag($request->post('tag'))
                ;

             $repo->saveAll($post);
                Session::setFlash("Post \"{$post->getTitle()}\" updated");
                $this->pageReload('/admin/post');
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
            ->getRepository('Post')
        ;

        //вытягиваем с базы все стили
        $categories=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Category')
            ->findAll();
//        dump($styles);
        //сохраняем данные в get параметры
        $request->set('fTitle','Add Post');
        $request->set('id','');
        $request->set('title','');
        $request->set('post','');
        $request->set('date','yyyy-mm-dd hh:mm:ss');
        $request->set('views','');
        $request->set('analitics','');
        $request->set('picture','');
        $request->set('tag','');
        $request->set('categories',$categories);
        $request->set('categoryId','');

        $form=new PostForm($request);
        if($request->isPost())
        {

            $key=null;
            foreach ($categories as $category){
                if ($category->getName()===$request->post('categories')){
                    $key=$category->getId();
                }
            }
            $category=(new Category())
                ->setId($key)
                ->setName($request->post('categories'));

            $flag=0;
            if($request->post('analitics')==='on'){
                $flag=1;
            }

            $post=(new Post())
                ->setId($request->post('id'))
                ->setTitle($request->post('title'))
                ->setPost($request->post('post'))
                ->setDate($request->post('date'))
                ->setViews($request->post('views'))
                ->setCategory($category)
                ->setAnalitics($flag)
                ->setPicture($request->post('picture'))
                ->setTag($request->post('tag'))
            ;

            $repo->saveAll($post);
            Session::setFlash("Post \"{$post->getTitle()}\" added success");
                $this->pageReload('/admin/post');

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