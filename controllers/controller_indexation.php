<?php 

class ControllerIndexation extends Controller
{

    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Проверка проиндексированных страниц";
        $data['p'] = "Инструмент позволяет узнать количество проиндексированных страниц в поисквой системе Яндекс.";
        $data['form']['action'] = "/tools/indexation/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "/views/forms/view_formIndexation.php";
        $data['scriptSRC'] = "/resource/js/handlerIndexation.js";
        
        $this->view->generate('view_toolTemplate.php', 'view_template.php', $data);
    }

    function actionCaptcha()
    {
        if(isset($_POST['rep']) & isset($_POST['url_check']))
        {
            $captchaCode = $_POST['rep'];
            $url = $_POST['url_check'];
            
        }
        else throw new Exception("Ошибка в передаче POST-параметров.");

        $captchaCode = preg_replace("/\s/", "+", $captchaCode);
        $url_reqCheck = "https://yandex.ru" . $url . "&rep=" . $captchaCode;
        $this->sendData($url_reqCheck);
    }

    function actionResult()
    {
        if(isset($_POST['url'])) $site = ModelTools::getUrlMainPage($_POST['url']);
        else throw new Exception("Ошибка в передаче POST-параметров.");

        $url_req = "https://yandex.ru/search/?text=site%3A" . $site . "%20%7C%20host%3A" . $site . "&lr=10651";
        $this->sendData($url_req);
    }

    //отправка итоговых данных клиенту
    private function sendData($url)
    {
        $htmlPage = ModelTools::download_resource_with_cookie($url);
        if ($this->checkCaptcha($htmlPage))
        {
            $data = $this->getCaptchaData($htmlPage);
            $data['type'] = 'captcha';
        }
        else
        {
            $data = [];
            $data['value'] = ModelTools::getNumIndexedPages($htmlPage);
            $data['url'] = $url;
            $data['type'] = 'result';
        }

        header('Content-Type: application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo $json;
    }

    //проверка captcha на странице
    private function checkCaptcha($htmlPage)
    {
        $pattern = "/form.*?action=.*?checkcaptcha/";
        $isCaptcha = false;
        if(preg_match($pattern, $htmlPage)) $isCaptcha = true;

        return $isCaptcha;
    }

    //парсинг страницы с captcha и возвращение data
    private function getCaptchaData($htmlPage)
    {
        preg_match("/action=\"(?<url>.*?)\"/", $htmlPage, $matches);
        $url_checkcaptcha = null;
        if(array_key_exists('url', $matches)) $url_checkcaptcha = $matches['url'];

        preg_match("/<form.*?>.*?<img\ssrc=\"(?<url>.*?)\".*?<\/form>/", $htmlPage, $matches);
        $url_img = null;
        if(array_key_exists('url', $matches)) $url_img = $matches['url'];

        if($url_img == null or $url_checkcaptcha == null) throw new Exception("Переменным не присвоены значения.");
        
        $data['form_path'] = ROOT . "views/forms/view_formCaptcha.php";
        $data['action'] = "/tools/indexation/captcha"; 
        $data['url_img'] = $url_img;
        $data['url_checkcaptcha'] = $url_checkcaptcha;

        return $data;
    }
}

?>