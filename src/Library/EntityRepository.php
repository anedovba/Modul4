<?php

namespace Library;

abstract class EntityRepository
{
    const SQLDATA='posts.id as id, title, post, date, views, category, analitics, picture, tag, name';

    protected $pdo;

    public function setPDO(\PDO $pdo){
        $this->pdo=$pdo;
        return $this;
    }
}