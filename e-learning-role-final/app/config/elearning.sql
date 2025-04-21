-- Création de la base de données
CREATE DATABASE IF NOT EXISTS elearning;
USE elearning;

-- Table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'etudiant', 'professeur') DEFAULT 'etudiant'
);

-- Table cours
CREATE TABLE IF NOT EXISTS cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    professeur VARCHAR(100),
    contenu TEXT,
    niveau ENUM('Débutant', 'Intermédiaire', 'Difficile') DEFAULT 'Débutant',
    duree VARCHAR(50),
    image VARCHAR(255),
    pdf VARCHAR(255),
    video VARCHAR(255)
);

-- Table chapitres
CREATE TABLE IF NOT EXISTS chapitres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cours_id INT,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    pdf VARCHAR(255),
    video VARCHAR(255),
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);

-- Insertion de données d'exemple pour users (basé sur l'image 4)
INSERT INTO users (id, nom, email, password, role) VALUES
(1, 'melissa', 'melissa.abider23@gmail.com', '$2y$10$XYl9Dgi1ICNQlEnoPrqR0$Tz1AGooFhoFoGJPyacw...', 'etudiant'),
(2, 'hi', 'melissaabider@gmail.com', '$2y$10$PJDQxbW0uuoXZmFSdU7fdeVJy4uDujyL7wNJF3wq...', 'etudiant'),
(3, 'lilo', 'lilo@gmail.com', '$2y$10$YxK4mXLf44uASA6a04fR6uemlintf5Nth/b3igynRp.VfCanNWRnS', 'etudiant'),
(5, 'ABIDER', 'melissar@gmail.com', '$2y$10$.NTvEtWpa/mhvC1DFMLReLvpz0DcCAZsVvzztw/8d...', 'etudiant'),
(8, 'Admin', 'admin@example.com', '$2y$10$YxK4mXLf44uASA6a04fR6uemlintf5Nth/b3igynRp.VfCanNWRnS', 'admin');

-- Insertion de données d'exemple pour cours (basé sur l'image 3)
INSERT INTO cours (id, nom, professeur, contenu, niveau, duree, image, pdf, video) VALUES
(1, 'Introduction à l''IA', 'Prof. Dupont', 'Ce cours présente les bases de l''intelligence artificielle', 'Débutant', '5 moi', 'ia.jpg', 'Billet_aller.pdf', '89ac89e8-b983-4285-a11a-248c8d100ad4-mp4_720p.mp4'),
(2, 'Développement Web', 'Prof. Martin', 'Apprenez à créer des sites web avec HTML, CSS, JS', 'Difficile', '3 mois', 'html.jpg', 'Billet_retour.pdf', '89ac89e8-b983-4285-a11a-248c8d100ad4-mp4_720p.mp4');

-- Insertion de données d'exemple pour chapitres (basé sur l'image fournie)
INSERT INTO chapitres (id, cours_id, titre, description, pdf, video) VALUES
(1, 1, 'CM_1', 'htdtf', 'projet_LFC_etape5.pdf', '89ac89e8-b983-4285-a11a-248c8d100ad4-mp4_720p.mp4'),
(4, 1, 'CM_2', 'scszfczfd', 'Billet_aller.pdf', '89ac89e8-b983-4285-a11a-248c8d100ad4-mp4_720p.mp4');
