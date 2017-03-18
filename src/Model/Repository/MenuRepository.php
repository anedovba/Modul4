<?php
namespace Model\Repository;

use Library\EntityRepository;
use Model\Menu;

class MenuRepository extends EntityRepository
{
    public function countAll(){
        $sql = 'select count(*) from menu';
        $sth=$this->pdo->query($sql);

        return $sth->fetchColumn();
    }

    public function findAllByPage($page, $perPage){
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT m1.id, m1.title as title, m2.title as parent, m1.parent_id FROM `menu` m1 LEFT JOIN `menu` m2 on m2.id=m1.parent_id LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $menu = [];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $menu[]=$this->createMenu($row['id'], $row['title'], $row['parent'], $row['parent_id']);
        }

        return $menu;

    }

    private function createMenu($id, $title, $parent, $parent_id)
    {
        $item=(new Menu())
            ->setId($id)
            ->setTitle($title)
            ->setParent($parent)
            ->setParentId($parent_id)
        ;
        return $item;
    }

    public function findById($id){
        $sth=$this->pdo->query("SELECT m1.id, m1.title as title, m2.title as parent, m1.parent_id FROM `menu` m1 LEFT JOIN `menu` m2 on m2.id=m1.parent_id WHERE m1.id={$id}");

        $row=$sth->fetch(\PDO::FETCH_ASSOC);

        if(!$row)
        {
            throw new \Exception('not found');
        }

        if ($row){
            $item=$this->createMenu($row['id'], $row['title'], $row['parent'], $row['parent_id']);
        }
        return $item;
    }

    public function findAll(){

        $sth=$this->pdo->query('SELECT m1.id, m1.title as title, m2.title as parent, m1.parent_id  FROM `menu` m1 LEFT JOIN `menu` m2 on m2.id=m1.parent_id');
        $menu=[];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $menu[]=$this->createMenu($row['id'], $row['title'], $row['parent'], $row['parent_id']);

        }
        return $menu;
    }

    public function save(Menu $item)
    {
            $query="update menu set title=:title, parent_id=:parent_id where id=:id";
            $sth=$this->pdo->prepare($query);
            $sth->execute(array(
                'title'=>$item->getTitle(),
                'parent_id'=>$item->getParentId(),
                'id'=>$item->getId()));
    }

    public function saveNew(Menu $item)
    {

            $query="insert INTO menu (id, title, parent_id) VALUES (:id, :title, :parent_id)";
            $sth=$this->pdo->prepare($query);
            $sth->execute(array(
                'id'=>$item->getId(),
                'title'=>$item->getTitle(),
                'parent_id'=>$item->getParentId()
                ));
    }
    public function removeById($id){
        $this->pdo->query("DELETE FROM menu WHERE id = {$id}");
    }

    public function find(){
        $sth=$this->pdo->query("select * from menu");
        $items=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            if(empty($items[$row['parent_id']])) {
                $items[$row['parent_id']]=[];
            }
            $items[$row['parent_id']][] = $row;
        }
        return $items;
    }


}