<?php

// chargement des bibliothèques de fonctions
require_once 'bibli_erestou.php';
require_once 'bibli_generale.php';

// bufferisation des sorties
ob_start();

// affichage de l'entête
affEntete('Inscription');
// affichage de la barre de navigation
affNav();

echo
'<h3>Formulaire d\'inscription</h3>',
'<p>Pour vous inscrire, merci de fournir les informations suivantes.</p>';

echo
// '<form method="post" action="inscription_1.php" id="inscription_form">',
// '<form method="post" action="inscription_2.php" id="inscription_form">',
'<form method="post" action="inscription_3.php" id="inscription_form">',
    '<table>',
        '<tr>',
            '<td>',
                '<label for="login">Votre login : </label>',
            '</td>',
            '<td>',
                '<input type="text" id="login" name="login" placeholder="4 à 8 lettres minuscules ou chiffres">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="passe1">Votre mot de passe : </label>',
            '</td>',
            '<td>',
                '<input type="password" id="passe1" name="passe1" placeholder="4 caractères minimum">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="passe2">Répétez le mot de passe :</label>',
            '</td>',
            '<td>',
                '<input type="password" id="passe2" name="passe2">',
                
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="nom">Votre nom : </label>',
            '</td>',
            '<td>',
                '<input type="text" id="nom" name="nom">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="prenom">Votre prenom : </label>',
            '</td>',
            '<td>',
                '<input type="text" id="prenom" name="prenom">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="email">Votre adresse email : </label>',
            '</td>',
            '<td>',
                '<input type="email" id="email" name="email">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td>',
                '<label for="naissance">Votre date de naissance : </label>',
            '</td>',
            '<td>',
                '<input type="date" id="naissance" name="naissance">',
            '</td>',
        '</tr>',
        '<tr>',
            '<td colspan="2" id="fusion">',
                '<input type="submit" id="btnInscription" name="btnInscription" value="S\'inscrire">',
                '<input type="reset" value="Réinitialiser">',
            '</td>',
        '</tr>',
    '</table>',
'</form>';

affPiedDePage();