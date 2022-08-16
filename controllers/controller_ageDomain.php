<?php 

class ControllerAgeDomain extends Controller
{
    function actionIndex()
    {
        //Формирование данных для шаблона
        $data = [];
        $data['h2'] = "Проверка возраста домена";
        $data['p'] = "Сервис для быстрой проверки возраста домена.";
        $data['form']['action'] = "../tools/age-domain/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "views/forms/view_form.php";
        $data['scriptSRC'] = "../resource/js/handlerDefault.js";

        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionResult()
    {
        //обработка post-параметров запроса
        if(isset($_POST['url'])) $url = $_POST['url'];
        else throw new Exception("Ошибка в передаче POST-параметров.");

        //подключение модели и вызов нужной функции
        $data = ModelTools::getAgeDomain($url);

        //вывод результата
        echo $data;
    }
}

?>