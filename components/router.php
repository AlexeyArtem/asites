<?php

class Router
{
    private $routes;
    private $controller;

    function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->controller = new Controller();
        $this->routes = include($routesPath);
    }

    private function getURI()
    {
        if(!empty($_SERVER['REQUEST_URI']))
        {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function Start()
    {
        $uri = $this->getURI();
        
        //Запрос главной страницы
        if(empty($uri))
        {
            $pathMain = ROOT . 'controllers/controller_main.php';
            if(file_exists($pathMain))
            {
                include_once($pathMain);
                $objMain = new ControllerMain();
                $action = 'actionIndex';

                if(method_exists($objMain, $action)) $objMain->$action();
                else $this->showErrorPage500();   
            }
            else $this->showErrorPage500();
        }

        else 
        {
            $isFindUri = false;
            foreach($this->routes as $uriPattern => $path)
            {
                if(preg_match("~^$uriPattern$~", $uri))
                {
                    $isFindUri = true;

                    $segments = explode('/', $path);
                    $name = $segments[0];
                    $action = $segments[1];

                    $fileController = ROOT . 'controllers/' . 'controller_' . $name . ".php";
                    $fileModel = ROOT .'models/' . 'model_' . $name . ".php";

                    //Подключение дополнительного файла с моделью инструментов, если запрашивается общий анализ или конкретный инструмент
                    if(preg_match("/analytics/", $uriPattern) or preg_match("/tools\//", $uriPattern))
                    {
                        $path = ROOT . 'models/model_tools.php';
                        if(file_exists($path)) include_once($path);
                    }

                    if(file_exists($fileController)) include_once($fileController);
                    if(file_exists($fileModel)) include_once($fileModel);

                    $classController = 'Controller' . ucfirst($name);
                    if(method_exists($classController, $action))
                    {
                        try {
                            $obj = new $classController;
                            $obj->$action();  
                        }
                        catch(ResourceNotFoundException $e) {
                            $this->showErrorPage404();
                        }
                        catch(Exception $e) {
                            $this->showErrorPage500();
                        }
                    }
                    else $this->showErrorPage500();

                    break;
                }
            }
            if(!$isFindUri) $this->showErrorPage404();
        }
    }

    function showErrorPage404()
	{
        $this->controller->showError404();
    }

    function showErrorPage500()
    {
        $this->controller->showError500();
    }
}