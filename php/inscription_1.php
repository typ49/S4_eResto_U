<?php

// chargement des bibliothèques de fonctions
require_once 'bibli_erestou.php';
require_once 'bibli_generale.php';

// bufferisation des sorties
ob_start();

// affichage de l'entête
affEntete('Réception des données saisies');
// affichage de la barre de navigation
affNav();

echo '<h3>Avec une boucle foreach</h3>';

foreach($_POST as $key => $value) {
    echo $key . " = " . $value . "<br>";
}

echo '<h3>Avec var_dump()</h3>';

echo '<pre>';
var_dump($_POST);
echo '</pre>';

echo '<h3>Avec print_r()</h3>';

echo '<pre>';
print_r($_POST);
echo '</pre>';