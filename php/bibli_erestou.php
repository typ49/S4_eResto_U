<?php
function affEntete($titre, $cheminStyles, $cheminRelatif) {
	// Afficher les erreurs PHP
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting( E_ALL );
	
	// Afficher l'en-tête de la page
	echo '<!doctype html>',
    '<html lang= "fr">',
    
    
    '<head>',
        '<meta charset="UTF-8">',
        '<title>eRestoU | ',$titre,'</title>',
        '<link rel="stylesheet" type="text/css" href="',$cheminRelatif,'/',$cheminStyles,'">',
    '</head>',
    
    '<body>',
        '<div id="bcContenu">',
            '<header>',
                '<img src="',$cheminRelatif,'/images/logo-eRestoU.png" id="logoRestoU" alt="Logo eResto-U">',
                '<aside>Le resto-U 100% digital</aside>',
                '<h1>',$titre,'</h1>',
                '<a href="http://www.crous-bfc.fr" target="_blank"></a>',
                '<a href="http://www.univ-fcomte.fr" target="_blank"></a>',
            '</header>';
}

function affNav($cheminAcceuil, $cheminMenu, $cheminConnexion) {
    echo '<nav>',
    '<ul>',
        '<li><a href="',$cheminAcceuil,'"><span>&#x2630;</span> Accueil</a></li>',
        '<li><a href="',$cheminMenu,'"><span>&#x2630;</span> Menus et repas</a></li>',
        '<li><a href=',$cheminConnexion,'><span>&#x2630;</span> Connexion</a></li>',
    '</ul>',
'</nav>',
'<main>';
}

function affPiedDePage() {
    echo '</main>',
    '<footer>&copy; Licence Informatique - Février 2023 - Université de Franche-Comté - CROUS de Franche-Comté',
    '</footer>',
'</div>',
'</body>',

'</html>';
}

?>
