<?php
/*********************************************************
 *        Bibliothèque de fonctions spécifiques          *
 *        à l'application eResto-U                       *
 *********************************************************/

// Force l'affichage des erreurs
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting( E_ALL );

// Phase de développement (IS_DEV = true) ou de production (IS_DEV = false)
define ('IS_DEV', true);

/** Constantes : les paramètres de connexion au serveur MariaDB */
define ('BD_NAME', 'e_RestoU_bd');
define ('BD_USER', 'e_RestoU_user');
define ('BD_PASS', 'e_RestoU_pass');
// define ('BD_NAME', 'merlet_erestou');
// define ('BD_USER', 'merlet_u');
// define ('BD_PASS', 'merlet_p');
define ('BD_SERVER', 'localhost');

// Définit le fuseau horaire par défaut à utiliser. Disponible depuis PHP 5.1
date_default_timezone_set('Europe/Paris');

define ('DATE_AUJOURDHUI', date('Ymd'));
define ('ANNEE_MAX', intdiv(DATE_AUJOURDHUI, 10000));
define ('ANNEE_MIN', ANNEE_MAX - 1);

// Nombre de plats de catégorie 'boisson'
define ('NB_CAT_BOISSON', 4);


//_______________________________________________________________
/**
 * Affiche le début du code HTML d'une page (de l'élément DOCTYPE jusqu'au tag de fermeture de l'élément header)
 *
 * @param  string  $title       Le titre de la page (<head> et <h1>)
 * @param  string  $css         Le nom du fichier de feuille de styles à inclure (situé dans le répertoire styles)
 * @param  string  $prefixe     Préfixe à utiliser pour construire les chemins par rapport à la page appelante (chemin vers le répertoire racine de l'application).
 *
 * @return void
 */
function affEntete(string $titre, string $css = 'eResto.css', string $prefixe = '..'): void {
    
    echo    '<!doctype html>',
            '<html lang="fr">',
                '<head>',
                    '<meta charset="UTF-8">',
                    '<title>eRestoU | ', $titre, '</title>',
                    '<link rel="stylesheet" type="text/css" href="', $prefixe, '/styles/', $css, '">',
                '</head>',
                '<body>',
                    '<div id="bcContenu">',
                        '<header>',
                            '<img src="', $prefixe,'/images/logo-eRestoU.png" id="logoRestoU" alt="Logo eResto-U">',
                            '<aside>Le resto-U 100% digital</aside>',
                            '<h1>', $titre, '</h1>',
                            '<a href="http://www.crous-bfc.fr" target="_blank"></a>',
                            '<a href="http://www.univ-fcomte.fr" target="_blank"></a>',
                        '</header>';
}


//_______________________________________________________________
/**
 *  Génération de la barre de navigation (élément nav)
 *
 * @param  string   $prefixe    Préfixe à utiliser pour construire les chemins par rapport à la page appelante (chemin vers le répertoire racine de l'application).
 *
 * @return  void
 */
function affNav(string $prefixe = '..'): void {
    echo '<nav>',
            '<ul>',
                '<li><a href="', $prefixe, '/index.php"><span>&#x2630;</span> Accueil</a></li>',
                '<li><a href="', $prefixe, '/php/menu.php"><span>&#x2630;</span> Menus et repas</a></li>',
                '<li><a href="', $prefixe, '/php/connexion.php"><span>&#x2630;</span> Connexion</a></li>',
            '</ul>',
        '</nav>',
        '<main>';
}


//_______________________________________________________________
/**
 *  Génération du pied de page.
 *
 * @return  void
 */
function affPiedDePage() : void{
    echo    '</main>',
            '<footer>&copy; Licence Informatique - Février 2023 - Université de Franche-Comté - CROUS de Franche-Comté</footer>',
        '</div>',
    '</body>',
    '</html>';
}
