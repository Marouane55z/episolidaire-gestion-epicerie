<!-- Bloc d'affichage des erreurs -->
<div class="bloc-erreur"> 
    <!-- Titre du bloc -->
    <strong>Oups ! Une ou plusieurs erreurs sont survenues :</strong>

    <!-- Liste des erreurs -->
    <ul>
        <?php 
        // Parcours du tableau d'erreurs passé dans la requête
        foreach($_REQUEST['erreurs'] as $erreur)
        {
            echo "<li>- $erreur</li>";
        }
        ?>
    </ul>
</div>
