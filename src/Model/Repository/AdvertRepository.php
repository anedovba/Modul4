<?php
namespace Model\Repository;

use Library\EntityRepository;
use Model\Advert;


class AdvertRepository extends EntityRepository
{
    public function countAll(){
        $sql = 'select count(*) from advert';
        $sth=$this->pdo->query($sql);

        return $sth->fetchColumn();

    }

public function findAllByPage($page, $perPage){
    $offset = ($page - 1) * $perPage;
    $sql = "select * from advert LIMIT {$offset}, {$perPage}";
    $sth = $this->pdo->query($sql);
    $adverts = [];

    while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
        $adverts[]=$this->createAdvert($row['id'], $row['name'], $row['price'], $row['company']);
    }

    return $adverts;

}

    private function createAdvert($id, $name, $price, $company)
    {
        $advert=(new Advert())
            ->setId($id)
            ->setName($name)
            ->setPrice($price)
            ->setCompany($company)
        ;
        return $advert;
    }

    public function findAll(){

        $sth=$this->pdo->query('select * from advert');
        $adverts=[];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $adverts[]=$this->createAdvert($row['id'], $row['name'], $row['price'], $row['company']);

        }
        return $adverts;
    }

    public function find($id){
        $sth=$this->pdo->query("select * from advert where id={$id}");


        $row=$sth->fetch(\PDO::FETCH_ASSOC);

        if(!$row)
        {
            throw new \Exception('not found');
        }

        if ($row){
            $advert=$this->createAdvert($row['id'], $row['name'], $row['price'], $row['company']);
        }
        return $advert;
    }
//
    public function save(Advert $advert, $table=null)
    {
        $class=explode('\\',get_class($advert));

        $class=end($class);

        if($table==null){
            $table=strtolower($class);
        }

        if ($advert->getId()== ''){
            $query="insert INTO {$table} (id, name, price, company) VALUES (:id, :name, :price, :company)";
            $sth=$this->pdo->prepare($query);
            $sth->execute(array(
                'id'=>$advert->getId(),
                'name'=>$advert->getName(),
                'price'=>$advert->getPrice(),
                'company'=>$advert->getCompany()));
        }
        else{

        $query="update {$table} set name=:name, price=:price, company=:company where id=:id";
            $sth=$this->pdo->prepare($query);
            $sth->execute(array(
                'name'=>$advert->getName(),
                'price'=>$advert->getPrice(),
                'company'=>$advert->getCompany(),
                'id'=>$advert->getId()));
        }
    }

     public function removeById($id){
         $this->pdo->query("DELETE FROM advert WHERE id = {$id}");
     }
}