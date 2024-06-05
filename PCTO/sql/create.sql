CREATE DATABASE pcto;

use pcto;

CREATE TABLE Azienda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    indirizzo VARCHAR(255),
    descrizione TEXT,
    cap VARCHAR(10)
);

CREATE TABLE Studente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    cognome VARCHAR(255),
    email VARCHAR(255),
    classe VARCHAR(50),
    password VARCHAR(255),
    cap VARCHAR(10)
);

CREATE TABLE Tutor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    cognome VARCHAR(255),
    numTel VARCHAR(20),
    email VARCHAR(255),
    idAzienda INT,
    FOREIGN KEY (idAzienda) REFERENCES Azienda(id)
);

CREATE TABLE Recensione (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voto INT,
    commento TEXT,
    idStudente INT,
    idAzienda INT,
    FOREIGN KEY (idStudente) REFERENCES Studente(id),
    FOREIGN KEY (idAzienda) REFERENCES Azienda(id)
);

CREATE TABLE Amministratore (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    cognome VARCHAR(255),
    email VARCHAR(255),
    classe VARCHAR(50),
    password VARCHAR(255)
);

CREATE TABLE DiarioDiBordo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giorno DATE,
    entrataMattina TIME,
    uscitaMattina TIME,
    entrataPome TIME,
    uscitaPome TIME,
    descrizione TEXT,
    idStudente INT,
    FOREIGN KEY (idStudente) REFERENCES Studente(id)
);

CREATE TABLE Assegnazione (
    idStudente INT,
    idAzienda INT,
    PRIMARY KEY (idStudente, idAzienda),
    FOREIGN KEY (idStudente) REFERENCES Studente(id),
    FOREIGN KEY (idAzienda) REFERENCES Azienda(id)
);

CREATE TABLE Preferiti (
    idStudente INT,
    idAzienda INT,
    PRIMARY KEY (idStudente, idAzienda),
    FOREIGN KEY (idStudente) REFERENCES Studente(id),
    FOREIGN KEY (idAzienda) REFERENCES Azienda(id)
); 