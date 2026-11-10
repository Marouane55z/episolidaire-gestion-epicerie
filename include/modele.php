<?php
/**
 * Classe d'accès aux données pour l'application GETCET
 * Utilise PDO pour toutes les opérations sur la base de données
 *
 * @package default
 * @author Cheri Bibi
 * @version 1.0
 */
class Modele {  

    /********** Paramètres de connexion **********/
    // Aucun identifiant n'est écrit dans le code : voir chargerConfiguration().

    // Objet PDO et instance unique (singleton)
    private static $monPdo;
    private static $monModele = null;

    /********** Constructeur privé **********/
    /**
     * Constructeur : crée l'objet PDO pour la connexion
     */
    private function __construct() {
        $config = Modele::chargerConfiguration();
        $dsn = $config['dsn'] !== ''
            ? $config['dsn']
            : 'mysql:host=' . $config['hote'] . ';port=' . $config['port']
              . ';dbname=' . $config['base'] . ';charset=utf8mb4';
        Modele::$monPdo = new PDO($dsn, $config['utilisateur'], $config['motDePasse'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    /**
     * Lit les paramètres de connexion, par ordre de priorité :
     * 1. variables d'environnement EPI_DB_* (Docker, hébergeur) ;
     * 2. fichier include/config.php (non versionné, copié depuis config.example.php) ;
     * 3. valeurs par défaut d'une installation locale (XAMPP, WAMP, MAMP).
     * @return array
     */
    private static function chargerConfiguration() {
        $config = [
            'dsn' => '',
            'hote' => 'localhost',
            'port' => '3306',
            'base' => 'getcet',
            'utilisateur' => 'root',
            'motDePasse' => '',
        ];
        $fichier = __DIR__ . '/config.php';
        if (is_file($fichier)) {
            $config = array_merge($config, (array) require $fichier);
        }
        $variables = [
            'dsn' => 'EPI_DB_DSN',
            'hote' => 'EPI_DB_HOST',
            'port' => 'EPI_DB_PORT',
            'base' => 'EPI_DB_NAME',
            'utilisateur' => 'EPI_DB_USER',
            'motDePasse' => 'EPI_DB_PASSWORD',
        ];
        foreach ($variables as $cle => $variable) {
            $valeur = getenv($variable);
            if ($valeur !== false) {
                $config[$cle] = $valeur;
            }
        }
        return $config;
    }

    /**
     * Destructeur : détruit l'objet PDO
     */
    public function _destruct() {
        Modele::$monPdo = null;
    }

    /********** Singleton **********/
    /**
     * Retourne l'unique instance de Modele
     * @return Modele
     */
    public static function getModele() {
        if (Modele::$monModele == null) {
            Modele::$monModele = new Modele();
        }
        return Modele::$monModele;
    }

    /**
     * Retourne l'objet PDO
     * @return PDO
     */
    public function getBdd() {
        return self::$monPdo;
    }

    /********** Gestion des employés **********/

    /**
     * Vérifie la connexion d'un employé
     * @param string $login : le login de l'employé
     * @param string $mdp : le mot de passe en clair
     * @return int : 1 si connexion réussie, 0 sinon
     */
    public function testConnexionEmploye($login, $mdp) {
        $res = 0;
        $req = "SELECT mdp FROM employe WHERE login = ?";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $login);
        $stmt->execute();
        $ligne = $stmt->fetch();

        if ($ligne) {
            $mdpHashBdd = $ligne['mdp'];
            if (password_verify($mdp, $mdpHashBdd)) {
                $res = 1;
            }
        }
        return $res;
    }

    /**
     * Récupère les informations d'un employé à partir de son login
     * @param string $login
     * @return array|false : tableau associatif avec id, nom, prenom, metier ou false si non trouvé
     */
    public function getInfosEmploye($login) {
        $req = "SELECT idEmploye as id, nom, prenom, metier FROM employe WHERE login = :login";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Récupère les informations d'un employé à partir de son ID
     * @param int $id
     * @return array|false : tableau associatif avec nom, prenom, login, dateEmbauche
     */
    public function getInfosEmployeById($id) {
        $req = "SELECT nom, prenom, login, dateEmbauche FROM employe WHERE idEmploye = :id";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ligne = $stmt->fetch();
        if ($ligne) {
            // Conversion de la date au format français
            $ligne['dateEmbauche'] = dateAnglaisVersFrancais($ligne['dateEmbauche']);
        }
        return $ligne;
    }

    /**
     * Modifie le mot de passe d'un employé
     * @param string $login : login de l'employé
     * @param string $mdp : nouveau mot de passe en clair
     * @return bool : true si succès, false sinon
     */
    public function modifierMotDePasse($login, $mdp) {
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt = Modele::$monPdo->prepare("UPDATE employe SET mdp = :mdp WHERE login = :login");
        $stmt->bindParam(':mdp', $mdpHash);
        $stmt->bindParam(':login', $login);
        return $stmt->execute();
    }

    /********** Gestion des acheteurs **********/

    /**
     * Récupère tous les acheteurs
     * @return array : tableau associatif de tous les acheteurs
     */
    public function getLesAcheteurs() {
        $req = "SELECT * FROM acheteur 
                JOIN habitant ON habitant.idHabitant = acheteur.idHabitant 
                AND habitant.idFoyer = acheteur.idFoyer";
        $res = Modele::$monPdo->query($req);
        return $res->fetchAll();
    }

    /**
     * Supprime un acheteur
     * @param int $idAcheteur
     * @return bool : true si suppression réussie, false sinon
     */
    public function supprimerAcheteur($idAcheteur) {
        $req = "DELETE FROM acheteur WHERE id = ?";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $idAcheteur, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /********** Gestion des commerces **********/

    /**
     * Récupère tous les commerces
     * @return array : tableau associatif de tous les commerces
     */
    public function getLesCommerces() {
        $res = Modele::$monPdo->query("SELECT * FROM commerce");
        return $res->fetchAll();
    }

    /**
     * Supprime un commerce
     * @param int $id
     */
    public function supprimerCommerce($id) {
        Modele::$monPdo->exec("DELETE FROM commerce WHERE id = $id");
    }

    /**
     * Ajoute un commerce
     * @param string $nom
     * @param string $rue
     * @param string $cp
     * @param string $ville
     * @return bool : true si succès, false sinon
     */
    public function ajouterUnCommerce($nom, $rue, $cp, $ville) {
        $req = "INSERT INTO commerce (nom, rue, codePostal, ville) VALUES (?, ?, ?, ?)";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $rue, PDO::PARAM_STR);
        $stmt->bindParam(3, $cp, PDO::PARAM_STR);
        $stmt->bindParam(4, $ville, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /********** Gestion des commandes **********/

    /**
     * Récupère toutes les commandes
     * @return array : tableau associatif de toutes les commandes
     */
    public function getLesCommandes() {
        $res = Modele::$monPdo->query("SELECT * FROM commande");
        return $res->fetchAll();
    }

    /**
     * Supprime une commande
     * @param int $idCommande
     * @return bool
     */
    public function supprimerCommande($idCommande) {
        $req = "DELETE FROM commande WHERE id = ?";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $idCommande, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Ajoute une commande et retourne son ID
     * @param int $idAcheteur
     * @param float $prixTotal
     * @return int|false : ID de la commande ou false si échec
     */
    public function ajouterUneCommande($idAcheteur, $prixTotal) {
        $req = "INSERT INTO commande (idAcheteur, dateCommande, prixTotal) VALUES (?, NOW(), ?)";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $idAcheteur, PDO::PARAM_INT);
        $stmt->bindParam(2, $prixTotal, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt ? Modele::$monPdo->lastInsertId() : false;
    }

    /**
     * Change l'état d'une commande
     * @param int $idCommande
     * @param string $nouvelEtat
     * @return bool
     */
    public function changerEtatCommande($idCommande, $nouvelEtat) {
        $req = "UPDATE commande SET etat = ? WHERE id = ?";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $nouvelEtat, PDO::PARAM_STR);
        $stmt->bindParam(2, $idCommande, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /********** Gestion des articles **********/

    /**
     * Récupère tous les produits
     * @return array
     */
    public function getLesProduits() {
        $res = Modele::$monPdo->query("SELECT * FROM produit");
        return $res->fetchAll();
    }

    /**
     * Récupère les articles à valider
     * @return array : articles dont l'état est 'Enregistré'
     */
    public function getArticlesAValider() {
        $req = "SELECT a.id as idArticle, p.designation, a.datePeremption
                FROM article a
                JOIN produit p ON a.refProduit = p.reference
                JOIN etatarticle e ON a.idEtat = e.id
                WHERE e.libelle = 'Enregistré'";
        $res = Modele::$monPdo->query($req);
        return $res->fetchAll();
    }

    /**
     * Valide un article (change son état à 'En vente')
     * @param int $idArticle
     * @return bool
     */
    public function validerArticle($idArticle) {
        $req = "UPDATE article SET idEtat = 2 WHERE id = ?";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $idArticle, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne les articles périmés
     * @return array
     */
    public function getArticlesPerimes() {
        $req = "SELECT a.id as idArticle, p.designation, a.datePeremption
                FROM article a
                JOIN produit p ON a.refProduit = p.reference
                WHERE (a.idEtat = 2 OR a.idEtat = 3) 
                AND a.datePeremption < CURDATE()
                ORDER BY a.datePeremption ASC";
        $res = Modele::$monPdo->query($req);
        return $res->fetchAll();
    }

    /**
     * Retourne les articles périssables dans les X prochains jours
     * @param int $nbJours
     * @return array
     */
    public function getArticlesPerissables($nbJours) {
        $req = "SELECT a.id as idArticle, p.designation, a.datePeremption
                FROM article a
                JOIN produit p ON a.refProduit = p.reference
                WHERE (a.idEtat = 2 OR a.idEtat = 3) 
                AND a.datePeremption BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY a.datePeremption ASC";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $nbJours, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Déclare un article comme 'Donné'
     * @param int $idArticle
     * @return bool
     */
    public function declarerArticleDonne($idArticle) {
        $stmt = Modele::$monPdo->prepare("UPDATE article SET idEtat = 9 WHERE id = ?");
        $stmt->bindParam(1, $idArticle, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Déclare un article comme 'Jeté'
     * @param int $idArticle
     * @return bool
     */
    public function declarerArticleJete($idArticle) {
        $stmt = Modele::$monPdo->prepare("UPDATE article SET idEtat = 8 WHERE id = ?");
        $stmt->bindParam(1, $idArticle, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Met à jour les articles périssables (idEtat = 3)
     * @param int $nbJours
     * @return bool
     */
    public function mettreAJourEtatsPerissables($nbJours) {
        $stmt = Modele::$monPdo->prepare(
            "UPDATE article SET idEtat = 3 
             WHERE idEtat = 2 
             AND datePeremption BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)"
        );
        $stmt->bindParam(1, $nbJours, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Baisse le stock d'un produit
     * @param string $refProduit
     * @param int $qteBaissee
     * @return bool
     */
    public function baisserStockProduit($refProduit, $qteBaissee) {
        $stmt = Modele::$monPdo->prepare("UPDATE produit SET qteStock = qteStock - ? WHERE reference = ?");
        $stmt->bindParam(1, $qteBaissee, PDO::PARAM_INT);
        $stmt->bindParam(2, $refProduit, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Retourne les articles disponibles à la vente (idEtat = 2 ou 3)
     * @return array
     */
    public function getArticlesDisponibles() {
        $req = "SELECT a.id as idArticle, a.refProduit, a.datePeremption, p.designation, p.prix
                FROM article a
                JOIN produit p ON a.refProduit = p.reference
                WHERE a.idEtat = 2 OR a.idEtat = 3
                ORDER BY p.designation ASC, a.datePeremption ASC";
        $res = Modele::$monPdo->query($req);
        return $res->fetchAll();
    }

    /**
     * Crée une ligne de commande
     * @param int $idCommande
     * @param int $idArticle
     * @param string $refProduit
     * @param float $prixAchat
     * @return bool
     */
    public function creerLigneCommande($idCommande, $idArticle, $refProduit, $prixAchat) {
        $req = "INSERT INTO ligne_commande (idCommande, idArticle, refProduit, prixAchat) VALUES (?, ?, ?, ?)";
        $stmt = Modele::$monPdo->prepare($req);
        $stmt->bindParam(1, $idCommande, PDO::PARAM_INT);
        $stmt->bindParam(2, $idArticle, PDO::PARAM_INT);
        $stmt->bindParam(3, $refProduit, PDO::PARAM_STR);
        $stmt->bindParam(4, $prixAchat, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Passe un article en état 'Commandé' (idEtat = 6)
     * @param int $idArticle
     * @param string $refProduit
     * @return bool
     */
    public function passerArticleCommande($idArticle, $refProduit) {
        $stmt = Modele::$monPdo->prepare("UPDATE article SET idEtat = 6 WHERE id = ? AND refProduit = ?");
        $stmt->bindParam(1, $idArticle, PDO::PARAM_INT);
        $stmt->bindParam(2, $refProduit, PDO::PARAM_STR);
        return $stmt->execute();
    }

}
?>
