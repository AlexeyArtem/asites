<?php 

class ControllerMain extends Controller
{
    function actionIndex()
    {
        $this->view->generate('view_main.php', 'view_template.php');
    }
}

?>