<?php 

class ControllerPageSpeed extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Проверка скорости загрузки сайта";
        $data['p'] = "Инструмент позволяет узнать различную информацию о скорости загруки сайта.";
        $data['form']['action'] = "/tools/speed/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_formSpeed.php";
        $data['scriptSRC'] = "/resource/js/handlerPageSpeed.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        $strategy = $_POST['strategy'];
        // var_dump($url);
        // exit;

        //подключение модели и вызов нужной функции
        $data = [];
        if($strategy != 'all') $data = ModelTools::getPageSpeedAnalysis($url, $strategy);
        else {
            $data['desk'] = ModelTools::getPageSpeedAnalysis($url, 'DESKTOP');
            $data['mob'] = ModelTools::getPageSpeedAnalysis($url, 'MOBILE');
        }


        //подготовка массива к отправке
        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //вывод результата
        echo $json;
    }
}

?>