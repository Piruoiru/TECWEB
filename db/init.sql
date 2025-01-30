USE test_db;

DROP TABLE IF EXISTS bigliettiAcquistati;
DROP TABLE IF EXISTS bigliettiCarrello;
DROP TABLE IF EXISTS ordini;
DROP TABLE IF EXISTS biglietti;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS spettacoli;

CREATE TABLE users(
    username varchar(20) PRIMARY KEY,
    password varchar(64) NOT NULL,
    nome varchar(35) NOT NULL,
    cognome varchar(50) NOT NULL
);  

-- tutte le passwords devono essere hash sha256, per ora admin ad esempio ha password "a"

INSERT INTO users(username,password, nome, cognome) VALUES
('admin','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'Mario', 'Rossi'),
('user','04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb', 'Lucia', 'Bianchi');


CREATE TABLE spettacoli(
    titolo  varchar(50) PRIMARY KEY NOT NULL,
    descrizione varchar(500) NOT NULL,
    percorso_immagine varchar(200) NOT NULL,
    descrizione_immagine varchar(100) NOT NULL
);

INSERT INTO spettacoli(titolo,descrizione,percorso_immagine,descrizione_immagine) VALUES
('Turbo Thrill: The Ultimate Stunt Show','Preparati a rimanere senza fiato mentre i motori ruggiscono e le gomme bruciano nel più incredibile stunt show mai visto! Auto da corsa, moto acrobatiche e veicoli personalizzati sfidano le leggi della fisica con salti mozzafiato, inseguimenti ad alta velocità e manovre spettacolari. Uno show adrenalinico che ti farà battere il cuore a ogni curva!','thurbo.jpg','Immagine di decorazione, spettacolo con macchine e percorso a ostacoli'),
('Enigma: Il Mondo della Magia','Entra in un universo di incantesimi e mistero con Enigma, lo spettacolo di magia che trasforma l’impossibile in realtà. Illusionisti di fama mondiale ti stupiranno con levitazioni, sparizioni, e incredibili giochi di prestigio che coinvolgono il pubblico in un’esperienza unica. Un viaggio magico che lascerà grandi e piccoli a bocca aperta!','enigma.jpg','Immagine di decorazione, mago con assistente al loro show luminoso'),
('Fantasia Live: Un Classico da Sogno','Lasciati trasportare in un mondo di musica, colori e avventure senza tempo con Fantasia Live, uno spettacolo teatrale che combina danza, acrobazie e scenografie mozzafiato. Ispirato ai grandi classici dell’intrattenimento, questo show celebra le storie che hanno fatto sognare generazioni, reinterpretandole in un formato unico, perfetto per tutta la famiglia. Un’esperienza emozionante e indimenticabile!','fantasia_live.jpg','Immagine di decorazione, show di danza e acrobazie varie'),
('Luminaria: Il Gioco delle Luci','Immergiti in Luminaria, lo spettacolo che unisce arte, tecnologia e fantasia in un’esplosione di luci e colori. Proiezioni, coreografie luminose e illusioni ottiche trasformano il palco in un universo scintillante, regalando un’esperienza visiva e sensoriale che incanterà grandi e piccoli!','luminaria.webp','Immagine di decorazione danzatori luminosi, giochi di luce, con posti a sedere davanti lo show');

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
    dataOrarioOrdine TIMESTAMP NOT NULL,
    quantita smallint(2) NOT NULL,
    PRIMARY KEY (biglietto,utente),
    FOREIGN KEY (utente) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (biglietto) REFERENCES biglietti(id)
);

CREATE TABLE ordini(
    id int AUTO_INCREMENT PRIMARY KEY,
    utente varchar(20) NOT NULL,
    dataOrarioOrdine TIMESTAMP NOT NULL,
    FOREIGN KEY (utente) REFERENCES users(username) ON DELETE CASCADE
);

INSERT INTO ordini(utente,dataOrarioOrdine) VALUES
('user',now()),
('user',now());

CREATE TABLE bigliettiAcquistati(
    id int AUTO_INCREMENT PRIMARY KEY,
    tipoBiglietto char(5) NOT NULL,
    ordine int NOT NULL,
    sommaPagata numeric(6,2) NOT NULL,
    FOREIGN KEY (tipoBiglietto) REFERENCES biglietti(id),
    FOREIGN KEY (ordine) REFERENCES ordini(id) ON DELETE CASCADE
);

INSERT INTO bigliettiAcquistati(tipoBiglietto,ordine,sommaPagata) VALUES
('1',1,24.99),
('2',1,34.99),
('2',1,34.99),
('1',2,24.99),
('2',2,34.99);
