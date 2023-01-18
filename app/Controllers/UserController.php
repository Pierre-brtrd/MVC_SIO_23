<?php

namespace App\Controllers;

use App\Core\Form;

class UserController extends Controller
{
    /**
     * Affiche la page de connexion user
     */
    public function login()
    {
        $form = new Form();

        $form
            ->startForm('POST', '#', [
                'class' => 'form card p-3',
                'id' => 'login-form'
            ])
            ->startDiv(['class' => 'form-group'])
            ->addLabel('email', 'Email:', ['class' => 'form-label'])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'placeholder' => 'you@exemple.com',
                'id' => 'email',
                'required' => true,
            ])
            ->endDiv()
            ->startDiv(['class' => 'form-group'])
            ->addLabel('password', 'Mot de passe:', ['class' => 'form-label'])
            ->addInput('password', 'password', [
                'class' => 'form-control',
                'placeholder' => 'S3CR3T',
                'id' => 'password',
                'required' => true,
            ])
            ->endDiv()
            ->addButton('Se connecter', [
                'type' => 'submit',
                'class' => 'btn btn-primary mt-4',
            ])
            ->endForm();

        $this->render('User/login.php', [
            'form' => $form->create(),
        ]);
    }
}
