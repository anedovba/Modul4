<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\Category;
use Library\Session;
use Library\Pagination\Pagination;
use Model\Comment;
use Model\Form\CommentsForm;
use Model\Form\PostForm;
use Model\Post;

class CommentController extends Controller
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
        $repos=$this->container->get('repository_manager')->getRepository('Comment');
        $count = $repos->countAll();
        $comments = $repos->findAllByPage($page, self::BOOKS_PER_PAGE);
        if (!$comments && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['comments'=>$comments, 'pagination'=>$pagination];
        return $this->render('index.phtml', $args);
    }


    public function editAction(Request $request)
    {
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }

        $id=$request->get('id');

          $repo=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Comment')
            ;

        $post=$repo
            ->find($id);

        //сохраняем данные в get параметры

        $request->set('fTitle','Edit Comment');
        $request->set('id',$post->getId());
        $request->set('message',$post->getMessage());
        $request->set('date',$post->getDate());
        $request->set('mark',$post->getMark());
        $request->set('visible',$post->getVisible());

        //передаем все в форму
        $form=new CommentsForm($request);
        //если форму отправили (подтвердили)
        if($request->isPost())
        {

            if($form->isValid()){
                $flag=0;
                if($request->post('visible')==='on'){
                    $flag=1;
                }


                $comment=(new Comment())
                    ->setId($request->post('id'))
                    ->setMessage($request->post('message'))
                    ->setDate($request->post('date'))
                    ->setMark($request->post('mark'))
                    ->setVisible($flag)
                ;

             $repo->saveUpdate($comment);
                Session::setFlash("Comment \"{$post->getMessage()}\" updated");
                $this->pageReload('/admin/comment');
            }
            Session::setFlash('Fill the fields');
        }
        //открываем форму

        return $this->render('edit.phtml', ['form'=>$form]);
    }

    public function approveAction(Request $request)
    {
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $page=(int)$request->get('page',1);
        if ($page < 1)
        {
            $page=1;
        }
        $repo=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Comment')
        ;
        $count = $repo->countAllNotActive();
        $comments=$repo->findNotActiveByPage($page, self::BOOKS_PER_PAGE);

        if (!$comments && $count) {
            $this->container->get('router')->redirect('/admin');
        }
        //paginator
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['comments'=>$comments, 'pagination'=>$pagination];
        return $this->render('approve.phtml', $args);
    }

    public function checkAction(Request $request){
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }

        $id=$request->get('id');

        $comment=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Comment')
            ->find($id);

        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Comment')
            ->approveById($id);

        Session::setFlash("Comment \"{$comment->getMessage()}\" approved");

        $this->pageReload('/admin/comment/approve');

    }


    public function deleteAction(Request $request)
    {
        //проверка что мы залогинены
        if(!Session::has('admin')){
            $this->container->get('router')->redirect('/login');
        }
        $id=$request->get('id');

        $comment=$this
            ->container
            ->get('repository_manager')
            ->getRepository('Comment')
            ->find($id);

        $this
        ->container
        ->get('repository_manager')
        ->getRepository('Comment')
        ->removeById($id);


        Session::setFlash("Comment \"{$comment->getMessage()}\" deleted");

        $this->pageReload('/admin/comment');

    }
}