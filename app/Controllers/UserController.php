<?php

namespace App\Controllers;

use App\Core\Form;
use App\Models\UserModel;

class UserController extends Controller
{
    /**
     * Affiche la page de connexion user
     */
    public function login()
    {
        if (Form::validate($_POST, ['email', 'password'])) {
            // On vérifie l'email
            $userModel = new UserModel();
            $user = $userModel->findOneByEmail(strip_tags($_POST['email']));

            if (!$user) {
                $_SESSION['messages']['error'] = "Identifiants incorrects";

                header('Location: /user/login');
                exit();
            }

            /**
             * L'email est en BDD vérif du password
             * 
             * @var UserModel $user
             */
            $user = $userModel->hydrate($user);

            if (password_verify($_POST['password'], $user->getPassword())) {
                // On connecte l'utilisateur
                $user->setSession();

                header('Location:  /');
                exit();
            } else {
                $_SESSION['messages']['error'] = "Identifiants incorrects";

                header('Location: /user/login');
                exit();
            }
        }

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

    /**
     *  Page de création d'un user
     *
     * @return void
     */
    public function register()
    {
        // Validation du formullaire
        if (Form::validate($_POST, ['nom', 'prenom', 'email', 'password'])) {
            // Le formualire est valide
            // On nettoie les champs
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            // On chiffre le password
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);

            // On hydrate l'objet
            $user = new UserModel();
            $user
                ->setNom($nom)
                ->setPrenom($prenom)
                ->setEmail($email)
                ->setPassword($pass);

            // On envoi le user en BDD
            $user->create();

            $_SESSION['messages']['success'] = "Vous êtes bien inscrit à notre application";

            header('Location: /user/login');
            exit();
        } else {
            if (!empty($_POST)) {
                $_SESSION['messages']['error'] = 'Le formulaire est incomplet';
            }

            $email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
            $nom = isset($_POST['nom']) ? strip_tags($_POST['nom']) : '';
            $prenom = isset($_POST['prenom']) ? strip_tags($_POST['prenom']) : '';
        }

        $form = new Form();

        $form
            ->startForm('POST', '', ['class' => 'form card p-3'])
            ->startDiv(['class' => 'row'])
            ->startDiv(['class' => 'col-md-6 form-group'])
            ->addLabel('nom', 'Votre nom:', ['class' => 'form-label'])
            ->addInput('text', 'nom', [
                'class' => 'form-control',
                'id' => 'nom',
                'required' => true,
                'placeholder' => 'Doe',
                'value' => $nom,
            ])
            ->endDiv()
            ->startDiv(['class' => 'col-md-6 form-group'])
            ->addLabel('prenom', 'Votre prénom:', ['class' => 'form-label'])
            ->addInput('text', 'prenom', [
                'class' => 'form-control',
                'id' => 'prenom',
                'required' => true,
                'placeholder' => 'John',
                'value' => $prenom,
            ])
            ->endDiv()
            ->endDiv()
            ->startDiv(['class' => 'form-group mt-2'])
            ->addLabel('email', 'Votre email:', ['class' => 'form-label'])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'id' => 'email',
                'required' => true,
                'placeholder' => 'john.doe@exemple.com',
                'value' => $email,
            ])
            ->endDiv()
            ->startDiv(['class' => 'form-group mt-2'])
            ->addLabel('password', 'Votre password:', ['class' => 'form-label'])
            ->addInput('password', 'password', [
                'class' => 'form-control',
                'id' => 'password',
                'required' => true,
                'placeholder' => 'S3CR3T',
            ])
            ->endDiv()
            ->addButton('Inscription', [
                'type' => 'submit',
                'class' => 'btn btn-primary mt-4 mx-auto',
            ])
            ->endForm();

        $this->render('User/register.php', [
            'form' => $form->create(),
        ]);
    }

    /**
     * Déconnecte l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['user']);

        $url = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '/';

        header('Location:' . $url);
        exit();
    }
}
