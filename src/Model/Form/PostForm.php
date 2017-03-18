<?php

namespace Model\Form;

use Library\Request;
class PostForm
{
    public $fTitle;
    public $id;
    public $title;
    public $post;
    public $date;
    public $views;
    public $analitics;
    public $picture;
    public $tag;
    public $categories;
    public $categoryId;



    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fTitle=$request->get('fTitle');
        $this->id = $request->get('id');
        $this->title = $request->get('title');
        $this->post = $request->get('post');
        $this->date = $request->get('date');
        $this->views = $request->get('views');
        $this->analitics = $request->get('analitics');
        $this->picture = $request->get('picture');
        $this->tag = $request->get('tag');
        $this->categories = $request->get('categories');
        $this->categoryId = $request->get('categoryId');

    }

    /**
     * @return bool
     */

    function isValid()
    {
        $res = $this->id !== '';
        return $res;
    }
}