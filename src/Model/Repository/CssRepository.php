<?php
namespace Model\Repository;

use Library\EntityRepository;

class CssRepository extends EntityRepository
{


    public function find(){
        $sth=$this->pdo->query("select * from css");
        $colors=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $colors[]=$row['background-color'];
            $colors[]=$row['nav-color'];
        }
        return $colors;

    }

         public function change($color){
             $this->pdo->query("UPDATE css SET `background-color`= '$color'");
         }
    public function changeNav($color){
        $this->pdo->query("UPDATE css SET `nav-color`= '$color'");
    }
}