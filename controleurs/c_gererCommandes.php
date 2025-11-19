<?php
/**
 * Contrôleur de gestion des commandes
 * -----------------------------------
 * Gère les actions liées aux commandes :
 * - Consultation et ajout de commandes
 * - Validation d'achat en magasin ou par téléphone
 * - Suppression de commandes
 * - Gestion du cycle de vie des commandes (Préparée, Incomplète, Livrée)
 *
 * @auteurs Marouane / Mathieu
 * @version 1.0
 */

// Inclusion du sommaire (menu principal)
include("vues/v_sommaire.php");

// Récupération de l'identifiant de l'employé connecté
$idEmploye = $_SESSION['idEmploye'];

// On récupère l'action demandée ; par défaut, on affiche la liste des commandes
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'gererCommandes';
}
$action = $_REQUEST['action'];

// Gestion des différentes actions
switch ($action) {

    /**
     * Cas 'gererCommandes' : Affiche la liste des commandes et le formulaire d'ajout
     */
    case 'gererCommandes': {
        // Récupération de toutes les commandes
        $lesCommandes = $pdo->getLesCommandes();

        // Inclusion des vues pour afficher la liste et le formulaire
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /**
     * Cas 'supprimerCommande' : Supprime une commande et recharge la liste
     */
    case 'supprimerCommande': {
        // Récupération de l'identifiant de la commande à supprimer
        $idCommande = $_REQUEST['id'];

        // Appel du modèle pour supprimer la commande
        $pdo->supprimerCommande($idCommande);

        // Rechargement des commandes après suppression
        $lesCommandes = $pdo->getLesCommandes();

        // Inclusion des vues pour afficher la liste mise à jour
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /**
     * Cas 'validerAchatMagasin' et 'validerCommandeTel' :
     * Validation d'une commande, calcul du prix total et mise à jour du stock
     */
    case 'validerAchatMagasin':
    case 'validerCommandeTel': {
        $typeDeVente = $action;

        // Récupération de l'acheteur sélectionné
        if(isset($_REQUEST['acheteur']) && $_REQUEST['acheteur'] != '') {
            $idAcheteur = $_REQUEST['acheteur'];
        } else {
            $idAcheteur = '';
        }
        
        // Initialisation des variables
        $lesProduits = $pdo->getLesProduits(); // Liste de tous les produits
        $prixTotal = 0;                        // Prix total de la commande
        $auMoinsUnProduit = false;             // Vérifie qu'au moins un produit est commandé
        $erreursStock = false;                 // Indique si un produit dépasse le stock
        $produitsACommander = array();         // Stocke les produits à commander

        // Vérification de la quantité de chaque produit
        foreach ($lesProduits as $unProduit) {
            $nomProduitForm = 'produit' . $unProduit['reference'];

            if (isset($_REQUEST[$nomProduitForm]) && $_REQUEST[$nomProduitForm] != '') {
                $qte = (int)$_REQUEST[$nomProduitForm];

                if ($qte > 0) {
                    $stockActuel = $unProduit['qteStock'];

                    // Vérification du stock disponible
                    if ($qte > $stockActuel) {
                        ajouterErreur(
                            "Stock insuffisant pour " . $unProduit['designation'] .
                            ". (Demandé: " . $qte . ", Stock: " . $stockActuel . ")"
                        );
                        $erreursStock = true;
                    } else {
                        // Mise à jour du prix total et stockage du produit
                        $prixTotal += ($unProduit['prix'] * $qte);
                        $auMoinsUnProduit = true;
                        $produitsACommander[] = [
                            'reference' => $unProduit['reference'],
                            'qte' => $qte
                        ];
                    }
                }
            }
        }

        // Vérification des conditions avant de créer la commande
        if ($idAcheteur == '') {
            ajouterErreur("Veuillez sélectionner un acheteur.");
        } else {
            if (!$erreursStock) {
                if ($auMoinsUnProduit) {
                    // Création de la commande
                    $idNouvelleCommande = $pdo->ajouterUneCommande($idAcheteur, $prixTotal);

                    if ($idNouvelleCommande) {
                        // Mise à jour du stock pour chaque produit commandé
                        foreach ($produitsACommander as $prod) {
                            $pdo->baisserStockProduit($prod['reference'], $prod['qte']);
                        }

                        // Si achat magasin, la commande est directement marquée "Livrée"
                        if ($typeDeVente == 'validerAchatMagasin') {
                            $pdo->changerEtatCommande($idNouvelleCommande, 'Livrée');
                        }
                        // Sinon, la commande reste en "Enregistrée"
                    } else {
                        ajouterErreur("Erreur lors de l'ajout de la commande.");
                    }
                } else {
                    ajouterErreur("Veuillez commander au moins un produit.");
                }
            }
            // Si erreurs de stock, elles ont déjà été ajoutées
        }

        // Affichage des erreurs si présentes
        if (nbErreurs() > 0) {
            include("vues/v_erreurs.php");
        }

        // Rechargement de la liste des commandes après traitement
        $lesCommandes = $pdo->getLesCommandes();
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /* Gestion du cycle de vie des commandes                     */

    /**
     * Cas 'passerAComplete' : Passe l'état d'une commande à "Préparée et complète"
     */
    case 'passerAComplete': {
        $idCommande = $_REQUEST['id'];
        $pdo->changerEtatCommande($idCommande, 'Préparée et complète');

        $lesCommandes = $pdo->getLesCommandes();
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /**
     * Cas 'passerAIncomplete' : Passe l'état d'une commande à "Préparée et incomplète"
     */
    case 'passerAIncomplete': {
        $idCommande = $_REQUEST['id'];
        $pdo->changerEtatCommande($idCommande, 'Préparée et incomplète');

        $lesCommandes = $pdo->getLesCommandes();
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /**
     * Cas 'passerALivree' : Passe l'état d'une commande à "Livrée"
     */
    case 'passerALivree': {
        $idCommande = $_REQUEST['id'];
        $pdo->changerEtatCommande($idCommande, 'Livrée');

        $lesCommandes = $pdo->getLesCommandes();
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }

    /**
     * Cas par défaut : Si l'action n'est pas reconnue, on affiche simplement la liste
     */
    default: {
        $lesCommandes = $pdo->getLesCommandes();
        include("vues/v_listeCommandes.php");
        include("vues/v_ajoutCommande.php");
        break;
    }
}
?>
