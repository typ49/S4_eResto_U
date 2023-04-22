<?php
// Connexion à la base de données
require_once 'bibli_generale.php';
require_once 'bibli_erestou.php';


ob_start();
// Variables pour stocker les éventuelles erreurs
$erreurs = [];

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $login = trim($_POST['login']);
    $motDePasse = $_POST['motDePasse'];
    $motDePasseConfirmation = $_POST['motDePasseConfirmation'];
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $dateNaissance = trim($_POST['dateNaissance']);

    // Vérification si les champs du formulaire sont tous remplis
    if (empty($login) || empty($motDePasse) || empty($motDePasseConfirmation) || empty($nom) || empty($prenom) || empty($email) || empty($dateNaissance)) {
        $erreurs[] = 'Tous les champs du formulaire doivent être remplis.';
    } else {
        // Vérification du login
        if (!preg_match('/^[a-z][a-z0-9]{3,7}$/', $login)) {
            $erreurs[] = 'Le login doit être constitué de 4 à 8 lettres minuscules sans accents ou chiffres, et doit commencer par une lettre.';
        } else {
            // Vérification si le login est déjà utilisé
            $requete = $pdo->prepare('SELECT COUNT(*) FROM usager WHERE login = ?');
            $requete->execute([$login]);
            $count = $requete->fetchColumn();
            if ($count > 0) {
                $erreurs[] = 'Le login est déjà utilisé.';
            }
        }

        // Vérification du mot de passe
        if (strlen($motDePasse) < 4 || strlen($motDePasse) > 20 || $motDePasse !== $motDePasseConfirmation) {
            $erreurs[] = 'Le mot de passe doit être constitué de 4 à 20 caractères et les deux mots de passe saisis doivent être identiques.';
        }

        // Vérification du nom et prénom
        if (!preg_match('/^[a-zA-Z\s\'-]+$/', $nom) || !preg_match('/^[a-zA-Z\s\'-]+$/', $prenom)) {
            $erreurs[] = 'Le nom et prénom ne doivent contenir que des lettres éventuellement séparées par un espace, un tiret ou une simple quote.';
        }

        // Vérification de l'adresse email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L\'adresse email n\'est pas valide.';
        } else {
            // Vérification si l'adresse email est déjà utilisée
            $requete = $pdo->prepare('SELECT COUNT(*) FROM usager WHERE email = ?');
            $requete->execute([$email]);
            $count = $requete->fetchColumn();
            if ($count > 0) {
                $erreurs[] = 'L\'adresse email est déjà utilisée dans notre base de données.';
            }
        }
    }
    // Vérification de la date de naissance
    if (!checkdate(date('m', strtotime($dateNaissance)), date('d', strtotime($dateNaissance)), date('Y', strtotime($dateNaissance)))) {
        $erreurs[] = 'La date de naissance n\'est pas valide.';
    } else {
        // Vérification si l'utilisateur a au moins 16 ans
        $dateNaissance = new DateTime($dateNaissance);
        $aujourdHui = new DateTime();
        $difference = $aujourdHui->diff($dateNaissance);
        if ($difference->y < 16) {
            $erreurs[] = 'Vous devez avoir au moins 16 ans pour vous inscrire.';
        }
    }
}

// Si aucune erreur, on insère les données dans la base de données
if (empty($erreurs)) {
    // Insertion des données dans la table usager
    $requete = $pdo->prepare('INSERT INTO usager (login, mot_de_passe, nom, prenom, email, date_naissance) VALUES (?, ?, ?, ?, ?, ?)');
    $requete->execute([$login, $motDePasse, $nom, $prenom, $email, $dateNaissance->format('Y-m-d')]);

    // Redirection vers une page de succès
    header('Location: inscription_reussie.php');
    exit();
}