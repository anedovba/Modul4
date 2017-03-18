<?php

namespace Model\Form;

use Library\Request;
class CommentsForm
{
    public $fTitle;
    public $id;
    public $message;
    public $date;
    public $mark;
    public $visible;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fTitle = $request->get('fTitle');
        $this->id = $request->get('id');
        $this->message = $request->get('message');
        $this->date=$request->get('date');
        $this->mark=$request->get('mark');
        $this->visible=$request->get('visible');
    }

    /**
     * @return bool
     */
    function isValid()
    {
        $res = $this->message !== '' && $this->mark !== '';
        return $res;
    }
}