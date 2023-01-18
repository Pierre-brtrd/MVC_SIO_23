<?php

namespace App\Controllers;

use App\Models\PosteModel;

class PostesController extends Controller
{
    public function __construct(
        private PosteModel $model = new PosteModel(),
    ) {
    }

    /**
     * Affiche les postes
     *
     * @return void
     */
    public function index()
    {
        // On récupère tous les postes
        $postes = $this->model->findBy(['actif' => true]);

        $this->render('Postes/index.php', [
            'postes' => $postes
        ]);
    }

    /**
     * Afficher une page pour un article
     *
     * @param integer $id
     * @return void
     */
    public function details(int $id)
    {
        $this->render('Postes/details.php', [
            'poste' => $this->model->find($id)
        ]);
    }
}
