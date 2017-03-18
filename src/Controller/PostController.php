<?php
namespace Controller;
use Library\Controller;
use Library\Cookie;
use Library\FormatterFactory;
use Library\Request;
use Library\Response;
use Library\Session;
use Model\Comment;


class PostController extends Controller
{
    public function indexAction(Request $request)
    {
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $count = $repos->countAll();
        $id = $request->get('id');

        if ( $id < 1) {
            $this->container->get('router')->redirect('/');
        }

        $post = $repos->find($id);
//        dump($post->getAnalitics());
//        die;
        if (!Session::has('user')&& ($post->getAnalitics()=="1"))
        {
            Session::setFlash('<a href="/login">Войдите</a>, чтобы прочитать дальше');
            $text=$post->getPost();

            $text=explode(". ", $text);

            $fullText=$text[0].". ".$text[1].". ".$text[2].". ".$text[3].". ".$text[4].". ";

            $post->setPost($fullText);
        }
        $tags=$this->findTag($post->getTag());
        $comments=$repos->findComments($id);

        $repos=$this->container->get('repository_manager')->getRepository('User');

        $users=[];
        foreach ($comments as $comment){
            $user=$repos->findById($comment->getUserId());
            $users[]=["{$comment->getId()}"=>$user];
        }

        $args=['post'=>$post, 'tags'=>$tags, 'comments'=>$comments, 'users'=>$users];

        return $this->render('index.phtml', $args);
    }

    public function findTag($tags){
        $tags = explode(", ", $tags);
        return $tags;
    }

    public function updateAction(Request $request)
    {
        $id = $request->get('id');
        $id = explode("_", $id);
        $views=$id[0];
        $postId=$id[1];
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $post = $repos->find($postId);
        $post->setViews($views);
        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Post')
            ->save($post);
        return null;
    }

    public function likeAction(Request $request){

        $id = $request->get('id');
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $comment=$repos->findCommentById($id);

        $mark=$comment->getMark();
        $mark=$mark+1;
        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Post')
            ->like($id, $mark);
        return null;
    }

    public function dislikeAction(Request $request){

        $id = $request->get('id');
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $comment=$repos->findCommentById($id);

        $mark=$comment->getMark();
        $mark=$mark-1;
        $this
            ->container
            ->get('repository_manager')
            ->getRepository('Post')
            ->like($id, $mark);
        return null;
    }
    public function commentAction(Request $request)
    {
        $format = $request->get('_format', 'json');
        $formatter = FormatterFactory::create($format);
        $repos=$this->container->get('repository_manager')->getRepository('Comment');
        $comment=new Comment();
        $comment->setMessage($request->post('message'));
        $comment->setUserId($request->post('user_id'));
        $comment->setPostId($request->post('post_id'));
        $comment->setMark(0);
        if(!($request->post('parent_id')=="null"))
        {
        $comment->setParentId($request->post('parent_id'));
        }
        $comment->setVisible($request->post('visible'));



        try{

            $repos->save($comment);
            $code=200;
            $message="Saved";
        }catch (\PDOException $e){
            $message=$e->getMessage();
            $code=500;
        }
        $response = new Response($code, $message, $formatter);

        $id=$repos->findLastId();
        $cookie=$this->container->get('cookie')->set('newComment'.$id, $id, $time = 60);
        return $response;

    }

    public function changeAction(Request $request){

        $format = $request->get('_format', 'json');
        $formatter = FormatterFactory::create($format);
        $repos=$this->container->get('repository_manager')->getRepository('Comment');
        $id= $request->post('id');
        $mess=$request->post('message');
        try{
            $repos->changeMessage($id, $mess);
            $code=200;
            $message="Saved";
        }catch (\PDOException $e){
            $message=$e->getMessage();
            $code=500;
        }
        $response = new Response($code, $message, $formatter);
        return $response;

    }




}