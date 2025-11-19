<!-- Bouton pour générer le PDF des commandes -->
<div class="pdf">
    <a href="generer_pdf_commandes.php" target="_blank" class="btn-cta">
        Imprimer la liste (PDF)
    </a>
</div>

<!-- Tableau des commandes -->
<table class="listeLegere">
    <caption>Liste des commandes enregistrées</caption>

    <!-- Entête du tableau -->
    <tr>
        <th>Id Commande</th>
        <th>Id Acheteur</th>
        <th>Date</th>  
        <th>Prix Total</th>
        <th>État</th>
        <th class="action" colspan="2">Actions</th>
    </tr>
          
    <?php    
    // Boucle sur toutes les commandes
    foreach($lesCommandes as $uneCommande) 
    {
        $idCom = $uneCommande['id'];
        $etat  = $uneCommande['etat']; // État brut de la commande
    ?>		

        <tr>
            <td><?php echo $idCom ?></td>
            <td><?php echo $uneCommande['idAcheteur'] ?></td>
            <td><?php echo $uneCommande['dateCommande'] ?></td>
            <td><?php echo $uneCommande['prixTotal'] ?></td>
            <td><strong><?php echo $etat ?></strong></td> 

            <!-- Actions logiques selon l'état de la commande -->
            <td>
                <div class="actions-container">

                    <?php
                    // Si l'état est "Enregistrée", proposer les options Préparée complète/incomplète
                    if ($etat == 'Enregistrée') {
                    ?>
                        <a href="index.php?uc=listeCommandes&action=passerAComplete&id=<?php echo $idCom?>" 
                           class="btn-validate"
                           onclick="return confirm('Voulez-vous marquer cette commande comme Préparée et Complète ?');">
                           Préparée (Complète)
                        </a>
                        <a href="index.php?uc=listeCommandes&action=passerAIncomplete&id=<?php echo $idCom?>" 
                           class="btn-warning"
                           onclick="return confirm('Voulez-vous marquer cette commande comme Préparée et Incomplète ?');">
                           Préparée (Incomplète)
                        </a>
                    <?php
                    }
                    // Si l'état est "Préparée" (Complète ou Incomplète), proposer la livraison
                    elseif ($etat == 'Préparée et complète' || $etat == 'Préparée et incomplète') {
                    ?>
                        <a href="index.php?uc=listeCommandes&action=passerALivree&id=<?php echo $idCom?>" 
                           class="btn-cta"
                           onclick="return confirm('Confirmer la livraison de cette commande ?');">
                           Marquer comme Livrée
                        </a>
                    <?php
                    }
                    // Sinon (ex: "Livrée"), aucune action à afficher
                    else {
                        echo "---";
                    }
                    ?>

                </div>
            </td>

            <!-- Colonne pour annulation -->
            <td>
                <?php
                if ($etat != 'Livrée') {
                ?>
                    <a href="index.php?uc=listeCommandes&action=supprimerCommande&id=<?php echo $idCom?>" 
                       class="btn-danger"
                       onclick="return confirm('Voulez-vous vraiment ANNULER cette commande ?');">
                       Annuler
                    </a>
                <?php } ?>
            </td>
        </tr>

    <?php		 
    } // Fin de la boucle foreach
    ?>	  
                                          
</table>
