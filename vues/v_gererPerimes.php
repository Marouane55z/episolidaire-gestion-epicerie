<div class="listeLegere">

    <!-- Titre général -->
    <h2>Gestion des périmés et périssables</h2>

    <!-- Section des articles périssables (dans 5 jours) -->
    <h3>Articles périssables (à 5 jours)</h3>
    
    <?php if (isset($lesArticlesPerissables) && !empty($lesArticlesPerissables)) { ?>
        
        <!-- Message explicatif -->
        <p>Ces articles sont "En vente" et leur date de péremption est dans les 5 prochains jours.</p>

        <!-- Tableau des articles périssables -->
        <table>
            <tr>
                <th>ID Article</th>
                <th>Désignation</th>
                <th>Date de Péremption</th>
            </tr>
            <?php
            // Boucle sur les articles périssables
            foreach ($lesArticlesPerissables as $unArticle) {
                $datePeremption = dateAnglaisVersFrancais($unArticle['datePeremption']);
            ?>
            <tr>
                <td><?php echo $unArticle['idArticle'] ?></td>
                <td><?php echo $unArticle['designation'] ?></td>
                <td><strong><?php echo $datePeremption ?></strong></td>
            </tr>
            <?php } ?>
        </table>

    <?php 
    } else { 
        // Aucun article périssable
    ?>
        <div class="bloc-info">
            <strong>Information :</strong> Il n'y a aucun article périssable dans les 5 prochains jours.
        </div>
    <?php } ?>

    <!-- Séparateur -->
    <hr style="border:none; border-top:1px solid #e6e6ea; margin: 25px 0;">

    <!-- Section des articles périmés -->
    <h3>Articles périmés</h3>

    <?php if (isset($lesArticlesPerimes) && !empty($lesArticlesPerimes)) { ?>
        
        <!-- Message explicatif -->
        <p>Ces articles sont "En vente" mais leur date de péremption est dépassée. Ils doivent être retirés du stock.</p>

        <!-- Tableau des articles périmés -->
        <table>
            <tr>
                <th>ID Article</th>
                <th>Désignation</th>
                <th>Date de Péremption</th>
                <th class="action" colspan="2">Actions</th>
            </tr>
            <?php
            // Boucle sur les articles périmés
            foreach ($lesArticlesPerimes as $unArticle) {
                $datePeremption = dateAnglaisVersFrancais($unArticle['datePeremption']);
            ?>
            <tr>
                <td><?php echo $unArticle['idArticle'] ?></td>
                <td><?php echo $unArticle['designation'] ?></td>
                <td><strong class="text-danger"><?php echo $datePeremption ?></strong></td>

                <!-- Bouton "Donné" -->
                <td>
                    <a href="index.php?uc=gererStock&action=declarerDonne&id=<?php echo $unArticle['idArticle'] ?>" 
                       class="btn-validate"
                       onclick="return confirm('Confirmer que cet article a été DONNÉ ?');">
                       Donné
                    </a>
                </td>

                <!-- Bouton "Jeté" -->
                <td>
                    <a href="index.php?uc=gererStock&action=declarerJete&id=<?php echo $unArticle['idArticle'] ?>" 
                       class="btn-danger"
                       onclick="return confirm('Confirmer que cet article a été JETÉ ?');">
                       Jeté
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>

    <?php 
    } else { 
        // Aucun article périmé
    ?>
        <div class="bloc-info">
            <strong>Information :</strong> Il n'y a aucun article périmé en stock.
        </div>
    <?php } ?>

</div>
