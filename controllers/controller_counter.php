<?php 

class ControllerCounter extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Проверка счетчиков аналитики";
        $data['p'] = "Инструмент позволяет узнать о наличии счетчиков Google Analytics и Яндекс Метрика для запрашиваемого URL.";
        $data['form']['action'] = "../tools/counter/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_form.php";
        $data['scriptSRC'] = "../resource/js/handlerCounter.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        //подключение модели и вызов нужных функций
        $htmlPage = ModelTools::downloadResource($url);
        $data = ModelTools::getAnalysisCounter($htmlPage);

        foreach($data as $key => $value) {
            if(empty($data[$key])) $data[$key] = "Не найден";
        }

        //подготовка массива к отправке
        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //вывод результата
        echo $json;
    }
}

?>