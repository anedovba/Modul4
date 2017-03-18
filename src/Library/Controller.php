<?php
namespace Library;


abstract class Controller{

    protected $container;

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

//    private $layout = 'default_layout.phtml';
// делаем его статичным
    private static $layout = 'default_layout.phtml';

    public function setContainer(Container $container)
    {
        $this->container=$container;
        return $this;
    }

    public  function pageReload($to)
    {
        $this->container->get('router')->redirect($to);
    }
    public static function setAdminLayout()
    {
        //меняем лейаут на админский
        self::$layout = 'admin_layout.phtml';
    }

    protected function render($view, array $args=array()){
        extract($args);
        $classname = str_replace(['Controller', '\\'], ['', DS], get_class($this));
        $classname = trim($classname, DS);
        //путь к файлу в вьюхе
        $file=VIEW_DIR.$classname.DS.$view;
        if(!file_exists($file)){
            throw new \Exception("Template {$file} not found");
        }
        ob_start();
        require $file;
        $content= ob_get_clean();

        ob_start();
//        require VIEW_DIR.$this->layout;
        require VIEW_DIR.self::$layout;
        return ob_get_clean();
    }

    public function getAdvert(){
        $advertService = $this->container->get('advert_service');

        return $advertService->findAll();
    }

    public function getCss()
    {
        $repos=$this->container->get('repository_manager')->getRepository('Css');
        return $colors=$repos->find();

    }
    //вывод menu
    function getMenu() {

        $repos=$this->container->get('repository_manager')->getRepository('Menu');
        return $repos->find();

    }



    public function setCookie(Cookie $cookie)
    {
        setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path);
    }

    public function getCookie($name)
    {
        if ( isset($_COOKIE[$name]) ){
            return $_COOKIE[$name];
        }

        return null;
    }

    public function removeCookie($name)
    {
        if (isset($_COOKIE[$name])){
            self::set($name, '', -3600);
            unset($_COOKIE[$name]);
        }
    }

    public function getTitle()
    {
        return $this->container->get('meta_helper')->getTitle();
    }

    protected function  getAllowedMethods($method)
    {
        //ReflectionСlass - объект этого класса считывает всю инфо с класса, даже комментарии
        $reflection=new \ReflectionClass(get_class($this));//::class - возврат строчки, котороая соответствует названию класса
        $method=$reflection->getMethod($method);//получаем все что есть в текущем методе

        $doc=$method->getDocComment();//получаем комментарии. далее поиском регулярки ищем методы
       $res= preg_match('#@Methods\[([A-Z,\s]+)\]#', $doc, $matches);//поиск в $doc и запись совпадений в $matches
        if(!$res)
        {
            return ['POST', 'GET', 'PUT', 'DELETE'];
        }
        array_shift($matches);//убираем первый элемент @Methods[GET, POST]

        return explode(",", str_replace(" ", '', $matches[0]));
    }

}