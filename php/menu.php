<?php

// chargement des bibliothèques
require_once('bibli_generale.php');
require_once('bibli_erestou.php');

// Header
$cheminRelatif = '..';
$cheminStyles = 'styles/eResto.css';
affEntete ("Menu", $cheminStyles, $cheminRelatif);
// Barre de navigation
affNav('../index.php', '#', '../connexion.php');

function affMenu () {
	$bd = bdConnect();
	$sql = 'SELECT plID, plNom, plCategorie, plCalories, plCarbone FROM plat LEFT OUTER JOIN menu on plID = mePlat WHERE meDate = 20230320;';
	$r = bdSendRequest($bd, $sql);
	//-- Traitement -------------------------------------
	echo '<ul style="list-style-type:disc;">';
	while ($enr = mysqli_fetch_assoc($r)) {
		echo '<li>', 'id = ', $enr['plID'], ', nom = ',$enr['plNom'], ', categorie = ', $enr['plCategorie'], ', apport énergétique = ', $enr['plCalories'], ', empreinte carbone = ', $enr['plCarbone'], '</li>';
	}	
	echo '</ul>';

	// Libération de la mémoire associée au résultat de la requête
	mysqli_free_result($r);
	mysqli_close($bd);
}



function affMenuCategorie () {
	$listeMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ];
	$jourj = getdate();
	$validiteDate = true;
	$a = $jourj['year'];
	$m = $jourj['mon'];
	$j = $jourj['mday'];
	$enToFr = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];
	
	/* ---------------- BOUCLE DE LA VALIDITÉ D'UNE DATE ---------------------- */
	// boucle de lecture permettant de vérifier la validité d'une date
	// PS : les samedis, dimanches ne sont pas des jours ouvrées
	foreach ($_GET as $key => $valeur) {
		if ($key != 'jour' && $key != 'mois' && $key != 'annee'){
			$message = "Erreur : le champ '$key' n'est pas valide.";
			$validiteDate = false;
			break;
		}
		if (!ctype_digit($valeur)) {
			$validiteDate = false;
			$message = "Erreur : le champ '$key' n'est pas un entier.";
			break;
		}
		if ($key == 'jour') {
			//verification validite du jour
			$j = $valeur;
		}elseif ($key == 'mois') {
			//verification validite du mois
			$m = $valeur;
		}elseif ($key == 'annee') {
			//verification validite de l'annee
			if (strlen($valeur) != 4) {
				$validiteDate = false;
				$message = "Erreur : le champ '$key' n'est pas sur 4 chiffres.";
				break;
			}
			if ($valeur != 2022 && $valeur != 2023) {
				$validiteDate = false;
				$message = "Erreur : le champ '$key' n'est pas égal à 2022 ou 2023.";
				break;
			}
			$a = $valeur;
		}
	}
	
	// On créer une nouvelle date (ça se fera dynamiquement ensuite)
	$Date = "$j-$m-$a";
	$creerDate = date_create_from_format('d-m-Y', $Date);
	
	if ($creerDate == true && checkdate($m, $j, $a)) {
		$jour = date_format($creerDate, 'l');
		if($jour == 'Saturday' || $jour == 'Sunday'){
			$validiteDate = false;
			$message = "Aucun repas n'est servi ce jour.";
		}
		echo '<h2>', $enToFr[$jour],' ',$j,' ', $listeMois[$m - 1],' ', $a, '</h2>';
		$prev_day = clone $creerDate;
		$prev_day->modify('-1 day');

		$prev_week = date_format($prev_day, 'l');
		if($prev_week == 'Sunday'){
			$prev_day->modify('-2 day');
		}else if($prev_week == 'Saturday'){
			$prev_day->modify('-1 day');
		}
		$next_day = clone $creerDate;
		$next_day->modify('+1 day');

		$next_week = date_format($next_day, 'l');
		if($next_week == 'Sunday'){
			$next_day->modify('+1 day');
		}else if($next_week == 'Saturday'){
			$next_day->modify('+2 day');
		}
		$j_next = date_format($next_day, 'j');
		$m_next = date_format($next_day, 'n');
		$a_next = date_format($next_day, 'Y');
		$j_prev = date_format($prev_day, 'j');
		$m_prev = date_format($prev_day, 'n');
		$a_prev = date_format($prev_day, 'Y');

	} else {
		$validiteDate = false;
		$message = "La date '$Date' est invalide.";    
	}
	/* ---------------- BOUCLE DE LA VALIDITÉ D'UNE DATE ---------------------- */
	
	/* ---------------- AFFICHAGE DU FORMULAIRE DE DATE
	 * 					AINSI QUE DES DEUX BOUTONS (PREV - NEXT)
	 * ----------------
	 * */
	 // C'est (certes) pas très propre mais ça fonctionne
	 echo
'<form id="navDate" action="menu.php" method="GET">',
    '<a href="menu.php?jour=',$j_prev,'&amp;mois=',$m_prev,'&amp;annee=',$a_prev,'">Jour précédent</a>',
    '<a href="menu.php?jour=',$j_next,'&amp;mois=',$m_next,'&amp;annee=',$a_next,'">Jour suivant</a>',
    'Date :',
    '<select name="jour">',
        '<option value="1">1</option>',
        '<option value="2">2</option>',
        '<option value="3">3</option>',
        '<option value="4">4</option>',
        '<option value="5">5</option>',
        '<option value="6" selected>6</option>',
        '<option value="7">7</option>',
        '<option value="8">8</option>',
        '<option value="9">9</option>',
        '<option value="10">10</option>',
        '<option value="11">11</option>',
        '<option value="12">12</option>',
        '<option value="13">13</option>',
        '<option value="14">14</option>',
        '<option value="15">15</option>',
        '<option value="16">16</option>',
        '<option value="17">17</option>',
        '<option value="18">18</option>',
        '<option value="19">19</option>',
        '<option value="20">20</option>',
        '<option value="21">21</option>',
        '<option value="22">22</option>',
        '<option value="23">23</option>',
        '<option value="24">24</option>',
        '<option value="25">25</option>',
        '<option value="26">26</option>',
        '<option value="27">27</option>',
        '<option value="28">28</option>',
        '<option value="29">29</option>',
        '<option value="30">30</option>',
        '<option value="31">31</option>',
    '</select>',
    '<select name="mois">',
        '<option value="1">janvier</option>',
        '<option value="2">février</option>',
        '<option value="3" selected>mars</option>',
        '<option value="4">avril</option>',
        '<option value="5">mai</option>',
        '<option value="6">juin</option>',
        '<option value="7">juillet</option>',
        '<option value="8">août</option>',
        '<option value="9">septembre</option>',
        '<option value="10">octobre</option>',
        '<option value="11">novembre</option>',
        '<option value="12">décembre</option>',
    '</select>',
    '<select name="annee">',
        '<option value="2022">2022</option>',
        '<option value="2023" selected>2023</option>',
    '</select>',
    '<input type="submit" value="Consulter">',
'</form>',
'<p class="notice">',
    '<img src="../images/notice.png" alt="notice" width="50" height="48">',
    'Tous les plateaux sont composés avec un verre, un couteau, une fouchette et une petite cuillère.',
'</p>'; 
	 
	 /* ---------------- AFFICHAGE DU FORMULAIRE DE DATE
	 * 					AINSI QUE DES DEUX BOUTONS (PREV - NEXT)
	 * ----------------
	 * */
	 
	// DERNIÈRE VÉRIFICATION DE LA VALIDITÉ DE LA DATE
	// ENSUITE : CONNEXION À LA BASE DE DONNÉES
	if (!$validiteDate) {
		echo '<p>',$message,'</p>';
		// autant quitter direct
		die(); 
	}
	// CONNEXION À LA BASE DE DONNÉES
	$bd = bdConnect();  
	if ($m < 10) {
		$m = "0$m";
	}
	if ($j < 10) {
		$j = "0$j";
	}
	// avant toute chose, on doit effectuer un rapide formatage de la date
	// dans la BD, la date est sous la forme AAAAMMJJ
	// étant donné que la boucle précédente nous donne une valeur numérique de ces champs
	// on a plus qu'à créer une variable pour avoir la date au bon format, like : 
	$date = "$a$m$j"; 
	$sql = "SELECT plID, plNom, plCategorie, plCalories, plCarbone FROM plat LEFT OUTER JOIN menu ON plID = mePlat WHERE meDate = $date OR meDate IS NULL AND plCategorie IN ('boisson');";
	$r = bdSendRequest($bd, $sql);
	$categories = array(
		'entree' => array(),
		'plat' => array(),
		'accompagnement' => array(),
		'dessert' => array(),
		'boisson' => array()
	);
	while ($enr = mysqli_fetch_assoc($r)) {
		if ($enr['plCategorie'] == 'viande' || $enr['plCategorie'] == 'poisson') {
            $categories['plat'][] = $enr;
        } else if($enr['plCategorie'] == 'fromage') {
            $categories['dessert'][] = $enr;
            
        } else {
            $categories[$enr['plCategorie']][] = $enr;
            
        }
	}
	$enritre="";
	// BOUCLE D'AFFICHAGE DES RÉSULTATS DE LA REQUÊTE SQL
	// MÊME AFFICHAGE QUE LE TP2
	foreach ($categories as $cat => $plats) {
		// trier les plats en fonction de leur catégorie
		switch ($cat) {
			case 'entree':
				$enritre = "Entrées";
				$bouton = 'radio';
				break;
			case 'viande':
			case 'poisson':
			case 'plat';
				$enritre = "Plats";
				$bouton = 'radio';
				break;
			case 'accompagnement':
				$enritre = "Accompagnement(s)";
				$bouton = 'checkbox';
				break;
			case 'fromage':
			case 'dessert':
				$enritre = "Fromage/dessert";
				$bouton = 'radio';
				break;
			case 'boisson':
				$enritre = "Boissons";
				$bouton = 'radio';
				break;
		}
		echo '<section class="bcChoix">', '<h3>', $enritre, '</h3>';
		foreach ($plats as $pl) {
			echo 
				'<input type="', $bouton, '" id="', $pl['plID'], '" name="', $cat, '" ', ($bouton == 'radio')? "checked" : "",'>',
				'<label for="', $pl['plID'], '"><img src="../images/repas/', $pl['plID'] ,'.jpg" alt=""><br>', $pl['plNom'], '<br><span>', $pl['plCarbone'], 'kg eqCO2 / ', $pl['plCalories'], 'kcal</span></label>';
					
		}
		echo '</section>';
	}
	mysqli_free_result($r);
	mysqli_close($bd);
}
// Menu sans style, juste récupération de la requête SQL en liste à puces
//affMenu();
// Menu avec style
affMenuCategorie();
// Footer
affPiedDePage();
