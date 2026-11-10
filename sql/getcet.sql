-- -----------------------------------------------------------------------------
-- Épi'Solidaire : base de démonstration « getcet » (MySQL 8 / MariaDB 10.5+)
--
-- Schéma reconstitué à partir des requêtes de include/modele.php : le script
-- d'origine n'a pas été conservé. Toutes les données sont fictives.
--
-- Comptes de démonstration (mot de passe : demo) :
--   secretaire / demo   -> commerces partenaires
--   epicier    / demo   -> acheteurs, commandes, stock, périmés
--   maire      / demo   -> toutes les fonctionnalités
-- -----------------------------------------------------------------------------

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS getcet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE getcet;

DROP TABLE IF EXISTS ligne_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS article;
DROP TABLE IF EXISTS etatarticle;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS acheteur;
DROP TABLE IF EXISTS habitant;
DROP TABLE IF EXISTS foyer;
DROP TABLE IF EXISTS commerce;
DROP TABLE IF EXISTS employe;

-- Agents de la mairie qui utilisent l'application
CREATE TABLE employe (
    idEmploye    INT AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(50)  NOT NULL,
    prenom       VARCHAR(50)  NOT NULL,
    login        VARCHAR(30)  NOT NULL UNIQUE,
    mdp          VARCHAR(255) NOT NULL COMMENT 'hachage password_hash()',
    metier       ENUM('secretaire', 'epicier', 'maire') NOT NULL,
    dateEmbauche DATE NOT NULL
) ENGINE = InnoDB;

-- Commerces partenaires qui donnent des produits
CREATE TABLE commerce (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(80) NOT NULL,
    rue        VARCHAR(120) NOT NULL,
    codePostal CHAR(5) NOT NULL,
    ville      VARCHAR(60) NOT NULL
) ENGINE = InnoDB;

-- Foyers et habitants de la commune
CREATE TABLE foyer (
    idFoyer    INT AUTO_INCREMENT PRIMARY KEY,
    rue        VARCHAR(120) NOT NULL,
    codePostal CHAR(5) NOT NULL,
    ville      VARCHAR(60) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE habitant (
    idFoyer           INT NOT NULL,
    idHabitant        INT NOT NULL,
    nom               VARCHAR(50) NOT NULL,
    prenom            VARCHAR(50) NOT NULL,
    dateNaiss         DATE NOT NULL,
    telephonePortable VARCHAR(14),
    mail              VARCHAR(120),
    PRIMARY KEY (idFoyer, idHabitant),
    FOREIGN KEY (idFoyer) REFERENCES foyer (idFoyer) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Habitants autorisés à acheter à l'épicerie solidaire
CREATE TABLE acheteur (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    idFoyer    INT NOT NULL,
    idHabitant INT NOT NULL,
    FOREIGN KEY (idFoyer, idHabitant) REFERENCES habitant (idFoyer, idHabitant) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Catalogue : un produit regroupe des articles de même référence
CREATE TABLE produit (
    reference   VARCHAR(20) PRIMARY KEY,
    designation VARCHAR(100) NOT NULL,
    prix        DECIMAL(6, 2) NOT NULL,
    qteStock    INT NOT NULL DEFAULT 0
) ENGINE = InnoDB;

-- États d'un article (identifiants utilisés en dur dans include/modele.php)
CREATE TABLE etatarticle (
    id      INT PRIMARY KEY,
    libelle VARCHAR(30) NOT NULL
) ENGINE = InnoDB;

-- Article physique avec sa date de péremption
CREATE TABLE article (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    refProduit     VARCHAR(20) NOT NULL,
    datePeremption DATE NOT NULL,
    idEtat         INT NOT NULL DEFAULT 1,
    FOREIGN KEY (refProduit) REFERENCES produit (reference),
    FOREIGN KEY (idEtat) REFERENCES etatarticle (id)
) ENGINE = InnoDB;

CREATE TABLE commande (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    idAcheteur   INT NOT NULL,
    dateCommande DATETIME NOT NULL,
    prixTotal    DECIMAL(8, 2) NOT NULL,
    etat         VARCHAR(30) NOT NULL DEFAULT 'Enregistrée',
    FOREIGN KEY (idAcheteur) REFERENCES acheteur (id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE ligne_commande (
    idCommande INT NOT NULL,
    idArticle  INT NOT NULL,
    refProduit VARCHAR(20) NOT NULL,
    prixAchat  DECIMAL(6, 2) NOT NULL,
    PRIMARY KEY (idCommande, idArticle),
    FOREIGN KEY (idCommande) REFERENCES commande (id) ON DELETE CASCADE,
    FOREIGN KEY (idArticle) REFERENCES article (id),
    FOREIGN KEY (refProduit) REFERENCES produit (reference)
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------
-- Données fictives
-- -----------------------------------------------------------------------------

INSERT INTO employe (nom, prenom, login, mdp, metier, dateEmbauche) VALUES
('Martin',  'Claire', 'secretaire', '$2y$10$Zp4dBep0rsjy5PQx5BXgPec.b3G8gAD9QB9MbZpD9mui5Q.I.nI72', 'secretaire', '2021-09-01'),
('Bernard', 'Hugo',   'epicier',    '$2y$10$w3QfoVyiRAs9wyMBxXjDt.0rKy.96Ui.y3PYuXFKP/lAXoUwfiDlu', 'epicier',    '2022-03-14'),
('Durand',  'Sophie', 'maire',      '$2y$10$D/u5sFlV.8lCYLU6r9unO.mcTq5n.2nVjwXyrO7TOi7tmLAqLRKn2', 'maire',      '2020-06-01');

INSERT INTO commerce (nom, rue, codePostal, ville) VALUES
('Boulangerie du Centre', '12 rue de la Mairie',     '99000', 'Getcet'),
('Primeur des Halles',    '3 place du Marché',       '99000', 'Getcet'),
('Supérette Getcet',      '45 avenue de la Gare',    '99000', 'Getcet');

INSERT INTO foyer (idFoyer, rue, codePostal, ville) VALUES
(1, '8 rue des Lilas',     '99000', 'Getcet'),
(2, '21 allée des Chênes', '99000', 'Getcet'),
(3, '5 impasse du Moulin', '99000', 'Getcet');

INSERT INTO habitant (idFoyer, idHabitant, nom, prenom, dateNaiss, telephonePortable, mail) VALUES
(1, 1, 'Petit',   'Julie',  '1985-04-12', '06 00 00 00 01', 'julie.petit@example.org'),
(1, 2, 'Petit',   'Marc',   '1983-11-02', '06 00 00 00 02', 'marc.petit@example.org'),
(2, 1, 'Moreau',  'Nadia',  '1992-07-23', '06 00 00 00 03', 'nadia.moreau@example.org'),
(3, 1, 'Leroy',   'Thomas', '1978-01-30', '06 00 00 00 04', 'thomas.leroy@example.org');

INSERT INTO acheteur (id, idFoyer, idHabitant) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

INSERT INTO produit (reference, designation, prix, qteStock) VALUES
('PAT-500', 'Pâtes 500 g',            0.50, 40),
('RIZ-1K',  'Riz 1 kg',               0.90, 25),
('LAI-1L',  'Lait demi-écrémé 1 L',   0.40, 30),
('YAO-4',   'Yaourts nature x4',      0.60, 12),
('POM-1K',  'Pommes 1 kg',            0.80, 15),
('CON-TOM', 'Conserve de tomates',    0.35, 20);

INSERT INTO etatarticle (id, libelle) VALUES
(1, 'Enregistré'),
(2, 'Validé'),
(3, 'Périssable'),
(6, 'Commandé'),
(8, 'Jeté'),
(9, 'Donné');

-- Dates relatives au jour de l'import, pour que les écrans « stock » et
-- « périmés » aient toujours des données à afficher.
INSERT INTO article (refProduit, datePeremption, idEtat) VALUES
('YAO-4',   DATE_ADD(CURDATE(), INTERVAL 10 DAY),  1),
('LAI-1L',  DATE_ADD(CURDATE(), INTERVAL 20 DAY),  1),
('POM-1K',  DATE_ADD(CURDATE(), INTERVAL 7 DAY),   1),
('PAT-500', DATE_ADD(CURDATE(), INTERVAL 300 DAY), 2),
('RIZ-1K',  DATE_ADD(CURDATE(), INTERVAL 400 DAY), 2),
('LAI-1L',  DATE_ADD(CURDATE(), INTERVAL 3 DAY),   2),
('YAO-4',   DATE_ADD(CURDATE(), INTERVAL 2 DAY),   3),
('YAO-4',   DATE_SUB(CURDATE(), INTERVAL 1 DAY),   3),
('POM-1K',  DATE_SUB(CURDATE(), INTERVAL 4 DAY),   2),
('CON-TOM', DATE_ADD(CURDATE(), INTERVAL 600 DAY), 2);

INSERT INTO commande (idAcheteur, dateCommande, prixTotal, etat) VALUES
(1, DATE_SUB(NOW(), INTERVAL 6 DAY), 2.30, 'Livrée'),
(2, DATE_SUB(NOW(), INTERVAL 2 DAY), 1.80, 'Préparée et complète'),
(3, DATE_SUB(NOW(), INTERVAL 1 DAY), 1.30, 'Enregistrée');
