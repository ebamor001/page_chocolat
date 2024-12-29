

--
-- Base de données : `choco_shop`
--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `nom_client` varchar(100) DEFAULT NULL,
  `email_client` varchar(100) DEFAULT NULL,
  `numero_telephone` varchar(15) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `produits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`produits`)),
  `quantites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quantites`)),
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('En attente de traitement','En cours de préparation','Prêt à expédier','Expédié','Livré') DEFAULT 'En attente de traitement',
  `etat_paiement` enum('','Sur place','Paylib','Virement','Payé en ligne') NOT NULL DEFAULT ''
) 
