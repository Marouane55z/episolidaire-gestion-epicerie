<div class="listeLegere"> 
    
    <!-- Titre de la section -->
    <h2>Identification utilisateur</h2>

    <!-- Formulaire de connexion -->
    <form method="POST" action="index.php?uc=connexion&action=valideConnexion">
   
        <!-- Champ pour le login -->
        <p>
            <label for="login">Login* </label>
            <input id="login" type="text" name="login" size="30" maxlength="45">
        </p>

        <!-- Champ pour le mot de passe -->
        <p>
            <label for="mdp">Mot de passe* </label>
            <input id="mdp" type="password" name="mdp" size="30" maxlength="45">
        </p>

        <!-- Boutons pour valider ou réinitialiser le formulaire -->
        <p>
            <input type="submit" value="Valider" name="valider">
            <input type="reset" value="Annuler" name="annuler"> 
        </p>
        
    </form>

</div>
