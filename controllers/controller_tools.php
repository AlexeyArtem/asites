<?php 

class ControllerTools extends Controller
{
    private $site;
    
    function __construct()
    {
        $this->view = new View();
    }
    
    function actionIndex()
    {
        $this->view->generate('view_tools.php', 'view_template.php');
    }

}

?>