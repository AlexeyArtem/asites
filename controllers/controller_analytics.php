<?php

class ControllerAnalytics extends Controller
{
    private $url;
    private $analysisData;
    
    function __construct()
    {
        if(isset($_POST['site'])) $url = $_POST['site'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        $this->view = new View();
        $this->model = new ModelAnalytics($url);
    }

    function actionIndex()
    {
        $this->analysisData = $this->model->getMainAnalysisSite();
        $this->view->generate('view_analytics.php', 'view_template.php', $this->analysisData);
    }
}

?>