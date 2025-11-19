<?php
/**
 * Contrôleur de gestion des stocks
 * --------------------------------
 * Gère les actions liées aux produits et articles :
 * - Validation des entrées
 * - Gestion des articles périmés et périssables
 * - Déclaration d'articles donnés ou jetés
 *
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

// Inclusion du sommaire (menu principal)
include("vues/v_sommaire.php");

// Récupération de l'action demandée
$action = $_REQUEST['action'];

// Vérification des droits : l'utilisateur doit être épicier ou maire
if ($_SESSION['metier'] != 'epicier' && $_SESSION['metier'] != 'maire') {
    // Si l'utilisateur n'a pas les droits
    ajouterErreur("Vous n'avez pas les droits pour accéder à la gestion des stocks.");
    include("vues/v_erreurs.php");
} 
else {
    // Mise à jour automatique des états des produits périssables (ex: périmés dans 5 jours)
    $pdo->mettreAJourEtatsPerissables(5);

    // Gestion des différentes actions
    switch ($action) {

        /**
         * Cas 'validerEntrees' : Affiche les articles en attente de validation
         */
        case 'validerEntrees': {
            $lesArticlesAValider = $pdo->getArticlesAValider();
            include("vues/v_validerStock.php");
            break;
        }

        /**
         * Cas 'confirmerValidation' : Met à jour l'état de l'article et recharge la liste
         */
        case 'confirmerValidation': {
            $idArticle = $_REQUEST['id'];
            $pdo->validerArticle($idArticle);

            // Rechargement des articles à valider
            $lesArticlesAValider = $pdo->getArticlesAValider();
            include("vues/v_validerStock.php");
            break;
        }

        /**
         * Cas 'gererPerimes' : Affiche les articles périmés et périssables
         */
        case 'gererPerimes': {
            $lesArticlesPerimes = $pdo->getArticlesPerimes();
            $lesArticlesPerissables = $pdo->getArticlesPerissables(5);
            include("vues/v_gererPerimes.php");
            break;
        }

        /**
         * Cas 'declarerDonne' : Passe l'état de l'article à 'Donné' (id 9)
         */
        case 'declarerDonne': {
            $idArticle = $_REQUEST['id'];
            $pdo->declarerArticleDonne($idArticle);

            // Rechargement des listes
            $lesArticlesPerimes = $pdo->getArticlesPerimes();
            $lesArticlesPerissables = $pdo->getArticlesPerissables(5);
            include("vues/v_gererPerimes.php");
            break;
        }

        /**
         * Cas 'declarerJete' : Passe l'état de l'article à 'Jeté' (id 8)
         */
        case 'declarerJete': {
            $idArticle = $_REQUEST['id'];
            $pdo->declarerArticleJete($idArticle);

            // Rechargement des listes
            $lesArticlesPerimes = $pdo->getArticlesPerimes();
            $lesArticlesPerissables = $pdo->getArticlesPerissables(5);
            include("vues/v_gererPerimes.php");
            break;
        }

        /**
         * Cas par défaut : action inconnue → affichage d'une erreur
         */
        default: {
            ajouterErreur("Action inconnue dans la gestion des stocks.");
            include("vues/v_erreurs.php");
            break;
        }
    }
}
?>
