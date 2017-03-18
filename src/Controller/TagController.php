<?php
namespace Controller;
use Library\Controller;
use Library\FormatterFactory;
use Library\Request;
use Library\Response;

class TagController extends Controller
{

    public function indexAction(Request $request)
    {
        $repos=$this->container->get('repository_manager')->getRepository('Tag');

        $tag = $request->get('id');
        $tag=urldecode($tag);

        $posts = $repos->find($tag);

        $args=['posts'=>$posts, 'tag'=>$tag];
        return $this->render('index.phtml', $args);

    }

    public function showAction(Request $request)
    {
        $format = $request->get('_format', 'json');
        $formatter = FormatterFactory::create($format);
        $repos=$this->container->get('repository_manager')->getRepository('Tag');
        $word= $request->post('search');
        $word=urldecode($word);

        if($word!=''){
            $repos=$repos->search($word);
        }
        else{
            $repos="Введите запрос";
        }

        try{

            $code=200;
            $message=$repos;
        }catch (\PDOException $e){
            $message=$e->getMessage();
            $code=500;
        }
        $response = new Response($code, $message, $formatter);

//        $id=$repos->findLastId();
        return $response;

    }


}