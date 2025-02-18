-- Structure de la table `admins`
DROP TABLE IF EXISTS admins;
CREATE TABLE IF NOT EXISTS admins (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  token VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL DEFAULT 'modo'
);

-- Déchargement des données de la table `admins`
INSERT INTO admins (id, name, email, password, token, role) VALUES
(1, 'tacosougrec', 'toto@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', '', 'admin');

-- Structure de la table `avis`
DROP TABLE IF EXISTS avis;
CREATE TABLE IF NOT EXISTS avis (
  id SERIAL PRIMARY KEY,
  id_intervention INTEGER DEFAULT NULL,
  id_client INTEGER DEFAULT NULL,
  note INTEGER DEFAULT NULL,
  commentaire TEXT
);

-- Structure de la table `comments`
DROP TABLE IF EXISTS comments;
CREATE TABLE IF NOT EXISTS comments (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  comment TEXT NOT NULL,
  post_id INTEGER NOT NULL,
  date TIMESTAMP NOT NULL,
  seen BOOLEAN NOT NULL DEFAULT FALSE
);

-- Déchargement des données de la table `comments`
INSERT INTO comments (id, name, email, comment, post_id, date, seen) VALUES
(1, 'toto', 'toto@mail.com', 'oui super !', 2, '2025-02-12 19:40:20', TRUE),
(2, 'toto', 'toto@mail.com', 'Bonjour, mon commentaire', 2, '2025-02-13 14:40:58', TRUE),
(3, 'toto', 'toto@mail.com', 'bla1', 2, '2025-02-13 17:31:57', TRUE),
(12, 'toto', 'test@mail.com', '1111', 7, '2025-02-15 17:22:26', TRUE),
(6, 'toto', 'toto@mail.com', 'bla1', 1, '2025-02-13 17:31:57', TRUE),
(16, 'toto', 'tete@mail.com', 'le bon lait', 7, '2025-02-15 18:30:16', TRUE),
(17, 'a', 'a@mail.com', '1', 6, '2025-02-15 18:52:53', TRUE);

-- Structure de la table `facture`
DROP TABLE IF EXISTS facture;
CREATE TABLE IF NOT EXISTS facture (
  id SERIAL PRIMARY KEY,
  id_intervention INTEGER DEFAULT NULL,
  prix INTEGER DEFAULT NULL
);

-- Structure de la table `intervention`
DROP TABLE IF EXISTS intervention;
CREATE TABLE IF NOT EXISTS intervention (
  id SERIAL PRIMARY KEY,
  service_id INTEGER DEFAULT NULL,
  lieu_utilisateur INTEGER DEFAULT NULL,
  date TIMESTAMP DEFAULT NULL,
  id_technicien INTEGER DEFAULT NULL,
  id_client INTEGER DEFAULT NULL
);

-- Déchargement des données de la table `intervention`
INSERT INTO intervention (id, service_id, lieu_utilisateur, date, id_technicien, id_client) VALUES
(1, 1, 1, '2025-02-14 00:00:00', 3, 1),
(2, 2, 2, '2025-02-14 18:34:24', 5, 4),
(3, 3, 1, '2025-02-14 18:35:23', 3, 1);

-- Structure de la table `lieu_utilisateur`
DROP TABLE IF EXISTS lieu_utilisateur;
CREATE TABLE IF NOT EXISTS lieu_utilisateur (
  id SERIAL PRIMARY KEY,
  adresse VARCHAR(255) DEFAULT NULL,
  code_postal INTEGER DEFAULT NULL,
  ville VARCHAR(255) DEFAULT NULL,
  id_utilisateur INTEGER DEFAULT NULL
);

-- Déchargement des données de la table `lieu_utilisateur`
INSERT INTO lieu_utilisateur (id, adresse, code_postal, ville, id_utilisateur) VALUES
(1, '62 allée des clématites', 77176, 'NANDY', 1),
(2, '1 rue du prout', 75002, 'Paris', NULL);

-- Structure de la table `notification`
DROP TABLE IF EXISTS notification;
CREATE TABLE IF NOT EXISTS notification (
  id SERIAL PRIMARY KEY,
  id_utilisateur INTEGER DEFAULT NULL,
  message TEXT,
  date_envoi TIMESTAMP DEFAULT NULL
);

-- Structure de la table `posts`
DROP TABLE IF EXISTS posts;
CREATE TABLE IF NOT EXISTS posts (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  writer VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL DEFAULT 'fond.jpg',
  date TIMESTAMP NOT NULL,
  posted BOOLEAN NOT NULL
);

-- Déchargement des données de la table `posts`
INSERT INTO posts (id, title, content, writer, image, date, posted) VALUES
(1, 'titre', 'tutoriel content', 'toto@mail.com', 'fond.jpg', '2025-02-12 18:24:00', TRUE),
(2, 'titre 222', 'content 2', 'toto@mail.com', 'fond.jpg', '2025-02-12 18:25:00', TRUE),
(3, 'titre pas visible', 'ce blog n\'est pas censé etre visible', 'toto@mail.com', 'fond.jpg', '2025-02-13 13:12:41', FALSE),
(4, 'premier article sans image', 'LOL', 'toto@mail.com', 'fond.jpg', '2025-02-14 05:04:58', TRUE),
(5, 'Article avec image', 'BLABOUBOU', 'toto@mail.com', 'fond.jpg', '2025-02-14 12:55:44', TRUE);

-- Structure de la table `service`
DROP TABLE IF EXISTS service;
CREATE TABLE IF NOT EXISTS service (
  id SERIAL PRIMARY KEY,
  nom_service VARCHAR(255) DEFAULT NULL,
  prix DECIMAL(10,2) NOT NULL,
  image_path VARCHAR(255) DEFAULT NULL
);

-- Déchargement des données de la table `service`
INSERT INTO service (id, nom_service, prix, image_path) VALUES
(1, 'réparation', 30.00, '7.png'),
(2, 'entretien', 10.00, '8.png'),
(3, 'dépannage d\'urgence', 60.00, '9.png');

-- Structure de la table `technicien`
DROP TABLE IF EXISTS technicien;
CREATE TABLE IF NOT EXISTS technicien (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  numero VARCHAR(20) NOT NULL,
  service_id INTEGER DEFAULT NULL,
  image_path VARCHAR(255) DEFAULT NULL
);

-- Déchargement des données de la table `technicien`
INSERT INTO technicien (id, nom, prenom, email, numero, service_id, image_path) VALUES
(1, 'Doe', 'John', 'john@doe.com', '0101010101', 1, '7.png'),
(2, 'Die', 'Johnny', 'johnny@die.com', '0201010102', 2, '7.png'),
(3, 'Dae', 'Jonathan', 'jonathan@dae.com', '0301010103', 3, '8.png'),
(4, 'Tueur', 'Oui', 'tueur@oui', '0101010101', 1, '7.png.'),
(5, 'non', 'Oui', 'tueur@oui', '0101010101', 1, '7.png'),
(6, 'prout', 'Oui', 'tueur@oui', '0101010101', 1, '7.png');

-- Structure de la table `type_intervention`
DROP TABLE IF EXISTS type_intervention;
CREATE TABLE IF NOT EXISTS type_intervention (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) DEFAULT NULL,
  prix INTEGER DEFAULT NULL
);

-- Déchargement des données de la table `type_intervention`
INSERT INTO type_intervention (id, nom, prix) VALUES
(1, 'réparation', 10),
(2, 'entretien', 5),
(3, 'dépannage d’urgence', 50);

-- Structure de la table `utilisateur`
DROP TABLE IF EXISTS utilisateur;
CREATE TABLE IF NOT EXISTS utilisateur (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) DEFAULT NULL,
  prenom VARCHAR(255) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  token VARCHAR(255) DEFAULT NULL,
  role VARCHAR(255) DEFAULT 'user',
  lieu_utilisateur INTEGER DEFAULT NULL
);

-- Déchargement des données de la table `utilisateur`
INSERT INTO utilisateur (id, nom, prenom, email, password, token, role, lieu_utilisateur) VALUES
(1, 'sansberro', 'toto', 'toto@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', 1),
(3, 'miguel', 'pedro', 'pedro@miguel', '', NULL, NULL, NULL),
(4, 'doe', 'john', 'john@doe', '', NULL, NULL, 2),
(5, 'depanneur', 'bob', 'bob@depanneur', '', NULL, NULL, NULL),
(19, 'min', 'ad', 'admin@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'admin', NULL),
(20, 'titi', 'toto', 'hihi@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', NULL),
(21, 'toto', 'titi', 'hihi@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', NULL),
(22, 'titi', 'titi', 'hihi@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', NULL),
(23, 'pouet', 'zboui', 'allo@mail', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', NULL),
(18, 'testtoto', 'oui', 'lepain@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, 'user', NULL),
(17, 'a', 'b', 'b@mail.com', '356a192b7913b04c54574d18c28d46e6395428ab', NULL, NULL, NULL);
