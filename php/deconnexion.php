<?php
// Inclure la fonction sessionExit si elle n'est pas déjà incluse
require_once 'bibli_generale.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est authentifié
if (estAuthentifie()) {
    // Si l'utilisateur est authentifié, terminez la session et redirigez-le vers la page index.php
    sessionExit('../index.php');
} else {
    // Si l'utilisateur n'est pas authentifié, redirigez-le simplement vers la page index.php
    header('Location: ../index.php');
    exit();
}
