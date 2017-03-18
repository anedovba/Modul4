<?php

namespace Library;

class MetaHelper
{
    private $title;
    private $description;

    public function __construct(Config $config)
    {

//        $this->title=$config->defaultTitle;
        $this->title="Default title";
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title=$title;
    }

    public function addToTitle($str)
    {
        $this->title.=" | {$str}";
    }
}