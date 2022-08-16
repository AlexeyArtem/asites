<?php

class ModelAnalytics extends Model
{
    private $urlMainPage;
    private $htmlPage;
    private $db;

    function __construct(string $url)
    {
        $this->urlMainPage = ModelTools::getUrlMainPage($url);
        if(!ModelTools::checkResourceExists($this->urlMainPage)) throw new ResourceNotFoundException("Такой страницы не существует.");
        
        $this->htmlPage = ModelTools::downloadResource($this->urlMainPage);
        $this->db = new Db();
    }

    function getMainAnalysisSite()
    {
        $data = [];
        $data['ageDomain'] = ModelTools::getAgeDomain($this->urlMainPage);
        $data['counter'] = ModelTools::getAnalysisCounter($this->htmlPage);
        $data['tags'] = ModelTools::getAnalysisTags($this->htmlPage);
        $data['yandexX'] = ModelTools::getYandexX($this->urlMainPage);
        $data['robots'] = ModelTools::checkExistsRobotsFile($this->urlMainPage);
        $data['htmlLoad'] = ModelTools::getLoadSpeedHtml($this->htmlPage);
        $data['server'] = ModelTools::checkServerResponse($this->urlMainPage);

        $data['deskSpeed'] = ModelTools::getPageSpeedAnalysis($this->urlMainPage, 'DESKTOP');
        $data['mobSpeed'] = ModelTools::getPageSpeedAnalysis($this->urlMainPage, 'MOBILE');
        
        $data['sitemap'] = ModelTools::getUrlSitemap($this->urlMainPage);

        $this->addStat($this->urlMainPage);

        return $data;
    }

    private function addStat($site)
    {
        $date = date("Y-m-d H:i:s");
        $params = [
            'date' => $date,
            'site' => $site,
        ];

        $sql = "INSERT INTO analysis_requests (`Date`, `Site`) VALUES (:date, :site)";
        
        $this->db->query($sql, $params);
    }

}