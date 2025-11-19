<!-- Barre latérale fixe -->
<div id="menuGauche">
    
    <!-- Zone d'informations utilisateur avec logo et titre de l'application -->
    <div id="infosUtil">
        <img src="./images/LogoGetcet.png" alt="Logo Mairie de Getcet" />
        <span class="appTitle">EPI'Solidaire</span>
    </div>  

    <!-- Menu principal -->
    <ul id="menuList">

        <!-- Affichage prénom et nom de l'utilisateur -->
        <li>
            <?php echo $_SESSION['prenom']." ".$_SESSION['nom'] ?>
        </li>

        <!-- Affichage du métier de l'utilisateur -->
        <li>
            <?php echo $_SESSION['metier'] ?>
        </li>

        <!-- Lien vers le profil utilisateur -->
        <li class="smenu">
            <a href="index.php?uc=profilEmploye&action=profil" title="Mon Profil">Mon Profil</a>
        </li>

        <!-- Gestion des commerces accessible seulement aux secrétaires et maires -->
        <?php if($_SESSION['metier']=="secretaire" || $_SESSION['metier']=="maire"){ ?>
        <li class="smenu">
            <a href="index.php?uc=listeCommerces&action=gererCommerces" title="Gestion des commerces">Gestion des commerces</a>
        </li>
        <?php } ?>

        <!-- Gestion des acheteurs et commandes accessible aux épiciers et maires -->
        <?php if($_SESSION['metier']=="epicier" || $_SESSION['metier']=="maire"){ ?>
        <li class="smenu">
            <a href="index.php?uc=listeAcheteurs&action=gererAcheteurs" title="Gestion des acheteurs">Gestion des acheteurs</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=listeCommandes&action=gererCommandes" title="Gestion des Commandes">Gestion des commandes</a>
        </li>
        <?php } ?>

        <!-- Gestion des stocks et des périmés accessible aux épiciers et maires -->
        <?php if($_SESSION['metier']=="epicier" || $_SESSION['metier']=="maire"){ ?>
        <li class="smenu">
            <a href="index.php?uc=gererStock&action=validerEntrees" title="Gestion des stocks">Gestion des stocks</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=gererStock&action=gererPerimes" title="Gérer les périmés">Gérer les périmés</a>
        </li>
        <?php } ?>

        <!-- Lien de déconnexion toujours disponible -->
        <li class="smenu">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Se déconnecter</a>
        </li>
        
    </ul>
    
</div>
