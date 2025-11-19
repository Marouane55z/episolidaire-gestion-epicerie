<div class="listeLegere">

    <!-- Titre du formulaire -->
    <h3>Ajouter une nouvelle commande : </h3>

    <!-- Formulaire d'ajout de commande -->
    <form method="POST" action="index.php?uc=listeCommandes&action=ajouterUneCommande">
        
        <!-- Sélecteur pour choisir l'acheteur -->
        <select name="acheteur" id="acheteur-select">
            <option value="">--Veuillez choisir un Acheteur--</option>
            <?php
                // 1. Récupérer tous les acheteurs depuis la BDD
                $lesAcheteurs = $pdo->getLesAcheteurs();

                // 2. Parcourir la liste et afficher chaque acheteur comme option
                foreach($lesAcheteurs as $unAcheteur){
                    echo "<option value='".$unAcheteur['id']."'>"
                         .$unAcheteur['nom']." ".$unAcheteur['prenom'].
                         "</option>";
                } 
            ?>
        </select>
        <br><br>

        <?php
            // 1. Récupérer tous les produits
            $lesProduits = $pdo->getLesProduits();

            // 2. Parcourir chaque produit
            foreach($lesProduits as $unProduit){
                $stockDispo = $unProduit['qteStock'];
                $designation = $unProduit['designation'];
                $reference = $unProduit['reference'];

                // 3. Afficher uniquement si le stock est supérieur à 0
                if($stockDispo > 0){
                    // Affiche le nom du produit et la quantité disponible
                    echo "<label for='produit".$reference."'>"
                         .$designation." (Dispo: ".$stockDispo.") : </label>";

                    // Input numérique pour choisir la quantité
                    // min=0 et max = stock disponible
                    echo "<input type='number' name='produit".$reference."' "
                         ."id='produit".$reference."' min='0' max='".$stockDispo."' "
                         ."placeholder='0'><br>";
                }
            }
        ?>
        <br>

        <!-- Bouton pour encaissement en magasin -->
        <button type="submit" name="action" value="validerAchatMagasin" class="btn-validate">
            Encaisser (Achat Magasin)
        </button>

        <!-- Bouton pour validation commande par téléphone -->
        <button type="submit" name="action" value="validerCommandeTel" class="btn-cta">
            Valider (Commande Tél.)
        </button>

        <!-- Bouton pour réinitialiser le formulaire -->
        <input type='reset' value='Annuler' name='annuler'>
        
    </form>
</div>
