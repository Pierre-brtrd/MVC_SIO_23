<?php

namespace App\Core;

use App\Controllers\MainController;

class Main
{
    public function start()
    {
        // On démarre la session
        session_start();

        // On retire le trailing /
        // On récupère l'url envoyée
        $uri = $_SERVER['REQUEST_URI'];

        // On vérifie que l'URI n'est pas vide et se termine par /
        if (!empty($uri) && $uri != '/' && $uri[-1] === '/') {
            // On enlève le dernier /
            $uri = substr($uri, 0, -1);

            // On renvoie le code de réponse 301
            http_response_code(301);

            // On redirige la paage
            header('Location:' . $uri);
            exit();
        }

        // On gère les paramètres
        $params = explode('/', $_GET['p']);

        if ($params[0] != '') {
            // On aa au moins 1 paramètre
            // On vérifie que le fichier du controller exist
            $file = ROOT . '/Controllers/' . ucfirst($params[0]) . 'Controller.php';

            if (file_exists($file)) {
                // On récupère le nom du controlller
                $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

                $controller = new $controller();

                $methode = (isset($params[0])) ? array_shift($params) : 'index';

                if (method_exists($controller, $methode)) {
                    // On éxecute la méthode
                    (isset($params[0])) ? call_user_func_array([$controller, $methode], $params) : $controller->$methode();
                } else {
                    // C'est une 404
                    http_response_code(404);
                    echo "Page not found";
                }
            } else {
                // C'est une 404
                http_response_code(404);
                echo "Page not found";
            }
        } else {
            // Page d'accueil
            // On instancie le bon controller
            $controller = new MainController();

            // On appelle la méthode index
            $controller->index();
        }
    }
}
