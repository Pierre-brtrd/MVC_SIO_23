<?php

namespace App\Controllers;

use App\Models\PosteModel;

class MainController extends Controller
{
    public function index()
    {
        $posteModel = new PosteModel();

        $this->render('Main/index.php', [
            'postes' => $posteModel->findAll(),
        ]);
    }
}
