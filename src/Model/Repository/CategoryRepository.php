<?php
namespace Model\Repository;

use Library\EntityRepository;
use Model\Category;


class CategoryRepository extends EntityRepository
{
    public function countAll(){
        $sql = 'select count(*) from category';
        $sth=$this->pdo->query($sql);

        return $sth->fetchColumn();

    }

public function findAllByPage($page, $perPage){
    $offset = ($page - 1) * $perPage;
    $sql = "select * from category LIMIT {$offset}, {$perPage}";
    $sth = $this->pdo->query($sql);
    $categories = [];

    while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
        $categories[]=$this->createCategory($row['id'], $row['name']);
    }

    return $categories;

}

    private function createCategory($id, $name)
    {

        $category=(new Category())
            ->setId($id)
            ->setName($name)
                   ;
        return $category;
    }


    public function findAll(){

        $sth=$this->pdo->query('select * from category');

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $category=(new Category())
                ->setId($row['id'])
                ->setName($row['name'])
            ;

            $categorys[]=$category;
        }
        return $categorys;
    }

    public function find($id){
        $sth=$this->pdo->query("select * from category where id={$id}");


        $data=$sth->fetch(\PDO::FETCH_ASSOC);

        if(!$data)
        {
            throw new \Exception('not found');
        }

        if ($data){
            $category=(new Category())
                ->setId($data['id'])
                ->setName($data['name'])
            ;

        }
        return $category;
    }
//
    public function save(Category $category, $table=null)
    {
        $class=explode('\\',get_class($category));

        $class=end($class);

        if($table==null){
            $table=strtolower($class);
        }

        if ($category->getId()== ''){

            $name=$category->getName();
            $query="insert INTO {$table} (name) VALUES ('$name')";
            $sth=$this->pdo->query($query);
        }
        else{

        $query="update {$table} set name=:name where id=:id";
        $sth=$this->pdo->prepare($query);

        $sth->execute(array(
            'name'=>$category->getName(),
            'id'=>$category->getId()
        ));
        }
    }

     public function removeById($id){
         $this->pdo->query("DELETE FROM category WHERE id = {$id}");
     }
}