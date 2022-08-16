<?php

//1. Общие настройки
//Отключение ошибок в финальной версии
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT', dirname(__FILE__) . '/'); //Определение константы корневого пути

//2. Подключение системных файлов
require_once ROOT . 'core/controller.php';
require_once ROOT . 'core/model.php';
require_once ROOT . 'core/view.php';

//3. Установка соединения с БД
require_once ROOT . 'components/db.php';

//4. Подключение и вызов router
require_once ROOT . 'components/router.php';
$router = new Router();

session_start();
$router->start();

?>