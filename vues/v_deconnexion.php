<ul>
    <?php
        // Récupère le nom de l'utilisateur depuis la session
        $nom = $_SESSION['nom'];

        // Affiche un message de bienvenue et le lien de déconnexion
        echo "Bonjour $nom <a href='Deconnexion.php'>Deconnexion</a>";
    ?>
</ul>
