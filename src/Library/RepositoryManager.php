<?php

namespace Library;

class RepositoryManager extends EntityRepository
{
    private $repositories=array();

    public function getRepository($entity)
    {
        if(empty($this->repositories[$entity])){
           $repository="\\Model\\Repository\\{$entity}Repository";
            $repository=new $repository();
            $repository->setPDO($this->pdo);
            $this->repositories[$entity]=$repository;
        }
        return $this->repositories[$entity];
    }
}