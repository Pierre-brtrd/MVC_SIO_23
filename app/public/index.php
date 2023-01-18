<?php

use App\Autoloader;
use App\Core\Main;

// On importe l'autoloader
define('ROOT', dirname(__DIR__));

require_once  ROOT . '/Autoloader.php';

Autoloader::register();

// On instancie Main (Routeur)
$app = new Main();

// On démarre l'app
$app->start();
