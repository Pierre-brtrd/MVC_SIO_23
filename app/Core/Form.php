<?php

namespace App\Core;

/**
 * Class de génération de formulaire
 */
class Form
{
    /**
     * Code Html du formulaire
     *
     * @var string
     */
    private string $formCode = '';

    /**
     * Validation du formulaire (si tous les champs sont remplis)
     *
     * @param array $form Taableau issu $_POST || $_GET
     * @param array $champs Tableau listant les champs obligatoire
     * @return bool
     */
    public static function validate(array $form, array $champs): bool
    {
        // On parcourt les champs
        foreach ($champs as $champ) {
            // Si le champ est absent ou vide dans le formulaire
            if (
                !isset($form[$champ]) ||
                empty($form[$champ]) ||
                strlen(trim($form[$champ]) == 0)
            ) {
                // On sort en retournant false
                return false;
            }
        }

        return true;
    }

    /**
     * Crée la balise d'ouverture du formulaire
     *
     * @param string $method methode du formulaire (POST, GET)
     * @param string $action action du formulaire
     * @param array $attributs
     * @return self
     */
    public function startForm(string $method = "POST", string $action = '#', array $attributs = []): self
    {
        // On crée la balise form
        $this->formCode .= "<form action='$action' method='$method'";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '>' : '>';

        return $this;
    }

    /**
     * Crée la balise de fermeture du form
     * 
     * @return self
     */
    public function endForm(): self
    {
        $this->formCode .= "</form>";

        return $this;
    }

    /**
     * Ouvre la balise div
     *
     * @param array $attributs
     * @return self
     */
    public function startDiv(array $attributs = []): self
    {
        // On ajoute la balise div au formulaire
        $this->formCode .= "<div";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '>' : '>';

        return $this;
    }

    /**
     * Fermeture de la div
     *
     * @return self
     */
    public function endDiv(): self
    {
        $this->formCode .= '</div>';

        return $this;
    }

    /**
     * Créer une balise label
     *
     * @param string $for
     * @param string $text
     * @param array $attributs
     * @return self
     */
    public function addLabel(string $for, string $text, array $attributs = []): self
    {
        $this->formCode .= "<label for=\"$for\"";

        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        $this->formCode .= ">$text</label>";

        return $this;
    }

    /**
     * Créer un input
     *
     * @param string $type
     * @param string $name
     * @param array $attributs
     * @return self
     */
    public function addInput(string $type, string $name, array $attributs = []): self
    {
        $this->formCode .= "<input type=\"$type\" name=\"$name\"";
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '/>' : '/>';

        return $this;
    }

    /**
     * Crée une balise TextArea pour un formulaire
     *
     * @param string $nom
     * @param string $valeur
     * @param array $attributs
     * @return self
     */
    public function addTextarea(string $name, string $valeur = '', array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<textarea name=\"$name\"";
        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';
        // On ajoute le texte
        $this->formCode .= ">$valeur</textarea>";

        return $this;
    }

    /**
     * Créer une balise select
     *
     * @param string $name
     * @param array $options
     * @param array $attributs
     * @return self
     */
    public function addSelect(string $name, array $options, array $attributs = []): self
    {
        $this->formCode .= "<select name='$name'";
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '> ' : '>';

        foreach ($options as $valeur => $text) {
            $this->formCode .= "<option value='$valeur'>$text</option>";
        }

        $this->formCode .= '</select>';

        return $this;
    }

    /**
     * Créer un bouton
     *
     * @param string $text
     * @param array $attributs
     * @return self
     */
    public function addButton(string $text, array $attributs = []): self
    {
        $this->formCode .= "<button";

        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        $this->formCode .= ">$text</button>";

        return $this;
    }

    /**
     * Ajoute les attributs envoyés à la balise html
     *
     * @param array $attributs Tableau associatif (['class' => 'form-control', 'require' => true])
     * @return string
     */
    private function addAttributs(array $attributs): string
    {
        // Initialization d'une chaîne de caractère vude
        $str = '';

        // On liste les attributs courts
        $courts = ['checked', 'selected', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];

        // On parcourt le tableau d'attributs
        foreach ($attributs as $attribut => $value) {
            // Vérifier si c'est un attribut court
            if (in_array($attribut, $courts) && $value == true) {
                $str .= " $attribut";
            } else {
                // On ajout l'attribut long
                $str .= " $attribut=\"$value\"";
            }
        }

        // On retourne la chaîne de caractère
        return $str;
    }

    /**
     * Génère et envoie le code HTML du formulaire
     *
     * @return string
     */
    public function create(): string
    {
        return $this->formCode;
    }
}
