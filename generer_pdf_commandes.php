<?php
// --- 1. CHARGEMENT DES RESSOURCES ---

// On a besoin de la session pour la sécurité
session_start();

// On inclut le modèle (pour la BDD) et FPDF
require_once("include/modele.php");
require_once("include/fpdf/fpdf.php"); // Le chemin vers FPDF

// --- 2. SÉCURITÉ ---
// On vérifie si l'utilisateur est connecté ET s'il est Épicier ou Maire
if (!isset($_SESSION['idEmploye']) || ($_SESSION['metier'] != 'epicier' && $_SESSION['metier'] != 'maire')) {
    die("Erreur : Accès non autorisé. Vous devez être connecté en tant qu'épicier ou maire pour générer ce document.");
}

// --- 3. RÉCUPÉRATION DES DONNÉES ---
$pdo = Modele::getModele();
$lesCommandes = $pdo->getLesCommandes(); // On récupère les commandes

// --- 4. CRÉATION DU PDF ---

// On crée un nouvel objet PDF
$pdf = new FPDF();
$pdf->AddPage(); // Ajoute une nouvelle page

// Définir la police
$pdf->SetFont('Arial', 'B', 16);

// Titre
$pdf->Cell(190, 10, 'Liste des Commandes - Epi\'Solidaire', 1, 1, 'C'); // Cellule(largeur, hauteur, texte, bordure, saut de ligne, alignement)
$pdf->Ln(10); // Saute une ligne

// En-têtes du tableau
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Couleur de fond grise
$pdf->Cell(20, 7, 'ID Comm.', 1, 0, 'C', true); // true = fond coloré
$pdf->Cell(20, 7, 'ID Ach.', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Date', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Prix Total', 1, 0, 'C', true);
$pdf->Cell(60, 7, 'Etat', 1, 1, 'C', true); // 1 = saut de ligne à la fin

// Données du tableau
$pdf->SetFont('Arial', '', 10);

foreach ($lesCommandes as $uneCommande) {
    $pdf->Cell(20, 6, $uneCommande['id'], 1);
    $pdf->Cell(20, 6, $uneCommande['idAcheteur'], 1);
    $pdf->Cell(40, 6, $uneCommande['dateCommande'], 1);
    $pdf->Cell(30, 6, $uneCommande['prixTotal'] . ' EUR', 1, 0, 'R'); // 'R' = aligné à droite
    $pdf->Cell(60, 6, $uneCommande['etat'], 1, 1);
}

// --- 5. ENVOI DU PDF AU NAVIGATEUR ---
$pdf->Output('I', 'liste_commandes.pdf'); // 'I' = envoie au navigateur

?>