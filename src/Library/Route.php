<?php
namespace Library;

//используется в массиве в config routes
class Route
{
    public $pattern;//хвост адресной строки
    public $controller;//имя контроллера
    public $action;//метод
    public $params;//доп параметры проверка данных


    public function __construct($pattern, $controller, $action, array $params = array(), array $methods=array())
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;

    }
}