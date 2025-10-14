<?php


// On importe la classe globale PDO pour pouvoir l'utiliser
// comme type de retour dans la signature de la méthode.
use PDO;

interface DatabaseInterface {
    public function getPdo(): PDO;
}