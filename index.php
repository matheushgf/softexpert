<?php
//Define root do projeto
define('BASE_PATH', __DIR__);

//Controller e método padrão
$classeController = 'HomeController';
$nomeController = 'home';
$nomeAcao = 'index';

//Parse de URL
if (isset($_SERVER['REQUEST_URI'])) {
    $url = explode('/', $_SERVER['REQUEST_URI']);
    
    if (isset($url[1]) && !empty($url[1])) {
        $nomeController = strtolower($url[1]);
        $classeController = ucfirst($nomeController) . 'Controller';
    }

    if (isset($url[2])) {
        $nomeAcao = explode('?', $url[2])[0];
    }
}

if (!file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $classeController . '.php')) {
    die('404');
}

require_once 'Controllers/' . $classeController . '.php';

try {
    $controller = new $classeController($nomeController);
    $controller->$nomeAcao();
} catch(Exception $e) {
    die('404');
}
