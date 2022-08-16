<?php 

class Controller
{
    public $model;
    public $view;

    function __construct()
    {
        $this->view = new View();
    }

    function actionIndex()
    {
        
    }

    function showError500()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $this->view->generate('view_500.php', 'view_template.php');
    }

    function showError404()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
        $this->view->generate('view_404.php', 'view_template.php');;
    }

}

?>