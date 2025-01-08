USE test_db;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS spettacoli;
DROP TABLE IF EXISTS biglietti;
DROP TABLE IF EXISTS bigliettiUtente;
DROP TABLE IF EXISTS bigliettiAcquistati;

CREATE TABLE users(
    username varchar(20) PRIMARY KEY,
    password varchar(64) NOT NULL,
    nome varchar(35) NOT NULL,
    cognome varchar(50) NOT NULL
);  

-- tutte le passwords devono essere hash sha256, per ora admin ad esempio ha password "a"

INSERT INTO users(username,password, nome, cognome) VALUES
('admin','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Mario', 'Rossi'),
('user','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', "Lucia", "Bianchi");


CREATE TABLE spettacoli(
    titolo  varchar(50) PRIMARY KEY,
    descrizione varchar(500) NOT NULL
);

INSERT INTO spettacoli(titolo,descrizione) VALUES
('Turbo Thrill: The Ultimate Stunt Show','Preparati a rimanere senza fiato mentre i motori ruggiscono e le gomme bruciano nel più incredibile stunt show mai visto! Auto da corsa, moto acrobatiche e veicoli personalizzati sfidano le leggi della fisica con salti mozzafiato, inseguimenti ad alta velocità e manovre spettacolari. Uno show adrenalinico che ti farà battere il cuore a ogni curva!'),
('Enigma: Il Mondo della Magia','Entra in un universo di incantesimi e mistero con Enigma, lo spettacolo di magia che trasforma l’impossibile in realtà. Illusionisti di fama mondiale ti stupiranno con levitazioni, sparizioni, e incredibili giochi di prestigio che coinvolgono il pubblico in un’esperienza unica. Un viaggio magico che lascerà grandi e piccoli a bocca aperta!'),
('Fantasia Live: Un Classico da Sogno','Lasciati trasportare in un mondo di musica, colori e avventure senza tempo con Fantasia Live, uno spettacolo teatrale che combina danza, acrobazie e scenografie mozzafiato. Ispirato ai grandi classici dell’intrattenimento, questo show celebra le storie che hanno fatto sognare generazioni, reinterpretandole in un formato unico, perfetto per tutta la famiglia. Un’esperienza emozionante e indimenticabile!');

CREATE TABLE ordini(
    id int AUTO_INCREMENT PRIMARY KEY,
    utente varchar(20) NOT NULL,
    dataOrarioOrdine TIMESTAMP NOT NULL,
    FOREIGN KEY (utente) REFERENCES users(username)
);

INSERT INTO ordini(utente,dataOrarioOrdine) VALUES
('user',now()),
('user',now());

CREATE TABLE tipiBiglietto(
    descrizione varchar(50) PRIMARY KEY,
    costo numeric(6,2) NOT NULL
);

INSERT INTO tipiBiglietto(descrizione,costo) VALUES
('Biglietto Ridotto',24.99),
('Biglietto Intero',34.99);

CREATE TABLE bigliettiAcquistati(
    id int AUTO_INCREMENT PRIMARY KEY,
    tipoBiglietto varchar(50) NOT NULL,
    ordine int NOT NULL,
    FOREIGN KEY (tipoBiglietto) REFERENCES tipiBiglietto(descrizione),
    FOREIGN KEY (ordine) REFERENCES ordini(id)
);

INSERT INTO bigliettiAcquistati(tipoBiglietto,ordine) VALUES
('Biglietto Ridotto',1),
('Biglietto Intero',1),
('Biglietto Intero',1),
('Biglietto Ridotto',2),
('Biglietto Intero',2);

-- CREATE TABLE bigliettiCarrello(
--     biglietto char(5),
--     utente varchar(20),
--     quantita smallint(2) NOT NULL,
--     PRIMARY KEY (biglietto,utente),
--     FOREIGN KEY (utente) REFERENCES users(username),
--     FOREIGN KEY (biglietto) REFERENCES biglietti(id)
-- );

-- INSERT INTO bigliettiCarrello(biglietto,utente,quantita) VALUES 
-- ('1','user',5),
-- ('2','user',1);



-- CREATE TABLE ordiniBiglietti(
--     ordine int NOT NULL,
--     tipoBiglietto char(5),
--     quantita smallint(2) NOT NULL,
--     PRIMARY KEY (ordine, tipoBiglietto),
--     FOREIGN KEY (ordine) REFERENCES ordini(id),
--     FOREIGN KEY (tipoBiglietto) REFERENCES biglietti(id)
-- );

-- INSERT INTO ordiniBiglietti(ordine, tipoBiglietto, quantita) VALUES 
-- ('1','1',5),
-- ('1','2',1),
-- ('2','1',2),
-- ('2','2',2);
