<?php

namespace Model\Form;

use Library\Request;
class CommentForm
{

    public $message;
    public $user_id;
    public $post_id;
    public $parent_id;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->message = $request->post('message');
        $this->user_id=$request-post('user_id');
        $this->post_id=$request-post('post_id');
        $this->parent_id=$request-post('parent_id');
    }

    /**
     * @return bool
     */
    function isValid()
    {
        $res = $this->user_id !== '' && $this->post_id !== '';
        return $res;
    }
}