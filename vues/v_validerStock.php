<div class="listeLegere">

    <!-- Titre de la section -->
    <h2>Validation des entrées en stock</h2>
    
    <?php if (isset($lesArticlesAValider) && !empty($lesArticlesAValider)) { ?>
        
        <!-- Message explicatif -->
        <p>
            Voici la liste des articles enregistrés qui attendent d'être 
            confirmés comme "En stock" (état "En vente").
        </p>
        
        <!-- Tableau des articles à valider -->
        <table>
            <tr>
                <th>ID Article</th>
                <th>Désignation (Produit)</th>
                <th>Date de Péremption</th>
                <th class="action">Action</th>
            </tr>
            
            <?php
            // Boucle sur chaque article à valider
            foreach ($lesArticlesAValider as $unArticle) {
                $idArticle = $unArticle['idArticle'];
                $designation = $unArticle['designation'];
                $datePeremption = dateAnglaisVersFrancais($unArticle['datePeremption']); // Formatage de la date
            ?>
            
            <tr>
                <td><?php echo $idArticle ?></td>
                <td><?php echo $designation ?></td>
                <td><?php echo $datePeremption ?></td>
                
                <!-- Bouton de validation -->
                <td>
                    <a href="index.php?uc=gererStock&action=confirmerValidation&id=<?php echo $idArticle ?>" 
                       class="btn-validate"
                       onclick="return confirm('Voulez-vous vraiment confirmer la réception de cet article ?');">
                       Valider
                    </a>
                </td>
            </tr>
            
            <?php } // Fin de la boucle ?>
            
        </table>

    <?php 
    } else { 
        // Affichage si aucun article à valider
    ?>
        <div class="bloc-info">
            <strong>Information :</strong> Il n'y a aucun article en attente de validation.
        </div>
    <?php } // Fin du if ?>

</div>
