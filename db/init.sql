USE test_db;

CREATE TABLE users(
    email VARCHAR(32) PRIMARY KEY NOT NULL,
    password VARCHAR(64) NOT NULL
);  

INSERT INTO users(email,password) VALUES ('a@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb'),
                                            ('val@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb'),
                                            ('pirulin@gmail.com','ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb');