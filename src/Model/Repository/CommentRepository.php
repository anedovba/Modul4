<?php
namespace Model\Repository;

use Library\EntityRepository;
use Model\Comment;

class CommentRepository extends EntityRepository
{
     /**
     * @param $object
     */
    public function save($object)
    {
        $query="insert into comments (message, user_id, post_id, mark, parent_id, visible) values (:message, :user_id, :post_id, :mark, :parent_id, :visible)";
        $sth=$this->pdo->prepare($query);
        $sth->execute(array(
            'message'=>$object->getMessage(),
            'user_id'=>$object->getUserId(),
            'post_id'=>$object->getPostId(),
            'mark'=>$object->getMark(),
            'parent_id'=>$object->getParentId(),
            'visible'=>$object->getVisible()
        ));
    }
    public function saveUpdate($object)
    {


        $query="update comments set message=:message, date=:date, mark=:mark, visible=:visible where id=:id";
        $sth=$this->pdo->prepare($query);
        $sth->execute(array(
            'message'=>$object->getMessage(),
            'date'=>$object->getDate(),
            'mark'=>$object->getMark(),
            'visible'=>$object->getVisible(),
            'id'=>$object->getId()
        ));
    }

    public function findLastId(){

        $query="select id from comments ORDER BY date DESC LIMIT 1";

        $sth=$this->pdo->query($query);
        $row=$sth->fetch(\PDO::FETCH_ASSOC);
        if(!$row)
        {
            throw new \Exception('not found');
        }
        if ($row){
            $id=$row['id'];
        }
        return $id;

    }

    public function findAll(){

        $query="select * from comments ORDER BY date DESC";

        $sth=$this->pdo->query($query);
        $comments=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $comments[]=$row['post_id'];
        }
        return $comments;

    }


   public function findNotActiveByPage($page, $perPage){
       $offset = ($page - 1) * $perPage;
       $query="select * from comments where visible=0 ORDER BY date DESC LIMIT {$offset}, {$perPage} ";

       $sth=$this->pdo->query($query);
       $comments=[];
       while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
           $comments[]=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
       }
       return $comments;
}

    public function findAllByPage($page, $perPage){
        $offset = ($page - 1) * $perPage;
        $sql = "select * from comments LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $comments = [];

        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $comments[]=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
        }
        return $comments;
    }

    public function changeMessage($id, $mess){
        $query="update comments set message=:message where id=:id";
        $sth=$this->pdo->prepare($query);
//        dump($mark, $id);
//        die;
        $sth->execute(array(
                'message'=>$mess,
                'id'=>$id)
        );
    }

    public function findTopUser(){

        $query="SELECT user_id, COUNT(id) as c FROM `comments` GROUP BY user_id ORDER BY c DESC LIMIT 5";

        $sth=$this->pdo->query($query);

        $topUsers=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $topUsers[]=$row['user_id'];
        }
        return $topUsers;

    }

    public function findByTopComments(){
        $sth=$this->pdo->query("SELECT post_id, COUNT(id) as c FROM `comments` GROUP BY post_id ORDER BY c DESC LIMIT 3");
        $topPosts=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){
            $topPosts[]=$row['post_id'];
        }
        return $topPosts;

    }

    public function findAllByUserId($id){

        $query="select * from comments  where user_id={$id} ORDER BY date DESC";

        $sth=$this->pdo->query($query);
        $comments=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $comments[]=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
        }
        return $comments;

    }
    public function countByUserId($id){
        $query="select count(*) from comments  where user_id={$id}";

        $sth=$this->pdo->query($query);
        //возвращает значение
        return $sth->fetchColumn();
    }

    public function countAll(){
        $query="select count(*) from comments";

        $sth=$this->pdo->query($query);
        //возвращает значение
        return $sth->fetchColumn();
    }
    public function countAllNotActive(){
        $query="select count(*) from comments where visible=0";

        $sth=$this->pdo->query($query);
        //возвращает значение
        return $sth->fetchColumn();
    }

    public function find($id){
        $sth=$this->pdo->query("select * from comments where id={$id}");
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

    public function findByUserIdByPage($id, $page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "select * from comments WHERE user_id={$id} ORDER by date DESC LIMIT {$offset}, {$perPage}";
        $sth = $this->pdo->query($sql);
        $comments=[];
        while ($row=$sth->fetch(\PDO::FETCH_ASSOC)){

            $comments[]=$this->createComment($row['id'], $row['message'], $row['date'], $row['user_id'], $row['post_id'], $row['mark'], $row['parent_id'], $row['visible']);
        }
        return $comments;
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

public function approveById($id){
    $query="update comments set visible=1 where id={$id}";
    $this->pdo->query($query);
}

    public function removeById($id){

        $this->pdo->query("DELETE FROM comments WHERE id = {$id}");
    }

}

