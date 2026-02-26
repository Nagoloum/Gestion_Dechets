CREATE DATABASE EcoPro;
USE EcoPro;

CREATE TABLE usager (
    id_usager INT PRIMARY KEY AUTO_INCREMENT,
    numero_telephone VARCHAR(15) NOT NULL DEFAULT 'x-xx-xx-xx-xx'
    -- La contrainte CHECK REGEXP n'est pas supportée dans MySQL < 8.0.16
);

CREATE TABLE administrateur (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    mot_de_passe TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE dechets (
    id_dechets INT PRIMARY KEY AUTO_INCREMENT,
    type_de_dechets VARCHAR(255) NOT NULL,
    quantite INT NOT NULL CHECK (quantite > 0),
    descriptions TEXT NOT NULL,
    location TEXT NOT NULL,  -- Correction du type pour une coordonnée GPS
    photo_path TEXT NOT NULL
);

CREATE TABLE signaler (
    id_usager INT NOT NULL,
    id_dechets INT NOT NULL,
    PRIMARY KEY (id_usager, id_dechets),
    FOREIGN KEY (id_usager) REFERENCES usager (id_usager) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_dechets) REFERENCES dechets (id_dechets) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE agent_collecte (
    id_agent INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    mot_de_passe TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    statut VARCHAR(10) NOT NULL DEFAULT 'libre' CHECK (statut IN ('occupe', 'libre'))
);

CREATE TABLE recuperer (
    id_dechets INT NOT NULL,
    id_agent INT NOT NULL,
    photo TEXT NOT NULL,
    statut VARCHAR(10) NOT NULL DEFAULT 'En attente' CHECK (statut IN ('En attente', 'Attribué', 'recupéré')),
    PRIMARY KEY (id_dechets, id_agent),
    FOREIGN KEY (id_dechets) REFERENCES dechets (id_dechets) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_agent) REFERENCES agent_collecte (id_agent) ON UPDATE CASCADE ON DELETE CASCADE
);
