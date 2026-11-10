<?php
/** 
 * Fonctions utilitaires pour l'application GETCET
 * ------------------------------------------------
 * Contient les fonctions pour :
 * - Gestion des sessions et connexion des employés
 * - Gestion des dates
 * - Validation et contrôle des données
 * - Gestion des erreurs
 *
 * @package default
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

/**
 * Teste si un employé est connecté
 * 
 * @return bool vrai si connecté, sinon faux
 */
function estConnecte() {
    return isset($_SESSION['idEmploye']);
}

/**
 * Enregistre dans une variable de session les informations d'un employé
 * 
 * @param int $id ID de l'employé
 * @param string $nom Nom de l'employé
 * @param string $prenom Prénom de l'employé
 * @param string $metier Métier de l'employé
 */
function connecter($id, $nom, $prenom, $metier) {
    $_SESSION['idEmploye'] = $id;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['metier'] = $metier;
}

/**
 * Détruit la session active
 */
function deconnecter() {
    session_start();
    $_SESSION = array();
    session_destroy();
}

/**
 * Transforme une date du format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 * 
 * @param string $maDate Date au format jj/mm/aaaa
 * @return string Date au format aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate) {
    @list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date du format anglais aaaa-mm-jj vers le format français jj/mm/aaaa
 * 
 * @param string $maDate Date au format aaaa-mm-jj
 * @return string Date au format jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate) {
    @list($annee, $mois, $jour) = explode('-', $maDate);
    return $jour . "/" . $mois . "/" . $annee;
}

/**
 * Retourne le mois au format aaaamm selon une date donnée
 * 
 * @param string $date Date au format jj/mm/aaaa
 * @return string Mois au format aaaamm
 */
function getMois($date) {
    @list($jour, $mois, $annee) = explode('/', $date);
    if (strlen($mois) == 1) {
        $mois = "0" . $mois;
    }
    return $annee . $mois;
}

/* ----------------------------------------- */
/* Gestion des erreurs et validation         */
/* ----------------------------------------- */

/**
 * Indique si une valeur est un entier positif ou nul
 * 
 * @param mixed $valeur Valeur à tester
 * @return bool vrai si entier positif ou nul, sinon faux
 */
function estEntierPositif($valeur) {
    return preg_match("/[^0-9]/", $valeur) == 0;
}

/**
 * Indique si un tableau contient uniquement des entiers positifs ou nuls
 * 
 * @param array $tabEntiers Tableau à tester
 * @return bool vrai si tous les éléments sont des entiers positifs ou nuls
 */
function estTableauEntiers($tabEntiers) {
    $ok = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $ok = false;
        }
    }
    return $ok;
}

/**
 * Vérifie si une date est dépassée d'au moins un an
 * 
 * @param string $dateTestee Date au format jj/mm/aaaa
 * @return bool vrai si la date est dépassée, sinon faux
 */
function estDateDepassee($dateTestee) {
    $dateActuelle = date("d/m/Y");
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $AnPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $AnPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 * 
 * @param string $date Date à tester
 * @return bool vrai si la date est valide, sinon faux
 */
function estDateValide($date) {
    $tabDate = explode('/', $date);
    $dateOK = true;

    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/* ----------------------------------------- */
/* Gestion du tableau des erreurs            */
/* ----------------------------------------- */

/**
 * Ajoute un message d'erreur au tableau des erreurs
 * 
 * @param string $msg Message à ajouter
 */
function ajouterErreur($msg) {
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
}

/**
 * Retourne le nombre d'erreurs enregistrées
 * 
 * @return int Nombre d'erreurs
 */
function nbErreurs() {
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}

/**
 * Modifie le mot de passe d'un employé via le modèle PDO
 * 
 * @param string $login Login de l'employé
 * @param string $mdp Nouveau mot de passe
 */
function modifierMotDePasse($login, $mdp) {
    global $pdo; // Nécessaire pour accéder à l'objet $pdo
    $pdo->modifierMotDePasse($login, $mdp);
}
?>
