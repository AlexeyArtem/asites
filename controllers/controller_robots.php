<?php 

class ControllerRobots extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Анализ файла Robots";
        $data['p'] = "Инструмент позволяет найти и проверить файл robots.txt на указанном сайте.";
        $data['form']['action'] = "../tools/robots/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_form.php";
        $data['scriptSRC'] = "../resource/js/handlerRobots.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        //подключение модели и вызов нужной функции
        $data = ModelTools::getAnalysisRobots($url);

        //подготовка массива к отправке
        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //вывод результата
        echo $json;
    }
}

?>