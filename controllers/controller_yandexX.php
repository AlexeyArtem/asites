<?php 

class ControllerYandexX extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Массовая проверка Яндекс ИКС";
        $data['p'] = "Инструмент позволяет узнать ИКС сайта. Этот показатель определяет, насколько сайт востребован у пользователей с точки зрения Яндекса. Можно использовать для своего или чужого сайта.";
        $data['form']['action'] = "../tools/yandexx/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_formYandexX.php";
        $data['scriptSRC'] = "../resource/js/handlerYandexX.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //Обработка post-параметров запроса
        if(isset($_POST['sites'])) $sites = explode("\n", $_POST['sites']);
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        //Подключение модели и вызов нужной функции
        $data = [];
        for($i = 0; $i < count($sites); $i++) 
        {
            $site = $sites[$i];
            $site = preg_replace("/\s/", "", $site);
            $array = ModelTools::getYandexX($site);
            $array['name'] = $site;

            $data[$i] = $array;
        }

        //Подготовка к отправке и преобразование массива в json формат
        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //Вывод результата
        echo $json;
    }
}

?>