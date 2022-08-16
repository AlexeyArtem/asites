<?php 

class ControllerSitemap extends Controller
{
    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Анализ файлов Sitemap";
        $data['p'] = "Инструмент позволяет узнать информацию о файле sitemap по указанной ссылке.";
        $data['form']['action'] = "../tools/sitemap/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_formSitemap.php";
        $data['scriptSRC'] = "../resource/js/handlerSitemap.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");
        
        //подключение модели и вызов нужной функции
        $data = ModelTools::getSitemapAnalysis($url);
        $size = (double)$data['size'];
        $data['size'] = round($size / 1024, 3);

        //подготовка массива к отправке
        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        //вывод результата
        echo $json;
    }
}

?>