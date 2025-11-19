<div class="listeLegere">

    <!-- Titre du tableau -->
    <caption><h2>Liste des commerces enregistrés</h2></caption>
    
    <!-- Bouton pour ajouter un nouveau commerce -->
    <a href="index.php?uc=listeCommerces&action=demandeAjoutCommerce" class="btn-cta">
        + Ajouter un commerce
    </a>

    <!-- Tableau des commerces -->
    <table class="listeLegere"> 
        <!-- Entête du tableau -->
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Rue</th>
            <th>Code Postal</th>
            <th>Ville</th>
            <th>Actions</th>
        </tr>

        <?php    
        // Boucle sur tous les commerces
        foreach($lesCommerces as $unCommerce) 
        {
            $id    = $unCommerce['id'];
            $nom   = $unCommerce['nom'];
            $rue   = $unCommerce['rue'];
            $cp    = $unCommerce['codePostal'];
            $ville = $unCommerce['ville'];
        ?>		

        <tr>
            <td><?php echo $id ?></td>
            <td><?php echo $nom ?></td>
            <td><?php echo $rue ?></td>
            <td><?php echo $cp ?></td>
            <td><?php echo $ville ?></td>

            <!-- Bouton supprimer pour chaque commerce -->
            <td>
                <a href="index.php?uc=listeCommerces&action=supprimerCommerce&id=<?php echo $id?>" 
                   class="btn-danger" 
                   onclick="return confirm('Voulez-vous vraiment supprimer ce commerce?');">
                   Supprimer
                </a>
            </td>
        </tr>

        <?php		 
        } // Fin de la boucle foreach
        ?>	  
                                          
    </table>

</div>
