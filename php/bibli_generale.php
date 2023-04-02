<?php
define('IS_DEV', true);
define('BD_SERVER', 'localhost');
define('BD_USER','e_RestoU_user');
define('BD_PASS','e_RestoU_pass');
define('BD_NAME','e_RestoU_bd');

//____________________________________________________________________________
/**
 * Arrêt du script si erreur de base de données
 *
 * Affichage d'un message d'erreur, puis arrêt du script
 * Fonction appelée quand une erreur 'base de données' se produit :
 *      - lors de la phase de connexion au serveur MySQL ou MariaDB
 *      - ou lorsque l'envoi d'une requête échoue
 *
 * @param array    $err    Informations utiles pour le débogage
 *
 * @return void
 */
function bdErreurExit(array $err): void
{
    ob_end_clean(); // Suppression de tout ce qui a pu être déja généré

    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">',
        '<title>Erreur',
        IS_DEV ? ' base de données' : '', '</title>',
        '</head><body>';
    if (IS_DEV) {
        // Affichage de toutes les infos contenues dans $err
        echo '<h4>', $err['titre'], '</h4>',
            '<pre>',
            '<strong>Erreur mysqli</strong> : ', $err['code'], "\n",
            $err['message'], "\n";
        if (isset($err['autres'])) {
            echo "\n";
            foreach ($err['autres'] as $cle => $valeur) {
                echo '<strong>', $cle, '</strong> :', "\n", $valeur, "\n";
            }
        }
        echo "\n", '<strong>Pile des appels de fonctions :</strong>', "\n", $err['appels'],
            '</pre>';
    } else {
        echo 'Une erreur s\'est produite';
    }

    echo '</body></html>';

    if (!IS_DEV) {
        // Mémorisation des erreurs dans un fichier de log
        $fichier = @fopen('error.log', 'a');
        if ($fichier) {
            fwrite($fichier, '[' . date('d/m/Y') . ' ' . date('H:i:s') . "]\n");
            fwrite($fichier, $err['titre'] . "\n");
            fwrite($fichier, "Erreur mysqli : {$err['code']}\n");
            fwrite($fichier, "{$err['message']}\n");
            if (isset($err['autres'])) {
                foreach ($err['autres'] as $cle => $valeur) {
                    fwrite($fichier, "{$cle} :\n{$valeur}\n");
                }
            }
            fwrite($fichier, "Pile des appels de fonctions :\n");
            fwrite($fichier, "{$err['appels']}\n\n");
            fclose($fichier);
        }
    }
    exit(1); // ==> ARRET DU SCRIPT
}

//____________________________________________________________________________
/**
 *  Ouverture de la connexion à la base de données en gérant les erreurs.
 *
 *  En cas d'erreur de connexion, une page "propre" avec un message d'erreur
 *  adéquat est affiché ET le script est arrêté.
 *
 *  @return mysqli  objet connecteur à la base de données
 */
function bdConnect(): mysqli
{
    // pour forcer la levée de l'exception mysqli_sql_exception
    // si la connexion échoue
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $conn = mysqli_connect(BD_SERVER, BD_USER, BD_PASS, BD_NAME);
    } catch (mysqli_sql_exception $e) {
        $err['titre'] = 'Erreur de connexion';
        $err['code'] = $e->getCode();
        // $e->getMessage() est encodée en ISO-8859-1, il faut la convertir en UTF-8
        $err['message'] = mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1');
        $err['appels'] = $e->getTraceAsString(); //Pile d'appels
        $err['autres'] = array(
            'Paramètres' => 'BD_SERVER : ' . BD_SERVER
            . "\n" . 'BD_USER : ' . BD_USER
            . "\n" . 'BD_PASS : ' . BD_PASS
            . "\n" . 'BD_NAME : ' . BD_NAME
        );
        bdErreurExit($err); // ==> ARRET DU SCRIPT
    }
    try {
        //mysqli_set_charset() définit le jeu de caractères par défaut à utiliser lors de l'envoi
        //de données depuis et vers le serveur de base de données.
        mysqli_set_charset($conn, 'utf8');
        return $conn; // ===> Sortie connexion OK
    } catch (mysqli_sql_exception $e) {
        $err['titre'] = 'Erreur lors de la définition du charset';
        $err['code'] = $e->getCode();
        $err['message'] = mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1');
        $err['appels'] = $e->getTraceAsString();
        bdErreurExit($err); // ==> ARRET DU SCRIPT
    }
}

//____________________________________________________________________________
/**
 * Envoie une requête SQL au serveur de BdD en gérant les erreurs.
 *
 * En cas d'erreur, une page propre avec un message d'erreur est affichée et le
 * script est arrêté. Si l'envoi de la requête réussit, cette fonction renvoie :
 *      - un objet de type mysqli_result dans le cas d'une requête SELECT
 *      - true dans le cas d'une requête INSERT, DELETE ou UPDATE
 *
 * @param   mysqli              $bd     Objet connecteur sur la base de données
 * @param   string              $sql    Requête SQL
 *
 * @return  mysqli_result|bool          Résultat de la requête
 */
function bdSendRequest(mysqli $bd, string $sql): mysqli_result|bool
{
    try {
        return mysqli_query($bd, $sql);
    } catch (mysqli_sql_exception $e) {
        $err['titre'] = 'Erreur de requête';
        $err['code'] = $e->getCode();
        $err['message'] = $e->getMessage();
        $err['appels'] = $e->getTraceAsString();
        $err['autres'] = array('Requête' => $sql);
        bdErreurExit($err); // ==> ARRET DU SCRIPT
    }
}