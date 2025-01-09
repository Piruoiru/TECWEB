USE test_db;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS spettacoli;
DROP TABLE IF EXISTS biglietti;
DROP TABLE IF EXISTS bigliettiUtente;
DROP TABLE IF EXISTS bigliettiAcquistati;

CREATE TABLE users(
    username varchar(20) NOT NULL PRIMARY KEY,
    password varchar(64) NOT NULL,
    name varchar(35) NOT NULL,
    surname varchar(50) NOT NULL
);  

-- tutte le passwords devono essere hash sha256, per ora admin ad esempio ha password "a"

INSERT INTO users(username,password, name, surname) VALUES
('admin','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Mario', 'Rossi'),
('user','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Lucia', 'Bianchi');


CREATE TABLE spettacoli(
    titolo  varchar(50) PRIMARY KEY NOT NULL,
    descrizione varchar(500) NOT NULL,
    percorso_immagine varchar(30) NOT NULL
);

INSERT INTO spettacoli(titolo,descrizione,percorso_immagine) VALUES
('Turbo Thrill: The Ultimate Stunt Show','Preparati a rimanere senza fiato mentre i motori ruggiscono e le gomme bruciano nel più incredibile stunt show mai visto! Auto da corsa, moto acrobatiche e veicoli personalizzati sfidano le leggi della fisica con salti mozzafiato, inseguimenti ad alta velocità e manovre spettacolari. Uno show adrenalinico che ti farà battere il cuore a ogni curva!','famiglia_felice.png'),
('Enigma: Il Mondo della Magia','Entra in un universo di incantesimi e mistero con Enigma, lo spettacolo di magia che trasforma l’impossibile in realtà. Illusionisti di fama mondiale ti stupiranno con levitazioni, sparizioni, e incredibili giochi di prestigio che coinvolgono il pubblico in un’esperienza unica. Un viaggio magico che lascerà grandi e piccoli a bocca aperta!','famiglia_felice.png'),
('Fantasia Live: Un Classico da Sogno','Lasciati trasportare in un mondo di musica, colori e avventure senza tempo con Fantasia Live, uno spettacolo teatrale che combina danza, acrobazie e scenografie mozzafiato. Ispirato ai grandi classici dell’intrattenimento, questo show celebra le storie che hanno fatto sognare generazioni, reinterpretandole in un formato unico, perfetto per tutta la famiglia. Un’esperienza emozionante e indimenticabile!','famiglia_felice.png');


CREATE TABLE biglietti(
    id char(5) PRIMARY KEY NOT NULL,
    titolo varchar(50) NOT NULL,
    costo numeric(6,2) NOT NULL
);

INSERT INTO biglietti(id,titolo,costo) VALUES
('1','Biglietto Ridotto',24.99),
('2','Biglietto Intero',34.99);



CREATE TABLE bigliettiCarrello(
    biglietto char(5) NOT NULL,
    utente varchar(20) NOT NULL,
    quantita smallint(2) NOT NULL,
    PRIMARY KEY (biglietto,utente),
    FOREIGN KEY (utente) REFERENCES users(username),
    FOREIGN KEY (biglietto) REFERENCES biglietti(id)
);

INSERT INTO bigliettiCarrello(biglietto,utente,quantita) VALUES 
('1','user',5),
('2','user',1);

CREATE TABLE bigliettiAcquistati(
    biglietto char(5) NOT NULL,
    utente varchar(20) NOT NULL,
    dataOrarioAcquisto TIMESTAMP NOT NULL,
    quantita smallint(2) NOT NULL,
    PRIMARY KEY (biglietto,utente,dataOrarioAcquisto),
    FOREIGN KEY (utente) REFERENCES users(username),
    FOREIGN KEY (biglietto) REFERENCES biglietti(id)
);

INSERT INTO bigliettiAcquistati(biglietto,utente,dataOrarioAcquisto,quantita) VALUES 
('1','user',now(),5),
('2','user',now(),4);
