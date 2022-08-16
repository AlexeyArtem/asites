<?php 

class ControllerTopSearchRes extends Controller
{
    const MAX_COUNT_KEYWORDS = 5;
    const NAME_VAR_COOKIE_KEYWORDS = 'keywords';
    const NAME_VAR_COOKIE_LINKS = 'links';
    
    private $keywords;
    private $arrayLinks;

    function actionIndex()
    {
        $data = [];
        $data['h2'] = "Анализ топ выдачи поисковой системы Яндекс";
        $data['p'] = "Инструмент для быстрой выгрузки и группировки ТОП-10 сайтов из выдачи поисковой системы Яндекса по заданным запросам. Максимальное количество ключевых слов: 5.";
        $data['form']['action'] = "/tools/top-search-result/action";
        $data['form']['button'] = "Проверить";
        $data['form']['path'] = ROOT . "/views/forms/view_formTopSearchRes.php";
        $data['scriptSRC'] = "/resource/js/handlerTopSearchRes.js";
        
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
        $urlCheckCaptcha = "https://yandex.ru" . $url . "&rep=" . $captchaCode;
        $this->sendData($urlCheckCaptcha);
    }

    function actionResult()
    {
        if(isset($_POST['keywords']))
        {
            $keywords = explode("\n", $_POST['keywords']);
           
            //Фильтрация и проверка ключевых слов
            foreach($keywords as $key => $value) {
                if(empty($value) or iconv_strlen($value) <= 1) {
                    unset($keywords[$key]);
                    continue;
                }
                $keywords[$key] = trim($value);
                $keywords[$key] = preg_replace("/\s/", "%20", $value);
            }

            $keywords = array_values($keywords);
            if(count($keywords) > self::MAX_COUNT_KEYWORDS) throw new Exception("Превышено допустимое число элементов.");
            
            $this->keywords = $keywords;
            $this->arrayLinks = null;
            $this->delCookie();

            $this->sendData();
        }
        else throw new Exception("Ошибка в передаче POST-параметров.");
    }

    //Отправка данных клиенту
    private function sendData($urlCheckCaptcha = null)
    {
        $currentIndex = getCount($this->arrayLinks);

        $copyArrayLinks = $copyKeywords = null;
        if(isset($_COOKIE[self::NAME_VAR_COOKIE_LINKS])) $copyArrayLinks = $this->getUnserializeArrayCookie($_COOKIE[self::NAME_VAR_COOKIE_LINKS]);
        if(isset($_COOKIE[self::NAME_VAR_COOKIE_KEYWORDS])) $copyKeywords = unserialize($_COOKIE[self::NAME_VAR_COOKIE_KEYWORDS]);
        
        if($copyKeywords != null) {
            $count = getCount($copyArrayLinks);
            if($count > 0 or ($count == 0 and $urlCheckCaptcha != null)) {
                $currentIndex = $count;
                $this->arrayLinks = $copyArrayLinks;
                $this->keywords = $copyKeywords;
            }
        }

        $data = [];
        for($i = $currentIndex; $i < count($this->keywords); $i++) {
            
            $url_req = "https://yandex.ru/search/?text=" . $this->keywords[$i] . "&lr=10651";
            if($urlCheckCaptcha != null) {
                $url_req = $urlCheckCaptcha;
                $urlCheckCaptcha = null;
            }

            $htmlPage = ModelTools::download_resource_with_cookie($url_req);
            if ($this->checkCaptcha($htmlPage)) {
                
                $data = $this->getCaptchaData($htmlPage);
                $data['type'] = 'captcha';
                
                //Установка или перезапись cookie
                $this->setCookie();
                break;
            }

            else {
                $this->addLinks($this->keywords[$i], ModelTools::getTopSearchData($htmlPage));
                
                //Очистка cookie и подготовка данных для отправка на последней итерации цикла
                if(getCount($this->arrayLinks) == count($this->keywords)) {
                    $data['links'] = $this->arrayLinks;
                    $data['type'] = 'result';

                    //Удаление cookie
                    $this->delCookie();
                }
            }
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
        
        //$data['form_path'] = ROOT . "views/forms/view_formCaptcha.php";
        $data['action'] = "/tools/top-search-result/captcha"; 
        $data['url_img'] = $url_img;
        $data['url_checkcaptcha'] = $url_checkcaptcha;

        return $data;
    }

    private function addLinks(string $keyword, $array_Links) {
        if(getCount($this->arrayLinks) < getCount($this->keywords)) $this->arrayLinks[$keyword] = $array_Links;
    }

    private function setCookie() {
        
        setcookie(self::NAME_VAR_COOKIE_KEYWORDS, serialize($this->keywords), time()+70, "/");
        
        if(!empty($this->arrayLinks)) {
            foreach($this->arrayLinks as $key => $val) {
                setcookie(self::NAME_VAR_COOKIE_LINKS . '[' . $key . ']', serialize($val), time()+70, "/");
            }
        }
    }

    private function getUnserializeArrayCookie($data) {
        $data = [];
        foreach ($data as $key => $value) {
            $data[unserialize($key)] = unserialize($value);
        }
        return $data;
    }

    private function delCookie() {
        setcookie(self::NAME_VAR_COOKIE_KEYWORDS, '', time()-7000, "/");
        setcookie(self::NAME_VAR_COOKIE_LINKS, '', time()-7000, "/");
    }
}

?>