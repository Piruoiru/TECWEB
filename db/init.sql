USE test_db;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS spettacoli;
DROP TABLE IF EXISTS biglietti;
DROP TABLE IF EXISTS bigliettiUtente;
DROP TABLE IF EXISTS bigliettiAcquistati;

CREATE TABLE users(
    username varchar(20) NOT NULL PRIMARY KEY,
    password varchar(64) NOT NULL
);  

--nome varchar(25) NOT NULL,
--cognome varchar(25) NOT NULL,
--email varchar(30) NOT NULL,
--dataN date NOT NULL,
--telefono varchar(20) NOT NULL

-- tutte le passwords devono essere hash sha256, per ora admin ad esempio ha password "a"

INSERT INTO users(username,password) VALUES
('admin','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb'),
('user','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb');
--('davreg16','Davide','Reggiani','davideregg16@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb','1981-08-10','138-76-82'),
--('thomasC71','Thomas','Cisse','thomC71@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb','1983-10-14','571-17-61'),
--('GiugiuA11','Giulio','Ansaloni','giulioansaoli11@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb','1984-11-16','528-66-85'),
--('gabrielRonc12','Gabriel','Roncaglia','agabrielronc03@gmail.com','Gab@7600','1985-10-03','735-71-66'),
--('eneenea27','Enea','Goldoni','eneagold27@gmail.com','eneaG9@3','1986-05-09','940-52-33'),
--('nicgogo02','Nicolo','Gozzi','nickG09@gmail.com','rand234@','1986-10-03','059-71-06'),
--('giogiolodi29','Gioele','Lodi','giogiolodi@gmail.com','lodiG29@','1987-06-16','243-93-95'),
--('luigibar83','Luigi','Barbieri','luigibarb15@gmail.com','Luigi83@','1987-11-19','309-46-57'),
--('mattespo02','Matteo','Esposito','matteoesposito123@gmail.com','Matt84#','1987-12-31','971-21-50'),
--('gabrieleCC18','Gabriele','Corradini','brielecorr18@gmail.com','Gab84##','1988-10-25','008-11-58'),
--('luca89','Luca','Morandi','lucamorandi89@gmail.com','Luca#@23','1988-10-28','024-41-95'),
--('fedevv29','Federico','Venturelli','fedeventu29@gmail.com','notPass@#','1990-03-26','949-50-71'),
--('antonioC11','Antonio','Cuoghi','antoniocuoghi11@gmail.com','saFe#@22','1992-08-26','532-68-38'),
--('mattia933','Mattia','Luppi','mattialuppi28@gmail.com','Letter@#2','1993-05-31','470-52-99'),
--('thomas94','Thomas','Panini','thomaspan21@gmail.com','wAter239$','1994-12-28','137-32-28'),
--('davide26','Davide','Vandelli','davidevandelli26@gmail.com','droiD55#','1996-07-01','788-75-97'),
--('ettrig24','Ettore','Righi','ettore2407@gmail.com','fiRe@23','2001-07-30','207-13-69'),
--('noemibb10','Noemi','Borsari','noemiborsari10@gmail.com','Noemi1010$','2003-07-22','713-50-35'),
--('beass98','Beatrice','Silvestri','beatricesilvestri09@gmail.com','Beea98$#','2003-09-09','708-23-08'),
--('aurora09','Aurora','Esposito','auroraesposito23@gmail.com','Aurora98$','2004-12-23','792-73-72');


CREATE TABLE spettacoli(
    codiceS char(6) PRIMARY KEY NOT NULL,
    titolo  varchar(30) NOT NULL,
    dataS date NOT NULL,
    descrizione varchar(300) NOT NULL
);

INSERT INTO spettacoli(codiceS,titolo,dataS,descrizione) VALUES
('105691','Speciale Halloween','2024-10-31','In questa nuova avventura scopriremo i diversi segreti che nasconde Halloween, di sicuro ci saranno diverse mostri molto spaventosi che cercherano di terrorizzarci, te la senti?'),
('144131','Horror','2024-11-11','Un evento pieno di paura e molto sangue, saranno presenti diversi personaggi dei classici horror che conoscono tutti da molto tempo'),
('153706','Fantasy World','2024-11-20','Esploriamo insieme questo mondo pieno di fantasia'),
('153952','Space War','2024-11-29','La guerra tra le galssie è iniziata, chi vincerà lo scontro finale? combatti insieme a noi'),
('172189','Life in ou Planet','2024-12-02','Scopriamo i diversi tipi di animali che finora erano rimasti nascosti'),
('189504','Special Force','2024-12-13','La Special Force è pronta per salvare il nostro pianeta un altra volta'),
('193107','Talent Show','2024-12-18','Un talent show pieno di passione e allegria dove la creatività non manca di certo'),
('207683','Medieval Times','2024-12-21','Il mondo medievale come non avete mai visto prima, soldati, guerre e tanto altro da vedere'),
('212719','Speciale Natale','2024-12-25','Uno evento speciale per celebrare questa data'),
('239458','New Year has arrived','2024-12-31','Un nuovo inizio per tutti, celebriamo insieme questa data speciale'),
('243174','Through the jungle','2025-01-17','L avventura è iniziata, cosa si ansconderà mai dietro questa immensa giungla?'),
('243627','Science Time','2025-01-21','L ora della scienza è arrivata, iniziamo subito con li sperimenti'),
('253550','Pirates War','2025-02-24','finalmente il grande evento è arrivato combattiamo contro i pirati più cattivi di sempre');


CREATE TABLE biglietti(
    id char(5) PRIMARY KEY NOT NULL,
    titolo varchar(50) NOT NULL,
    costo numeric(6,2) NOT NULL
);

INSERT INTO biglietti(id,titolo,costo) VALUES
('1','1 Giorno, Biglietto per Bambini (sotto i 10 anni)',29.99),
('2','2 Giorni, Biglietto per Bambini (sotto i 10 anni)',49.99),
('3','3 Giorni, Biglietto per Bambini (sotto i 10 anni)',89.99),
('4','1 Giorno',39.99),
('5','2 Giorni',59.99),
('6','3 Giorni',99.99);


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
('2','user',1),
('3','user',2);

CREATE TABLE bigliettiAcquistati(
    biglietto char(5) NOT NULL,
    utente varchar(20) NOT NULL,
    dataAcquisto date NOT NULL,
    quantita smallint(2) NOT NULL,
    PRIMARY KEY (biglietto,utente,dataAcquisto),
    FOREIGN KEY (utente) REFERENCES users(username),
    FOREIGN KEY (biglietto) REFERENCES biglietti(id)
);

INSERT INTO bigliettiAcquistati(biglietto,utente,dataAcquisto,quantita) VALUES 
('1','user','2024-01-22',5),
('2','user','2024-01-21',4),
('3','user','2024-01-23',2);
