<?php
// Démarrage de la session
session_start();

// Inclusion des fonctions et du modèle
require_once("include/fonctions.php");
require_once("include/modele.php");

// Inclusion de l'entête HTML
include("vues/v_entete.php");

// Création de l'objet PDO pour accéder à la base de données
$pdo = Modele::getModele();

// Vérification si l'utilisateur est connecté
$estConnecte = estConnecte();

// Si l'utilisateur n'est pas connecté ou que l'UC n'est pas défini, 
// on force la page de connexion
if (!isset($_REQUEST['uc']) || !$estConnecte) {
    $_REQUEST['uc'] = 'connexion';
}

// Récupération de l'unité de contrôle (UC) demandée
$uc = $_REQUEST['uc'];

// Routeur principal : inclusion du contrôleur correspondant
switch ($uc) {
    
    case 'connexion': {
        // Contrôleur de connexion
        include("controleurs/c_connexion.php");
        break;
    }

    case 'profilEmploye': {
        // Contrôleur pour la gestion du profil employé
        include("controleurs/c_employe.php");
        break; 
    }

    case 'listeCommerces': {
        // Contrôleur pour la gestion des commerces
        include("controleurs/c_gererCommerces.php");
        break;
    }

    case 'listeAcheteurs': {
        // Contrôleur pour la gestion des acheteurs
        include("controleurs/c_gererAcheteurs.php");
        break;
    }

    case 'listeCommandes': {
        // Contrôleur pour la gestion des commandes
        include("controleurs/c_gererCommandes.php");
        break;
    }

    case 'gererStock': {
        // Contrôleur pour la gestion du stock
        include("controleurs/c_gererStock.php");
        break;
    }

    case 'deconnexion': {
        // Déconnexion de l'utilisateur
        deconnecter();
        include("vues/v_connexion.php");
        break;
    }

    case 'modifierMotDePasse': {
        // Contrôleur pour la modification du mot de passe
        include("controleurs/c_employe.php");
        break;
    }

    default: {
        // UC non reconnu : redirection vers la connexion
        include("controleurs/c_connexion.php");
        break;
    }
}

// Inclusion du pied de page HTML
include("vues/v_pied.php");
?>
