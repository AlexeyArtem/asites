<?php

class ModelTools extends Model
{   
    function __construct() { }

    static function getUrlMainPage($url)
    {
        $pattern = "/.*?\.[A-Za-z]+/"; //доработать рег. выражение
        preg_match($pattern, $url, $mathes);

        if(!empty($mathes)) $result = $mathes[0];
        else $result = $url;

        return $result;
    }

    static function checkResourceExists($url)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
            
        if($http_code == 200) return true;
        else return false;
    }

    static function downloadResource($url)
    {
        if(!ModelTools::checkResourceExists($url)) throw new ResourceNotFoundException("Такой страницы не существует.");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $htmlPage = curl_exec($curl);
        curl_close($curl);

        return $htmlPage;
    }

    static function getAnalysisCounter(string $htmlPage)
    {
        $keyYM = "counterYM";
        $keyGA = "counterGA";
        
        $patternYM = "/(ym\(|yaCounter)(?<$keyYM>\d+)/s";
        $patternGA = "/'(config|create)',\s?'(?<$keyGA>(UA|G)-.*?)'/s";
    
        $counterYA = $counterGA = null;

        preg_match($patternYM, $htmlPage, $mathes);
        if(array_key_exists($keyYM, $mathes)) $counterYA = $mathes[$keyYM];

        preg_match($patternGA, $htmlPage, $mathes);
        if(array_key_exists($keyGA, $mathes)) $counterGA = $mathes[$keyGA];

        return array('counterYA' => $counterYA, 'counterGA' => $counterGA);
    }

    
    static function getAgeDomain($url)
    {
        $domain = $url;

        if( preg_match("/[А-Яа-я]/", $domain) ) $domain = idn_to_ascii($domain);

        $urlWhois = "https://www.nic.ru/whois/?searchWord=" . $domain;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urlWhois);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $htmlPage = curl_exec($curl);

        curl_close($curl);
        $domain = idn_to_utf8($domain);
        
        $pattern = '/((created.*?><.*?>)|(Creation\sDate:\s))(?<year>\d{4})-(?<month>\d{2})-/';
        preg_match($pattern, $htmlPage, $matches);

        if(!array_key_exists("year", $matches) or !array_key_exists("month", $matches)) return "не удалось определить.";

        $year = (int)$matches["year"];
        $month = (int)$matches["month"];

        $today = getdate();
        $result = 0;

        if($year == (int)$today['year']) $result = $today['mon'] - $month - 1;
        else $result = ($today['year'] - ($year + 1)) * 12 + (12 - $month + $today['mon'] - 1);
        
        return "$result мес.";
    }

    static function getAnalysisTags(string $htmlPage)
    {
        $pattern = '/(<title>(?<title>.*?)<\/title>)|<meta\sname="description"\scontent="(?<description>[^"]+?)"/s';

        preg_match_all($pattern, $htmlPage, $mathes, PREG_SET_ORDER);
        
        $titleValue = $mathes[0]['title'];
    
        if (empty($titleValue)) $descriptionValue = $mathes[0]['description'];
        else $descriptionValue = $mathes[1]['description'];
    
        $patternH = "/<h\d>.*?<\/h\d>/";
        preg_match_all($patternH, $htmlPage, $mathes, PREG_SET_ORDER);

        $tags = [];
        
        for($i = 0; $i < count($mathes); $i++) {
            
            $str = $mathes[$i][0];
            preg_match("/h\d/", $str, $math);
            $str = preg_replace("/<.*?>/", "", $str);
            $nameTag = $math[0];
            $tags[$nameTag][] = $str;
        }

        return array('titleValue' => $titleValue, 'description' => $descriptionValue, 'tagsHvalue' => $tags);
    }

    static function getYandexX($url)
    {
        $reference = [
            '..xxxxxx....xxxxxxxxx.xxxxxxxxxxxxx.......xxxx.......xxxxx.....xxxxxxxxxxxxxx.xxxxxxxx..',
            '..xx........xxx.......xxx........xxxxxxxxxxxxxxxxxxxxxx',
            '.........xxxx......xxxxx......xxxxx.....xxxxxx...xxxx.xxxx.xxxx..xxxxxxxx...x.xxxx.....x',
            'x........xxxx.......xxxx...x...xxxx...x...xxxx..xxx..xxxxxxxxxxxxxxxxxxxxxxxx......xxxx.',
            '......xx.......xxxx......xxxxx....xxxx..x...xxxx...x...xxxxxxxxxxxxxxxxxxxxxx.......x...',
            '..........xxxxxxx...xxxxxxxx...xxx...xx...xxx...xx...xxx...xxxxxxxx...xxxxxxx.....xxxx..',
            '....xxxxx....xxxxxxxxx.xxxxxx.xxxxxxxxx...xxxxx.xx...xxxx..xx...xxx...xxxxxxxx....xxxxx.',
            'x..........x.........xx.......xxxx....xxxxxxx..xxxxxx..xxxxxxx....xxxxx......xxx........',
            '......xxxx.xxxxxxxxxxxxxxxxxxxxxxxx..xxx..xxxx..xxx..xxxxxxxxx..xxxxxxxxxxxxx.xxxx.xxxx.',
            '.xxxxx.....xxxxxxx...xxxx.xxx...xxx...xx..xxxx...xx.xxxxxx..xxxxx.xxxxxxxxx...xxxxxxx...',
        ];

        $yaurl = 'https://www.yandex.ru/cycounter?' . $url;
        $img = @imagecreatefrompng($yaurl);
        
        $src_img = &$img;

        // где могут размещаться данные по ИКС
        $iks_x = 26;
        $iks_y = 10;
        $iks_w = 56;
        $iks_h = 11;
        
        // сюда вырезается нужная часть изображения
        $dst_img = imagecreatetruecolor($iks_w, $iks_h);
        
        // нужна часть изображения, на которой могут быть цифры
        imagecopy($dst_img, $src_img, 0, 0, $iks_x, $iks_y, $iks_w, $iks_h);
        
        $arr = [];
        for ($i = 0; $i < $iks_w; ++$i) {
            $arr[$i] = '';
            for ($j = 0; $j < $iks_h; ++$j) {
                $arr[$i] .= 8882055 == imagecolorat($dst_img, $i, $j) ? '.' : 'x';
            }
        }
        
        // подчистить пустые ряды
        for ($i = 0; $i < $iks_w; ++$i) {
            if ('...........' == $arr[$i])  unset($arr[$i]);
        }
        
        $iks = '';
        $current_symbol = '';
        $current_len = 0;
        
        foreach ($arr as $v) {
            $current_symbol .= $v;
            $current_len += 11;
            
            if (88 == $current_len) { // все символы имеют ширину 8
                foreach ($reference as $num => $symb) {
                    if (similar_text($symb, $current_symbol) > 80) {
                        $iks .= $num;
                        break;
                    }
                }
                $current_symbol = '';
                $current_len = 0;
            } elseif (55 == $current_len) { // кроме 1 — у него ширина 5
                if (similar_text($reference[1], $current_symbol) > 50) {
                    $iks .= 1;
                    $current_symbol = '';
                    $current_len = 0;
                }
            }
        }
        $value = $iks ? $iks : '-';
        $link = "https://webmaster.yandex.ru/siteinfo/?host=" . $url;
        
        return array( 'value' => $value, 'link' => $link);
    }

    static function checkExistsRobotsFile($url)
    {
        //Регистр значения не имеет. Правило в файле должно начинаться либо с '/' либо с '*'.
        //Пробелы между название правила и значением правила не учитываются.
        //Правило allow перекрывает disallow при совпадании значений.
        //user-agent:\s*?(\*|googlebot|yandex|yandexbot).*?disallow:\s*?((\/\*?)|(\*\/?)|(\/index\.\w*?))\n
        //$userAgents[] = { "googlebot", "yandex", "yandexbot"};
        
        $url = ModelTools::getUrlMainPage($url) . "/robots.txt";
        $isExists = ModelTools::checkResourceExists($url);
        return $isExists;
    }

    static function getLoadSpeedHtml($url)
    {
        list($usec, $sec) = explode(" ", microtime());
        $load_time_start = ((float)$usec + (float)$sec);

        $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $htmlPage = curl_exec($curl);
            curl_close($curl);

        list($usec, $sec) = explode(" ", microtime());
        $load_time_end = ((float)$usec + (float)$sec);
        
        $load_time = $load_time_end - $load_time_start;

        return $load_time;
    }

    static function getUrlSitemap($url)
    {
        $resultUrl = null;
        if(ModelTools::checkExistsRobotsFile($url))
        {
            $url = ModelTools::getUrlMainPage($url) . "/robots.txt";
            $text = ModelTools::downloadResource($url);
            
            $pattern = "/sitemap:\s?(?<url>.*?\.xml)/i";
            preg_match($pattern, $text, $mathes);
            if(array_key_exists('url', $mathes)) $resultUrl = $mathes['url'];
        }

        return $resultUrl;
    }

    static function checkServerResponse($url)
    {
        if(!ModelTools::checkResourceExists($url)) throw new ResourceNotFoundException("Такой страницы не существует.");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch,CURLOPT_NOBODY, true);
        curl_setopt($ch,CURLOPT_HEADER, true);

        $result = curl_exec($ch);
        $data['headers'] = explode("\n", $result);   
        
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        $data['curlInfo'] = curl_getinfo($ch);
        
        curl_close($ch);

        return $data;
    }

    static function getPageSpeedAnalysis($url, $strategy = null)
    {
        if(!ModelTools::checkResourceExists($url)) throw new ResourceNotFoundException("Такой страницы не существует.");

        if($strategy != 'DESKTOP' and $strategy != 'MOBILE') $strategy = 'STRATEGY_UNSPECIFIED';

        $url_req = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=' . 'https://' . $url . '&strategy=' . $strategy;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_req);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $results = curl_exec($ch);
        curl_close($ch);

        $array = json_decode($results, true);

        $arr_result = [];
        if(array_key_exists('lighthouseResult', $array))
        {
            $arr_result = array(
            'first-contentful-paint' => $array['lighthouseResult']['audits']['first-contentful-paint']['displayValue'],
            'speed-index' => $array['lighthouseResult']['audits']['speed-index']['displayValue'],
            'largest-contentful-paint' => $array['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'],
            'time-to-interactive' => $array['lighthouseResult']['audits']['interactive']['displayValue'],
            'total-blocking-time' => $array['lighthouseResult']['audits']['total-blocking-time']['displayValue'],
            'cumulative-layout-shift' => $array['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue']
        );
        }

        return $arr_result;
    }

    static function getSitemapAnalysis($url)
    {
        $xmlFile = ModelTools::downloadResource($url);
        
        if(!preg_match("/<\?xml/", $xmlFile)) throw new Exception("Ресурс не является xml-файлом.");
        

        $data = [];

        $name = "Файл Sitemap";
        $pattern = "/<url>/";
        if(preg_match("/<sitemapindex/", $xmlFile)) 
        {
            $pattern = "/<sitemap>/";
            $name = "Файл индекса Sitemap";
        }
        $data['name'] = $name;

        $response = ModelTools::checkServerResponse($url);

        $data['size'] = $response['curlInfo']['size_download'];
        $data['links'] = preg_match_all($pattern, $xmlFile);

        return $data;
    }

    static function getAnalysisRobots($url)
    {
        if(!ModelTools::checkExistsRobotsFile($url)) throw new Exception("Файла robots не существует.");
        
        $file = ModelTools::downloadResource(ModelTools::getUrlMainPage($url) . '/robots.txt');
        $array = explode("\n", $file);

        return $array;
    }

    static function getNumIndexedPages($htmlPage)
    {
        $pattern = "/Нашл(о|а)сь\s(?<value>\d+.*?)р/";
        preg_match($pattern, $htmlPage, $matches);

        $value = 0;
        if (array_key_exists('value', $matches))
        {
            $value = $matches['value'];
            if(preg_match("/(тыс.)|(млн)/", $value))
            {
                if(strpos($value, "тыс.") !== false) $add_zeros = "000";
                if(strpos($value, "млн") !== false) $add_zeros = "000000";

                $string = htmlentities($value, null, 'utf-8');
                $arr_replace = array("тыс.", "млн" , " ", "&nbsp;");
                $value = str_replace($arr_replace, '', $string);
                $value = $value . $add_zeros;
                $value = (int)$value;
            }
        }

        return $value;
    }

    static function download_resource_with_cookie($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt"); //установка ранее сохраненных cookie
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt"); //сохранение cookie
        $userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 YaBrowser/20.11.3.183 Yowser/2.5 Safari/537.36";
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_REFERER, 'https://yandex.ru/');
        
        $htmlPage = curl_exec($ch);
        curl_close($ch);

        return $htmlPage;
    }

    static function getTopSearchData($htmlPage) {
        $pattern = '/<li\sclass="?serp-item"?.*?data-cid="?(?<position>\d)"?.*?href="(?<url>.*?)"/';
        preg_match_all($pattern, $htmlPage, $matches);

        foreach($matches['url'] as $key => $value)
            if(preg_match("/(yabs\.)|^(\/\/)/", $value)) unset($matches['url'][$key]);
        
        $result = array_values($matches['url']);
        //$result  = $matches['url'];

        return $result;
    }
}