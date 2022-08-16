<?php

class Db
{
    private $config;
    private $pdo;

    function __construct()
    {
        $dbConfigPath = ROOT . 'config/db.php';
        $this->config = include($dbConfigPath);

        $this->pdo = new PDO('mysql:host='.$this->config['host'].';dbname='.$this->config['dbname'].'', $this->config['user'], $this->config['password']);
    }

    function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);

		if (!empty($params)) {
			foreach ($params as $key => $val) {
				$stmt->bindValue(':' . $key, $val);
			}
        }
        
		$stmt->execute();
        return $stmt;
    }
}