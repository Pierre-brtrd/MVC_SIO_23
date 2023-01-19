<?php

namespace App\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        if ($this->isAdmin()) {
            $this->render('Admin/index.php');
        }
    }

    private function isAdmin(): mixed
    {
        // On vérifie si l'utilisateur est connecté et qu'il est admin
        if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', json_decode($_SESSION['user']['roles']))) {
            // on est admin
            return true;
        } else {
            $_SESSION['messages']['error'] = "Vous n'avez pas les droits";
            header('Location: /user/login');
            exit();
        }
    }
}
