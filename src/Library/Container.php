<?php
namespace Library;

class Container
{//сервис соединения
    private $services=array();

    public function get($key)
    {
        if (!$this->services[$key]){
            throw new \Exception("$key not found", 500);
        }
        return $this->services[$key];
    }

    public function set($key, $object)
    {
        $this->services[$key]=$object;
    }
}