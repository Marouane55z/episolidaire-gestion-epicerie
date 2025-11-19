<?php
/**
 * Contrôleur de gestion des commerces
 * -----------------------------------
 * Gère les actions liées aux commerces :
 * - Consultation de la liste des commerces
 * - Ajout d'un commerce
 * - Suppression d'un commerce
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

// Gestion des différentes actions
switch ($action) {

    /**
     * Cas 1 : Affichage de la liste des commerces et formulaire d'ajout
     */
    case 'gererCommerces': {
        // Récupération de tous les commerces
        $lesCommerces = $pdo->getLesCommerces();

        // Inclusion des vues
        include("vues/v_listeCommerces.php");
        include("vues/v_ajoutCommerce.php");
        break;
    }

    /**
     * Cas 2 : Suppression d'un commerce
     */
    case 'supprimerCommerce': {
        // Récupération de l'identifiant du commerce à supprimer
        $idCommerce = $_REQUEST['id'];

        // Appel au modèle pour suppression
        $pdo->supprimerCommerce($idCommerce);

        // Rechargement de la liste
        $lesCommerces = $pdo->getLesCommerces();
        include("vues/v_listeCommerces.php");
        include("vues/v_ajoutCommerce.php");
        break;
    }

    /**
     * Cas 3 : Ajout d'un commerce
     */
    case 'ajouterUnCommerce': {
        // Récupération des informations du formulaire
        $nom = $_REQUEST['nom'];
        $rue = $_REQUEST['rue'];
        $cp = $_REQUEST['cp'];
        $ville = $_REQUEST['ville'];

        // Appel au modèle pour ajouter le commerce
        $pdo->ajouterUnCommerce($nom, $rue, $cp, $ville);

        // Rechargement de la liste
        $lesCommerces = $pdo->getLesCommerces();
        include("vues/v_listeCommerces.php");
        include("vues/v_ajoutCommerce.php");
        break;
    }
}
?>
