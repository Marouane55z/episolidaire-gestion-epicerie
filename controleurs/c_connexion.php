<?php
/**
 * Contrôleur de gestion de la connexion utilisateur
 * -------------------------------------------------
 * Ce script gère les différentes actions liées à la connexion :
 * - Affichage du formulaire de connexion
 * - Validation de la connexion
 * - Modification du mot de passe
 *
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

// Si aucune action n'est définie, on redirige vers la demande de connexion
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'demandeConnexion';
}

$action = $_REQUEST['action'];

// Gestion des différentes actions possibles
switch ($action) {

    /**
     * Cas 1 : Affichage du formulaire de connexion
     */
    case 'demandeConnexion': {
        include("vues/v_connexion.php");
        break;
    }

    /**
     * Cas 2 : Validation des identifiants de connexion
     */
    case 'valideConnexion': {

        // --- Récupération des informations du formulaire ---
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];
        
        // --- Vérification de la connexion dans la base de données ---
        $verif = $pdo->testConnexionEmploye($login, $mdp);

        // Si les identifiants sont incorrects
        if ($verif == 0) {
            ajouterErreur("Login ou mot de passe incorrect");
            include("vues/v_erreurs.php");
            include("vues/v_connexion.php");
        } 
        // Si la connexion est valide
        else {
            // --- Récupération des informations de l'employé ---
            $employe = $pdo->getInfosEmploye($login);
            $id = $employe['id'];
            $nom = $employe['nom'];
            $prenom = $employe['prenom'];
            $metier = $employe['metier'];

            // --- Connexion de l'utilisateur ---
            connecter($id, $nom, $prenom, $metier);

            // --- Affichage du sommaire (page d'accueil après connexion) ---
            include("vues/v_sommaire.php");
        }
        break;
    }

    /**
     * Cas 3 : Modification du mot de passe
     */
    case 'modifierMotDePasse': {
        $login = $_SESSION['login'];
        $mdp = $_REQUEST['mdp'];

        // Appel à la méthode du modèle pour mettre à jour le mot de passe
        $pdo->modifierMotDePasse($login, $mdp);

        // Retour sur la page de connexion après modification
        include("vues/v_connexion.php");
        break;
    }

    /**
     * Cas par défaut : redirection vers la connexion
     */
    default: {
        include("vues/v_connexion.php");
        break;
    }
}
?>
