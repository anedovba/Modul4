<?php
namespace Library;
class Request
{
    private $get;
    private $post;
    private $server;

    /**
     * @return mixed
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @param mixed $get
     * @return Request
     */
    public function setGet($get)
    {
        $this->get = $get;
        return $this;
    }
    // private $session

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
//        $this->session=new Session();
    }

    public function post($key, $default = null)
    {
        return isset($this->post[$key]) ? $this->post[$key] : $default;
    }

    public function get($key, $default = null)
    {
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }

    public function server($key, $default = null)
    {
        return isset($this->server[$key]) ? $this->server[$key] : $default;
    }

    public function isPost()
    {
        return (bool)$this->post;
    }

    public function isGet()
    {
        return (bool)$this->get;
    }


    public function getIpAddress()
    {
        return $this->server('REMOTE_ADDR');
    }

    public function mergeGet($newGet)
    {
        $this->get += $newGet;
        $_GET += $newGet;
    }

    public function getUri()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $uri = $uri[0];
        return $uri;
    }

    public function set($key, $value)
    {
        $this->get[$key]=$value;
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }
}