<?php

error_reporting(E_ALL);

use Library\Config;
use Library\Container;
use Library\RepositoryManager;
use Library\Request;
use Library\Session;
use Library\Router;
use Library\Cookie;
use Library\DbConnection;
use Library\MetaHelper;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Controller\ErrorController;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT',  __DIR__.DS. '..'.DS );
define('SRC_DIR', ROOT . 'src' . DS);
define('VIEW_DIR', SRC_DIR.'View'.DS);
define('LIB_DIR', SRC_DIR.'Library'.DS);
define('CONFIG_DIR', ROOT . 'config' . DS);
define('VENDOR_DIR', ROOT . 'vendor' . DS);
define('LOG_DIR', ROOT . 'logs' . DS);

require VENDOR_DIR.'autoload.php';

try{
    Session::start();
    $config=new Config();
    $request = new Request();
    $pdo = (new DbConnection($config))->getPDO();
    $repositoryManager=(new RepositoryManager())
        ->setPDO($pdo);

    $router = new Router(CONFIG_DIR . 'routes.php');
    $metaHelper=new MetaHelper($config);

    // Create the logger
    $logger = new Logger('default_logger');
    // Now add some handlers
    $logger->pushHandler(new StreamHandler(LOG_DIR.'log.txt', Logger::DEBUG));

    $advertService=new \Model\AdvertService();

    $cookie=new Cookie();
    $container =new Container();

    $container->set('cookie', $cookie);
    $container->set('config', $config);

    $container->set('database_connection', $pdo);

    $container->set('repository_manager', $repositoryManager);

    $container->set('router', $router);
    //title и description
    $container->set('meta_helper', $metaHelper);

    $container->set('logger', $logger);//опрокидываем в контейнер и в контроллере можно использовать например в security контроллере

    $container->set('advert_service', $advertService);

    $router->match($request);//перебор всех адресов на поиск совпадения

    $route = $router->getCurrentRoute();//вытаскиваем название текущий роутер контроллера и название действия
// определяем название контроллера и название действия
    $controller = 'Controller\\' . $route->controller . 'Controller';

    $action = $route->action . 'Action';

//для того что б проверялся и наличие метода с правильным названием
    $controller = new $controller();

    $controller->setContainer($container);
    // вытаскиваем все запросы
//проверяем существует ли метод в классе
    if(!method_exists($controller,$action)){
        throw new \Exception('Page not found',404);
    }
//вызываем действие но никто не гарантирует что оно есть у нас
    $content=$controller->$action($request);


} catch (\Exception $e){
    $controller=new ErrorController();
    $controller->setContainer($container);
    $content=$controller->errorAction($request,$e);
}

echo $content;



