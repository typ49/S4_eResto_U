<?php
require_once 'bibli_generale.php';
require_once 'bibli_erestou.php';

// Connexion à la base de données
ob_start();
$bd = bdconnect();
// Variables pour stocker les éventuelles erreurs
$erreurs = [];

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $login = trim($_POST['login']);
    $passe1 = $_POST['passe1'];
    $passe2 = $_POST['passe2'];
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $naissance = trim($_POST['naissance']);

    // Vérification anti-piratage
    $pirate = parametresControle('post', ['login','passe1','passe2','nom','prenom','email','naissance']);
    if ($pirate===0) {
        header('location: ../index.php');
    }

    // Vérification si les champs du formulaire sont tous remplis
    if (empty($login) || empty($passe1) || empty($passe2) || empty($nom) || empty($prenom) || empty($email) || empty($naissance)) {
        $erreurs[] = 'Tous les champs du formulaire doivent être remplis.';
    
    }
    
    // Vérification si le login est valide
    if (preg_match('/^[a-z][a-z0-9]{3,7}$/', $login) === 0) {
        $erreurs[] = 'Le login doit contenir entre 4 et 8 lettres minuscules sans accent, ou chiffres.';
    
    }
    $sql_login = "SELECT COUNT(*) as count FROM usager WHERE usLogin = '$login'"; 
    $req_login = bdSendRequest($bd, $sql_login);
    $res_login = mysqli_fetch_assoc($req_login);
    if ($res_login['count'] != 0) {
        $erreurs[] = 'le login est deja utilise.';
    
    }

    // Vérifier le mot de passe
    if (strlen($passe1)<4 || strlen($passe1)>20) {
        $erreurs[] = 'Le mot de passe doit contenir entre 4 et 20 caractères.';
    }
    if ($passe1 != $passe2) {
        $erreurs[] = 'Le mot de passe et le mot de passe de confirmation ne sont pas identique.';
    }

    // Vérification du nom et du prénom
    if (strip_tags($nom) != $nom || strip_tags($prenom) != $prenom) {
        $erreurs[] = 'Le nom et le prénom ne doivent pas contenir de balise HTML';
    }
    if (preg_match('/^[a-zA-Z \'-]{1,}$/', $nom)===0 || preg_match('/^[a-zA-Z \'-]{1,}$/', $prenom)===0) {
        $erreurs[] = 'Le nom et le prénom ne doivent contenir que des lettres éventuellement séparés par un espace, un tiret ou une simple quote.';
    }
    if (strlen($nom) > 50) {
        $erreurs[] = 'Le nom est trop long (>50).';
    }
    if (strlen($prenom) > 50) {
        $erreurs[] = 'Le prenom est trop long (>50).';
    }

    // Vérification de l'adresse mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL)===0) {
        $erreurs[] = "L'adrresse mail est invalide.";
    }
    if (strlen($email) > 80) {
        $erreurs[] = "l'adresse mail est trop longue (>80).";
    }
    $sql_mail = "SELECT COUNT(*) as count FROM usager WHERE usMail = '$email'"; 
    $req_mail = bdSendRequest($bd, $sql_mail);
    $res_mail = mysqli_fetch_assoc($req_mail);
    if ($res_mail['count'] != 0) {
        $erreurs[] = "l'adresse email est deja utilise.";
    }

    // Vérification de la date de naissance
    $birthday = getJourMoisAnneeFromDate((int)$naissance);
    if (checkdate($birthday[1], $birthday[0], $birthday[2]) === 0) {
        $erreurs[] = 'La date de naissance est invalide.';
    }
    $naissance = new DateTime($naissance);
    $aujourdhui = new dateTime();
    $age = $aujourdhui->diff($naissance);
    if ($age->y < 16) {
        $erreurs[] = 'Vous devez avoir au moins 16 ans pour vous inscrire. Petit fripons !';
    }


    // afficher les erreurs éventuelles
    if (empty($erreurs)) {
        echo 'Aucune erreur de saisie';
    }
    else {
        $affErr = '<ul>';
        foreach($erreurs as $err) {
            $affErr .= '<li>'.$err.'</li>';
        }
        $affErr .='</ul>';
        echo $affErr;
    }
}