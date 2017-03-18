<?php

namespace Model\Form;

use Library\Request;
class CategoryForm
{
    public $fTitle;
    public $id;
    public $name;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fTitle=$request->get('fTitle');
        $this->id = $request->get('id');
        $this->name = $request->get('name');

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