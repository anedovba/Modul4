<?php
namespace Library;
use Library\Exception\ParameterNotFoundException;
class Config
{
      public function __construct()
    {
        $file = CONFIG_DIR . 'db.xml';
        //берет файл и превращает в объект с свойствами и их значениями согласно тегам
        $xmlObject = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOWARNING);
        //если файл не xml - ошибка
        if (!$xmlObject) {
            throw new \Exception('Config file not found');
        }

        //создание метода на лету
        foreach ($xmlObject as  $key => $value) {
            $this->$key = (string)$value;
        }
    }

    //обрабатіваем візов несуществующего свойства
    public function __get($name)
    {
        throw new ParameterNotFoundException();
    }

}