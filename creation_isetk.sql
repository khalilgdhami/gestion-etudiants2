USE ISETK;

DROP DATABASE Persons;
CREATE TABLE Persons (
    Nom VARCHAR(255),
    Prenom VARCHAR(255),
    Age INT,
    Email VARCHAR(255)
);
INSERT INTO Persons (Nom, Prenom, Age, Email)
VALUES 
    ('ahmed', 'ben ali', 21, 'ahmed@gmail.com'),
    ('ali', 'malki', 19, 'ali@gmail.com'),
    ('sami', 'gmati', 20, 'sami@gmail.com'),
    ('mohamed', 'ben ali', 12, 'mohamed@gmail.com');

