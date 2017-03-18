<?php
namespace Model\Repository;

use Library\EntityRepository;
use Model\Category;
use Model\Post;


class TagRepository extends EntityRepository
{
    public function findAll(){
        $query='SELECT tag FROM posts';
        $sth=$this->pdo->query($query);
        $tags=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $tags[]=$row['tag'];
        }
        return $tags;

    }

    public function find($tag){

        $query="select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id WHERE LOCATE('{$tag}', tag)";

        $sth=$this->pdo->query($query);


        $posts=[];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }

        return $posts;
    }

    public function search($word){
        $tags=[];

        $sql=$this->pdo->query("SELECT tag FROM posts WHERE tag LIKE '%{$word}%'");
        if($sql->rowCount() > 0){
            $i = 0;
            $finds=[];
            while($row=$sql->fetch()){
                $finds[$i]['tag']=explode(', ', $row['tag']);
                $i++;
            }
            $tags_for_filter = [];
            foreach ($finds as $find) {
                foreach ($find['tag'] as $find_tag){
                    $tags_for_filter[]=$find_tag;
                }
            }
            $tags_unique = array_unique($tags_for_filter);
            $arr_tags = [];
            foreach ($tags_unique as $item){
                if(strpos($item, $word)!== false){
                    $arr_tags[]=$item;
                }
            }

            foreach ($arr_tags as $unique_tag){
                $tags[]= "<div><a href='/tag/{$unique_tag}'>" .$unique_tag. "</a></div>";
            }

    }else {
            $tags= "Нет результатов";}
        return $tags;
    }

    private function createPost($category, $name, $id, $title, $post, $date, $views, $analitics, $picture, $tag)
    {
        $category=(new Category())
            ->setId($category)
            ->setName($name)
        ;
        $post=(new Post())
            ->setId($id)
            ->setTitle($title)
            ->setPost($post)
            ->setDate($date)
            ->setViews($views)
            ->setCategory($category)
            ->setAnalitics($analitics)
            ->setPicture($picture)
            ->setTag($tag)
        ;
        return $post;
    }


}