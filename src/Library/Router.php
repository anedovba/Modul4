<?php
namespace Library;

class Router
{
//    public static function redirect($to)
//    {
//        header("Location: {$to}");
//        die;
//    }

    private $routes;
    private $currentRoute;
    //передаем название файла в конструктор
    public function __construct($file)// TODO изменить конструктор вместо фыйла принимает $config (в котором находятся все распарсенные файлы). внутри конструктора перебираем массив и создаем роут
    {

        // зашиваем в приватную переменную Require
        $this->routes = require $file;
    }

    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * @param $uri
     * @return bool
     */
    private function isAdminUri($uri)
    {
        return strpos($uri, '/admin') === 0;
    }

    public function match(Request $request)
    {
        $uri = $request->getUri();//вытягиваем строчку URI

        if ($this->isAdminUri($uri)) { // мы в админке или нет
            Controller::setAdminLayout();
        }

        foreach ($this->routes as $route) { // перебираем роуты
            $pattern = $route->pattern;

            foreach ($route->params as $key => $value) {
                $pattern = str_replace('{' . $key . '}', "($value)", $pattern); // тут меняется то что в фигурных скобках на регулярку
            }

            $pattern = '@^' . $pattern .     '$@'; // @ - delimiter, @^book-([0-9]+)\.html$@ //запись регулярного віражения в переменную

            if (preg_match($pattern, $uri, $matches)) { // preg_match отдает 0 (нет соответствия) или  1 (есть соостветсвие в массиве)
//                if(in_array($route->methods,$request->getMethod()))
//                {
//                      TODO
//                }
                $this->currentRoute = $route;
                array_shift($matches);
                $getParams = array_combine(array_keys($route->params), $matches);
                $request->mergeGet($getParams);//передача в глобальный гет айди книги

                break;
            }
        }

        if (!$this->currentRoute) {
            throw new \Exception('Page not found', 404);
        }
    }

    public function getUri($routeName, array $params = array())
    {

        // todo сгенерировать Uri - обратное действие
    }

    public function redirect($to)
    {
        header('Location: ' . $to);
        die;
    }
}