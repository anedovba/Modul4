<?php
namespace Model\Repository;

//use Library\DbConnection;
use Library\EntityRepository;
use Library\Session;
use Model\Comment;
use Model\Post;
use Model\Category;

/**
 * Class BookRepository
 * @package Model\Repository
 */
class PostRepository extends EntityRepository
{


    public function findAll()
    {
        $sth=$this->pdo->query('select '.self::SQLDATA.' from posts JOIN category ON posts.category=category.id ORDER by date DESC');
        $posts=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }
        return $posts;
    }


    public function findByCategory($id){

            $sth=$this->pdo->query("select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id WHERE category.id={$id} ORDER by date DESC");

            $posts=[];

            while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
                $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
            }

            return $posts;

    }



    public function findAllByPage($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id ORDER by date DESC LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $posts = [];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }

        return $posts;
    }
    public function findByCategoryByPage($id, $page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id WHERE category.id={$id} ORDER by date DESC LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $posts = [];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }

        return $posts;
    }

    public function findByAnalitics($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id WHERE analitics=1 ORDER by date DESC LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $posts = [];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }

        return $posts;
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

    public function find($id)
    {
        $query="select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id WHERE posts.id={$id}";

        $sth=$this->pdo->query($query);

        $row=$sth->fetch(\PDO::FETCH_ASSOC);

        if(!$row)
        {
            throw new \Exception('not found');
        }
        if ($row){
            $post=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }

        return $post;
    }

    public function count($id){
        $sql = "select count(*) from posts JOIN category ON posts.category=category.id WHERE posts.category={$id}";

        $sth=$this->pdo->query($sql);
        //возвращает значение
        return $sth->fetchColumn();

    }

        public function countAll(){
        $sql = 'select count(*) from posts';
        $sth=$this->pdo->query($sql);
        //возвращает значение
        return $sth->fetchColumn();
    }
    public function countAnalitics(){
        $sql = 'select count(*) from posts WHERE analitics=1';
        $sth=$this->pdo->query($sql);
        //возвращает значение
        return $sth->fetchColumn();
    }


    public function save(Post $post)
    {
        $query="update posts set views=:views where id=:id";
        $sth=$this->pdo->prepare($query);

        $sth->execute(array(
            'views'=>$post->getViews(),
            'id'=>$post->getId())
        );
    }

    public function like($id, $mark){
        $query="update comments set mark=:mark where id=:id";
        $sth=$this->pdo->prepare($query);
//        dump($mark, $id);
//        die;
        $sth->execute(array(
                'mark'=>$mark,
                'id'=>$id)
        );
    }

    public function findComments($id)
    {
        $query="select * from comments WHERE post_id={$id} and visible=1 ORDER BY mark DESC";

        $sth=$this->pdo->query($query);

        $comments=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $comments[]=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
        }
        return $comments;
    }

    public function findCommentById($id)
    {
        $query="select * from comments WHERE id={$id}";

        $sth=$this->pdo->query($query);
        $row=$sth->fetch(\PDO::FETCH_ASSOC);
        if(!$row)
        {
            throw new \Exception('not found');
        }
        if ($row){
            $comment=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
        }
        return $comment;
    }



    private function createComment($id, $message, $date, $user_id, $post_id, $mark, $parent_id, $visible)
    {

        $comment=(new Comment())
            ->setId($id)
            ->setMessage($message)
            ->setDate($date)
            ->setUserId($user_id)
            ->setPostId($post_id)
            ->setMark($mark)
            ->setParentId($parent_id)
            ->setVisible($visible)
        ;
        return $comment;
    }





    public function saveAll(Post $post)
    {
        if ($post->getId()== ''){


            $query="insert INTO posts (title, post,  category, analitics, picture, tag) VALUES (:title, :post, :category, :analitics, :picture, :tag)";
            $sth=$this->pdo->prepare($query);
            $sth->execute(array(
                    'title'=>$post->getTitle(),
                    'post'=>$post->getPost(),
                    'category'=>$post->getCategory()->getId(),
                    'analitics'=>$post->getAnalitics(),
                    'picture'=>$post->getPicture(),
                    'tag'=>$post->getTag())
            );
        }
        else {

        $query="update posts set title=:title, post=:post, date=:date, views=:views, category=:category, analitics=:analitics, picture=:picture, tag=:tag where id=:id";
        $sth=$this->pdo->prepare($query);
        $sth->execute(array(
            'title'=>$post->getTitle(),
            'post'=>$post->getPost(),
            'date'=>$post->getDate(),
            'views'=>$post->getViews(),
            'category'=>$post->getCategory()->getId(),
            'analitics'=>$post->getAnalitics(),
            'picture'=>$post->getPicture(),
            'tag'=>$post->getTag(),
            'id'=>$post->getId())
        );
    }
    }

    public function search($category, $tags, $date_start, $date_end){
        $i='';
        if ($category!=null)
        {$params1=[];
        foreach ($category as $id) {
            $params1[]="$id";

        }
        $params1=implode(',', $params1);
            $i='or';
        }
        $params2=[];
        if ($tags!=null and $i!='')
        {
        foreach ($tags[0] as $id) {
            $params2[]=" posts.tag like '$id' or ";

        }
        $params2=implode(' ', $params2);
            $params2 = substr($params2,0,-3);
            $i='or';
        }
        $params3='';

        if (isset($date_start)and isset($date_end)){

                $params3="or posts.date BETWEEN '$date_start[0]' AND '$date_end[0]'";
        }
        elseif (isset($date_start)and !isset($date_end)){
            $params3="posts.date >'$date_start[0]' ";
        }elseif (!isset($date_start)and isset($date_end)){
            $params3="posts.date <'$date_end[0]' ";
        }
//return "select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id where  posts.category in ({$params1}) "."{$params2}"."{$params3}";
        $sth=$this->pdo->prepare("select ".self::SQLDATA." from posts JOIN category ON posts.category=category.id where posts.category in ({$params1}) "."$i "."{$params2}"."$i "."{$params3}"); // in (?,?,?)

        $sth->execute($category);

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $posts[]=$this->createPost($row['category'], $row['name'], $row['id'], $row['title'], $row['post'], $row['date'], $row['views'], $row['analitics'], $row['picture'], $row['tag']);
        }
if (isset($posts))
        return $posts;
        else
            return [];

//
//        $args=[];
//        $args[]=$category;
//        $args[]=$tags;
//        $args[]=$date_start;
//        $args[]=$date_end;
//        return $args;
    }

}