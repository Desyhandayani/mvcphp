<?php

class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        if (!empty($url)) {
            // Cek apakah file controller yang dimaksud ada
            if (file_exists('../app/controllers/' . $url[0] . '.php')) {
                $this->controller = $url[0];
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';

        // Inisialisasi objek controller
        $this->controller = new $this->controller;

        // Cek apakah method yang dimaksud ada dalam controller
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Parameter
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Jalankan controller, & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET["url"])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        } else {
            return [];
        }
    }
}
