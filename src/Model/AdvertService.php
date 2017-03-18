<?php
namespace Model;
use Library\Config;
use Library\DbConnection;
use Library\EntityRepository;

class AdvertService
{
    public function findAll(){

        $config=new Config();
        $pdo = (new DbConnection($config))->getPDO();
        $query="select * from advert";
        $sth=$pdo->query($query);

        $adverts=[];
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
}