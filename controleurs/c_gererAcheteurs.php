<?php
/**
 * Contrôleur de gestion des acheteurs
 * -----------------------------------
 * Gère les actions liées à la consultation et à la suppression des acheteurs.
 *
 * Actions possibles :
 * - gererAcheteurs : affiche la liste des acheteurs
 * - supprimerAcheteur : supprime un acheteur et recharge la liste
 *
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

// Inclusion du sommaire (menu principal)
include("vues/v_sommaire.php");

// Récupération de l'identifiant de l'employé connecté
$idEmploye = $_SESSION['idEmploye'];

// Récupération de l'action demandée
$action = $_REQUEST['action'];

// Gestion des différentes actions possibles
switch ($action) {

    /**
     * Cas 1 : Affichage de la liste des acheteurs
     */
    case 'gererAcheteurs': {
        // Récupération de tous les acheteurs depuis la base de données
        $lesAcheteurs = $pdo->getLesAcheteurs();

        // Affichage de la vue correspondante
        include("vues/v_listeAcheteurs.php");
        break;
    }

    /**
     * Cas 2 : Suppression d’un acheteur
     */
    case 'supprimerAcheteur': {
        // Récupération de l'identifiant de l'acheteur à supprimer
        $idAcheteur = $_REQUEST['idAcheteur'];

        // Suppression dans la base de données
        $pdo->supprimerAcheteur($idAcheteur);

        // Rechargement de la liste des acheteurs après suppression
        $lesAcheteurs = $pdo->getLesAcheteurs();
        include("vues/v_listeAcheteurs.php");
        break;
    }
}
?>
