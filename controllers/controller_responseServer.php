<?php 

class ControllerResponseServer extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Проверка ответа сервера";
        $data['p'] = "Инструмент позволяет узнать ответ сервера запрашиваемого URL.";
        $data['form']['action'] = "../tools/response/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_form.php";
        $data['scriptSRC'] = "../resource/js/handlerResponse.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        //подключение модели и вызов нужной функции
        $data = ModelTools::checkServerResponse($url);

        //подготовка массива для отправки
        $array = [];
        $array['info']['status'] = mb_substr($data['headers'][0], mb_strpos($data['headers'][0], ' '));
        $array['info']['time'] = round($data['curlInfo']['starttransfer_time'] * 1000); //перевод в МС
        $array['info']['ip'] = $data['curlInfo']['primary_ip'];
        
        $encoding = mb_substr($data['curlInfo']['content_type'], mb_strpos($data['curlInfo']['content_type'], ' '));
        if(!preg_match("/charset/", $encoding)) $encoding = "utf-8";
        $array['info']['encoding'] = preg_replace("/charset\=/", '', $encoding);
        
        $array['info']['size'] = round($data['curlInfo']['size_download'] / 1024, 2); //перевод в КБ

        $array['headers'] = [];
        foreach($data['headers'] as $value) {
            if(strlen($value) <= 1) continue;
            $array['headers'][] = $value;
        }
        unset($array['headers'][0]);//удаление строки со статусом ответа

        //подготовка массива к отправке
        header('Content-Type: application/json');
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);

        //вывод результата
        echo $json;
    }
}

?>