DROP DATABASE DB_IP;
CREATE DATABASE DB_IP;
USE DB_IP;

CREATE TABLE IP
    (
    ADR_IP VARCHAR(15),
    DATE_DERNIER_CTRL DATE,
    CPT_NOT_OK INTEGER,
    A_TESTER BOOLEAN,
    PRIMARY KEY (ADR_IP)
    );
INSERT INTO IP VALUES ('10.10.9.241',NULL,0,NULL);
INSERT INTO IP VALUES ('10.10.9.10',NULL,0,NULL);
INSERT INTO IP VALUES ('192.168.0.25',NULL,0,NULL);
INSERT INTO IP VALUES ('8.8.8.8',NULL,0,NULL);
INSERT INTO IP VALUES ('410.45.7.8',NULL,0,NULL);
