<?php
namespace Library\Pagination;
class Button
{
    public $page;
    public $text;
    public $isActive;
    public $current;
    public function __construct($page, $isActive = true, $text = null, $current=false)
    {
        $this->page = $page;
        $this->text = is_null($text) ? $page : $text;
        $this->isActive = $isActive;
        $this->current=$current;
    }
    public function activate()
    {
        $this->isActive = true;
    }
    public function deactivate()
    {
        $this->isActive = false;
    }
    public function actCurrent()
    {
        $this->current = true;
    }
    public function deactCurrent()
    {
        $this->current = false;
    }

}