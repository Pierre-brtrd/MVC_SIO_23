<?php

namespace App\Controllers;

abstract class Controller
{
    public function render(string $template, array $data = [])
    {
        // On extrait le contenu
        extract($data);

        // Démarrage du buffer de sortie
        ob_start();
        // À partir de maintenant toutes les instructions sont conservé en mémoire

        // On met en mémoire la vue de la page
        include_once ROOT . '/Views/' . $template;

        $contenu = ob_get_clean();

        // On crée le chemin vers la vue
        include_once ROOT . '/Views/base.php';
    }
}
