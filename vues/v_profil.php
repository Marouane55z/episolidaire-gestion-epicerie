<div class="listeLegere"> 

    <!-- Titre de la section profil -->
    <h2>Mon profil</h2>

    <!-- Tableau affichant les informations du profil -->
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>  
            <th>Login</th>  
            <th class="date">Date embauche</th> 
        </tr>
      
        <?php    
        if (!isset($leProfil)) {
            // Si le profil n'existe pas, rediriger vers la page profil
            header('Location: index.php?uc=profilEmploye&action=profil');
            exit();
        }

        // Récupération des informations du profil
        $nom = $leProfil['nom'];
        $prenom = $leProfil['prenom'];
        $login = $leProfil['login'];
        $dateEmbauche = $leProfil['dateEmbauche'];
        ?>		
        <tr>
            <td><?php echo $nom ?></td>
            <td><?php echo $prenom ?></td>
            <td><?php echo $login ?></td>
            <td><?php echo $dateEmbauche ?></td>
        </tr>
      
    </table>

    <!-- Séparateur visuel -->
    <hr class="separateur"> 

    <!-- Section pour modifier le mot de passe -->
    <h3>Modifier le mot de passe</h3>
    <form action="index.php?uc=profilEmploye&action=modifierMotDePasse" 
          method="post" 
          onsubmit="return confirm('Voulez-vous vraiment modifier votre mot de passe ?')">

        <!-- Champ caché pour le login -->
        <input type="hidden" name="login" value="<?php echo $login ?>">

        <!-- Ancien mot de passe -->
        <label for="ancienMdp">Ancien mot de passe :</label>
        <input type="password" name="ancienMdp" id="ancienMdp" required>

        <!-- Nouveau mot de passe -->
        <label for="nouveauMdp">Nouveau mot de passe :</label>
        <input type="password" name="nouveauMdp" id="nouveauMdp" required>

        <!-- Bouton de soumission -->
        <input type="submit" value="Modifier le mot de passe" style="align-self: flex-start;">
    </form>
    
</div>
