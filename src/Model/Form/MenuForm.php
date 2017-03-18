<?php

namespace Model\Form;

use Library\Request;
class MenuForm
{
    public $fTitle;
    public $id;
    public $title;
    public $parent;
    public $parent_id;
    public $menu;




    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fTitle=$request->get('fTitle');
        $this->id = $request->get('id');
        $this->title = $request->get('title');
        $this->parent = $request->get('parent');
        $this->parent_id = $request->get('parent_id');
        $this->menu = $request->get('menu');
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