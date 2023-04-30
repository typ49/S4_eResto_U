<?php
// Inclure les fonctions nécessaires
require_once 'bibli_generale.php';
require_once 'bibli_erestou.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est authentifié
if (!estAuthentifie()) {
    // Si l'utilisateur n'est pas authentifié, redirigez-le vers la page de connexion
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID de l'utilisateur authentifié
$userId = $_SESSION['id'];

// Se connecter à la base de données
$bd = bdconnect();

// Récupérer les informations de l'utilisateur authentifié à partir de la table usager
$sql = "SELECT * FROM usager WHERE usID = $userId";
$result = bdSendRequest($bd, $sql);
$userData = mysqli_fetch_assoc($result);

// Fermer la connexion à la base de données
mysqli_close($bd);

// Afficher les informations de l'utilisateur authentifié

affEntete('Page protégée');
affNav();

echo '<h2>Informations de l\'utilisateur authentifié</h2>',

'<p>ID de l\'utilisateur : ' . $userId . '</p>',
'<p>Identifiant de session (SID) : ' . session_id() . '</p>',

'<h3>Informations de l\'utilisateur dans la table "usager"</h3>',
'<table border="1">',
'<tr><th>Champ</th><th>Valeur</th></tr>';
foreach ($userData as $key => $value) {
    echo '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
}
echo '</table>';

affPiedDePage();
