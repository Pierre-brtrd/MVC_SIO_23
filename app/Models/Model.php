<?php

namespace App\Models;

use App\Core\Db;
use PDOStatement;

class Model extends Db
{
    // Table de la base de données
    protected string $table;

    // Instance de Db
    protected Db $db;

    /**
     * Récupère toutes ls enttrées d'une table
     *
     * @return array|boolean
     */
    public function findAll(): array|bool
    {
        $query = $this->runQuery('SELECT * FROM ' . $this->table);

        return $query->fetchAll();
    }

    /**
     * Recherche une entrée par son id
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->runQuery("SELECT * FROM $this->table WHERE id = ?", [$id])->fetch();
    }

    /**
     * Fonction de recherche par filtre sur un ou plusieurs champs
     *
     * @param array $criteres
     * @return array|boolean
     */
    public function findBy(array $criteres): array|bool
    {
        $champs = [];
        $valeurs = [];

        foreach ($criteres as $champ => $valeur) {
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        // On transforme le tableau de champ en chaîne de caractères
        $strChamp = implode(' AND ', $champs);

        return $this->runQuery("SELECT * FROM $this->table  WHERE $strChamp", $valeurs)->fetchAll();
    }

    /**
     * Créer une entrée en base de données
     *
     * @param Model $model
     * @return PDOStatement|bool
     */
    public function create(): PDOStatement|bool
    {
        $champs = [];
        $markeurs = [];
        $valeurs = [];

        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != 'table' && $champ != 'db') {
                $champs[] = "$champ";
                $markeurs[] = "?";
                $valeurs[] = $valeur;
            }
        }

        $strChamp = implode(', ', $champs);
        $strMarkeur = implode(', ', $markeurs);

        return $this->runQuery("INSERT INTO $this->table ($strChamp) VALUES ($strMarkeur)", $valeurs);
    }

    /**
     * Mettre à jour une entrée de la table avec l'id
     *
     * @return PDOStatement|bool
     */
    public function update(int $id): PDOStatement|bool
    {
        $champs = [];
        $valeurs = [];

        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != 'table' && $champ != 'db' && $champ != 'id') {
                $champs[] = "$champ = :$champ";
                $valeurs[$champ] = is_array($valeur) ? json_encode($valeur) : $valeur;
            }
        }

        $valeurs['id'] = $id;

        $strChamps = implode(', ', $champs);

        return $this->runQuery("UPDATE $this->table SET $strChamps WHERE id = :id", $valeurs);
    }

    /**
     * Supprime une entrée de la table
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id)
    {
        return $this->runQuery("DELETE FROM $this->table WHERE id = ?", [$id]);
    }

    /**
     * Création d'un objet depuis un tableau
     *
     * @param mixed $datas
     * @return self
     */
    public function hydrate(mixed $datas): self
    {
        foreach ($datas as $key => $value) {
            // On récupère le nom du setter
            $setter = 'set' . ucfirst($key);

            // On vérifie que le setter exist
            if (method_exists($this, $setter)) {
                // On execute le setter
                $this->$setter($value);
            }
        }

        return $this;
    }

    /**
     * Fonction pour lancer un requête en base de données
     *
     * @param string $sql
     * @param array|null $attributs
     * @return PDOStatement|boolean
     */
    protected function runQuery(string $sql, array $attributs = null): PDOStatement|bool
    {
        // On recupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie les attributs
        if ($attributs !== null) {
            // Requetes préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributs);

            return $query;
        } else {
            // Reqête simple
            return $this->db->query($sql);
        }
    }
}
