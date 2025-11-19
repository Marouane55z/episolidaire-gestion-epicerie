<?php
/**
 * Contrôleur du profil utilisateur
 * --------------------------------
 * Gère les actions liées au profil d’un employé :
 * - Consultation du profil
 * - Modification du mot de passe
 *
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

// Inclusion de la vue du sommaire (menu principal)
include("vues/v_sommaire.php");

// Récupération de l’action demandée et de l’identifiant de l’employé connecté
$action = $_REQUEST['action'];
$idEmploye = $_SESSION['idEmploye'];

// Gestion des différentes actions possibles
switch ($action) {

    /**
     * Cas 1 : Affichage du profil de l’employé connecté
     */
    case 'profil': {
        // Récupération des informations du profil depuis la base de données
        $leProfil = $pdo->getInfosEmployeById($idEmploye);

        // Inclusion de la vue correspondante
        include("vues/v_profil.php");
        break;
    }

    /**
     * Cas 2 : Action "autre" (réservée pour de futures fonctionnalités)
     */
    case 'autre': {
        // Pas encore implémenté
        break;
    }

    /**
     * Cas 3 : Modification du mot de passe
     */
    case 'modifierMotDePasse': {
        // --- Récupération des données du formulaire ---
        $login = $_REQUEST['login'];
        $ancienMdp = $_REQUEST['ancienMdp'];
        $nouveauMdp = $_REQUEST['nouveauMdp'];
        
        // --- Vérification de l'ancien mot de passe ---
        $verif = $pdo->testConnexionEmploye($login, $ancienMdp);
        
        if ($verif > 0) {
            // Ancien mot de passe correct → on peut procéder à la modification
            $resultat = $pdo->modifierMotDePasse($login, $nouveauMdp);

            if ($resultat) {
                // Succès de la modification
                ajouterErreur("Le mot de passe a été modifié avec succès. Veuillez vous reconnecter.");
                include("vues/v_erreurs.php");
                include("vues/v_connexion.php");
                exit(); // Arrêt du script après redirection
            } else {
                // Échec de la mise à jour
                ajouterErreur("Une erreur est survenue lors de la modification du mot de passe.");
                $leProfil = $pdo->getInfosEmployeById($idEmploye);
                include("vues/v_erreurs.php");
                include("vues/v_profil.php");
            }
        } else {
            // Ancien mot de passe incorrect
            ajouterErreur("L'ancien mot de passe est incorrect.");
            $leProfil = $pdo->getInfosEmployeById($idEmploye);
            include("vues/v_erreurs.php");
            include("vues/v_profil.php");
        }
        break;
    }
}
?>
