<?php
namespace Model\Repository;

//use Library\DbConnection;
use Library\EntityRepository;
use Model\User;


class UserRepository extends EntityRepository
{
    public function find($email, $password){

        $sql = '
            select * from user 
            where email = :email and password = :password
            and activation_hash is null
        ';
        $sth = $this->pdo->prepare($sql);

//        $sth = $this->pdo->prepare('select * from user where email = :email and password = :password');
        $sth->execute(compact('email', 'password'));
        $user = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($user){
            $user=(new User())->setEmail($user['email'])->setId($user['id'])->setRole($user['role']);
        }
        return $user;
    }

    public function findById($id){

        $sql = 'select * from user where id = :id';
        $sth = $this->pdo->prepare($sql);

        $sth->execute(compact('id'));
        $user = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($user){
            $user=(new User())->setEmail($user['email'])->setId($user['id']);
        }
        return $user;
    }


    public function save($object, $table = null)
    {
        $class=explode('\\',get_class($object));
        $class=end($class);
        if($table==null){
            $table=strtolower($class);
        }
        $sql = "insert into {$table} (email, password, role, activation_hash) values (:email, :password, :role, :activation_hash)";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(
            'email' => $object->getEmail(),
            'password' => $object->getPassword(),
            'role' => $object->getRole(),
            'activation_hash' => $object->getActivationHash()
        ));
    }


}