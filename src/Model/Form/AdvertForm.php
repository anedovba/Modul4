<?php

namespace Model\Form;

use Library\Request;
class AdvertForm
{
    public $fTitle;
    public $id;
    public $name;
    public $price;
    public $company;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fTitle=$request->get('fTitle');
        $this->id = $request->get('id');
        $this->name = $request->get('name');
        $this->price = $request->get('price');
        $this->company = $request->get('company');

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