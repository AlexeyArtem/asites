<?php 

class ControllerAdmin extends Controller
{
    private $site;
    function __construct()
    {
        $this->model = new ModelAdmin();
        $this->view = new View();
    }
    
    function actionIndex()
    {
        if(!isset($_SESSION['logged_user']))
        {
            header("location: ../login");
        }
        else 
        {
            $this->view->generate('view_mainAdmin.php', 'view_adminTemplate.php');
        }
    }

    function actionStat()
    {
        if(!isset($_SESSION['logged_user']))
        {
            header("location: ../login");
            return;
        }
        else 
        {
            $data = $this->model->getStat();
            $this->view->generate('view_statAdmin.php', 'view_adminTemplate.php', $data);
        }
    }

}

?>