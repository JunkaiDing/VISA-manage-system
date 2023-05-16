DROP TABLE Admissions;

DROP TABLE Visas;

DROP TABLE TouristApps;

DROP TABLE StudentApps;

DROP TABLE WorkerApps;

DROP TABLE Applications;

DROP TABLE FamilyMembers;

DROP TABLE Applicants;

DROP TABLE VisaOfficers;

DROP TABLE VisaTypes;

DROP TABLE BorderOfficers;

DROP TABLE AdmissionEntryPoints;

DROP TABLE Embassies;

CREATE TABLE Embassies (
    eID INTEGER,
    country CHAR(20) UNIQUE,
    PRIMARY KEY (eID)
);

CREATE TABLE Applicants (
    aplctID INTEGER GENERATED ALWAYS AS IDENTITY,
    passport CHAR(9) UNIQUE,
    name CHAR(20),
    gender CHAR(1),
    birthDate DATE,
    country CHAR(20),
    PRIMARY KEY (aplctID)
);

CREATE TABLE FamilyMembers (
    aplctID INTEGER,
    name CHAR(20),
    relationship CHAR(20),
    PRIMARY KEY (aplctID, name),
    FOREIGN KEY (aplctID) REFERENCES Applicants ON DELETE CASCADE
);

CREATE TABLE VisaTypes (
    type CHAR(2),
    length INTEGER,
    PRIMARY KEY (type)
);

CREATE TABLE VisaOfficers (
    voID INTEGER,
    eID INTEGER NOT NULL,
    name CHAR(20),
    experience INTEGER,
    PRIMARY KEY (voID),
    FOREIGN KEY (eID) REFERENCES Embassies
);

CREATE TABLE AdmissionEntryPoints (
    entryPoint CHAR(3),
    entryType CHAR(5),
    PRIMARY KEY (entryPoint)
);

CREATE TABLE BorderOfficers (
    boID INTEGER,
    name CHAR(20),
    PRIMARY KEY (boID)
);

CREATE TABLE Applications (
    appID INTEGER GENERATED ALWAYS AS IDENTITY,
    aplctID INTEGER NOT NULL,
    voID INTEGER,
    eID INTEGER,
    receiveDate DATE,
    status CHAR(10),
    PRIMARY KEY (appID),
    FOREIGN KEY (aplctID) REFERENCES Applicants,
    FOREIGN KEY (voID) REFERENCES VisaOfficers,
    FOREIGN KEY (eID) REFERENCES Embassies
);

CREATE TABLE TouristApps (
    appID INTEGER,
    destination CHAR(20),
    PRIMARY KEY (appID),
    FOREIGN KEY (appID) REFERENCES Applications ON DELETE CASCADE
);

CREATE TABLE StudentApps (
    appID INTEGER,
    school CHAR(20),
    PRIMARY KEY (appID),
    FOREIGN KEY (appID) REFERENCES Applications ON DELETE CASCADE
);

CREATE TABLE WorkerApps (
    appID INTEGER,
    company CHAR(20),
    PRIMARY KEY (appID),
    FOREIGN KEY (appID) REFERENCES Applications ON DELETE CASCADE
);

CREATE TABLE Visas (
    vID INTEGER,
    appID INTEGER UNIQUE NOT NULL,
    aplctID INTEGER NOT NULL,
    voID INTEGER NOT NULL,
    issueDate DATE,
    type CHAR(2),
    PRIMARY KEY (vID),
    FOREIGN KEY (appID) REFERENCES Applications ON DELETE CASCADE,
    FOREIGN KEY (aplctID) REFERENCES Applicants,
    FOREIGN KEY (voID) REFERENCES VisaOfficers,
    FOREIGN KEY (type) REFERENCES VisaTypes
);

CREATE TABLE Admissions (
    admID INTEGER GENERATED ALWAYS AS IDENTITY,
    aplctID INTEGER NOT NULL,
    boID INTEGER NOT NULL,
    grantDate DATE,
    entryPoint CHAR(3),
    PRIMARY KEY (admID),
    FOREIGN KEY (aplctID) REFERENCES Applicants,
    FOREIGN KEY (boID) REFERENCES BorderOfficers,
    FOREIGN KEY (entryPoint) REFERENCES AdmissionEntryPoints
);

INSERT INTO
    Embassies
VALUES
    (1, 'Spain');

INSERT INTO
    Embassies
VALUES
    (2, 'China');

INSERT INTO
    Embassies
VALUES
    (3, 'Australia');

INSERT INTO
    Embassies
VALUES
    (4, 'USA');

INSERT INTO
    Embassies
VALUES
    (5, 'Germany');

INSERT INTO
    Embassies
VALUES
    (6, 'UK');

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    ('P00000001', 'John', 'M', '01-FEB-2000', 'Spain');

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000002',
        'Xiaohong',
        'X',
        '02-APR-1997',
        'China'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000003',
        'Julia',
        'F',
        '16-DEC-1965',
        'Australia'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000004',
        'Benjamin',
        'M',
        '22-MAY-2010',
        'USA'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000005',
        'Ella',
        'F',
        '30-AUG-2002',
        'Germany'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000006',
        'Emma',
        'F',
        '19-OCT-2010',
        'Germany'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    (
        'P00000007',
        'Lihua',
        'M',
        '19-OCT-1993',
        'China'
    );

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    ('P00000008', 'Edward', 'X', '07-JUN-1988', 'UK');

INSERT INTO
    Applicants(passport, name, gender, birthDate, country)
VALUES
    ('P00000009', 'Iris', 'F', '25-DEC-2001', 'China');

INSERT INTO
    VisaTypes
VALUES
    ('S1', 2);

INSERT INTO
    VisaTypes
VALUES
    ('S2', 5);

INSERT INTO
    VisaTypes
VALUES
    ('T1', 1);

INSERT INTO
    VisaTypes
VALUES
    ('T2', 10);

INSERT INTO
    VisaTypes
VALUES
    ('W1', 3);

INSERT INTO
    FamilyMembers
VALUES
    (1, 'Joe', 'Father');

INSERT INTO
    FamilyMembers
VALUES
    (2, 'Xiaoli', 'Mother');

INSERT INTO
    FamilyMembers
VALUES
    (3, 'Peter', 'Spouse');

INSERT INTO
    FamilyMembers
VALUES
    (3, 'Andy', 'Son');

INSERT INTO
    FamilyMembers
VALUES
    (4, 'Daisy', 'Mother');

INSERT INTO
    VisaOfficers
VALUES
    (1, 1, 'Liam', 2);

INSERT INTO
    VisaOfficers
VALUES
    (2, 2, 'Noah', 10);

INSERT INTO
    VisaOfficers
VALUES
    (3, 3, 'William', 5);

INSERT INTO
    VisaOfficers
VALUES
    (4, 4, 'James', 7);

INSERT INTO
    VisaOfficers
VALUES
    (5, 5, 'Charles', 8);

INSERT INTO
    VisaOfficers
VALUES
    (6, 5, 'Samantha', 11);

INSERT INTO
    VisaOfficers
VALUES
    (7, 2, 'Rachel', 9);

INSERT INTO
    VisaOfficers
VALUES
    (8, 1, 'Joe', 3);

INSERT INTO
    AdmissionEntryPoints
VALUES
    ('YVR', 'Air');

INSERT INTO
    AdmissionEntryPoints
VALUES
    ('BLA', 'Land');

INSERT INTO
    AdmissionEntryPoints
VALUES
    ('PAC', 'Water');

INSERT INTO
    AdmissionEntryPoints
VALUES
    ('BCA', 'Air');

INSERT INTO
    AdmissionEntryPoints
VALUES
    ('AKG', 'Air');

INSERT INTO
    BorderOfficers
VALUES
    (1, 'John');

INSERT INTO
    BorderOfficers
VALUES
    (2, 'David');

INSERT INTO
    BorderOfficers
VALUES
    (3, 'Alfred');

INSERT INTO
    BorderOfficers
VALUES
    (4, 'Justin');

INSERT INTO
    BorderOfficers
VALUES
    (5, 'Amy');

INSERT INTO
    BorderOfficers
VALUES
    (6, 'Melissa');

INSERT INTO
    BorderOfficers
VALUES
    (7, 'Kevin');

INSERT INTO
    BorderOfficers
VALUES
    (8, 'Sam');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (1, 1, 1, '19-JUN-2017', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, 2, 2, '20-SEP-2018', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (3, 3, 3, '21-DEC-2015', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (4, 4, 4, '22-JUL-2018', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (5, 5, 5, '23-SEP-2020', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (1, null, 1, '07-OCT-2019', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, null, 2, '20-JAN-2013', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (3, null, 3, '11-NOV-2019', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (4, null, 4, '09-SEP-2018', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (5, null, 5, '19-JUL-2017', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (1, null, 1, '30-OCT-2020', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, null, 2, '01-SEP-2021', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (3, null, 3, '27-DEC-2016', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (4, null, 4, '18-SEP-2019', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (5, 5, 5, '23-SEP-2019', 'Received');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, 2, 2, '23-SEP-2019', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, 2, 2, '24-SEP-2019', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, 2, 2, '25-SEP-2019', 'Approved');

INSERT INTO
    Applications(aplctID, voID, eID, receiveDate, status)
VALUES
    (2, 2, 2, '26-SEP-2019', 'Approved');

INSERT INTO
    TouristApps
VALUES
    (1, 'Vancouver');

INSERT INTO
    TouristApps
VALUES
    (2, 'Toronto');

INSERT INTO
    TouristApps
VALUES
    (3, 'Calgary');

INSERT INTO
    TouristApps
VALUES
    (4, 'Kelowna');

INSERT INTO
    TouristApps
VALUES
    (5, 'Ottawa');

INSERT INTO
    StudentApps
VALUES
    (6, 'UBCV');

INSERT INTO
    StudentApps
VALUES
    (7, 'UTSG');

INSERT INTO
    StudentApps
VALUES
    (8, 'UAlberta');

INSERT INTO
    StudentApps
VALUES
    (9, 'UBCO');

INSERT INTO
    StudentApps
VALUES
    (10, 'UOttawa');

INSERT INTO
    WorkerApps
VALUES
    (11, 'Amazon');

INSERT INTO
    WorkerApps
VALUES
    (12, 'Meta');

INSERT INTO
    WorkerApps
VALUES
    (13, 'Microsoft');

INSERT INTO
    WorkerApps
VALUES
    (14, 'Apple');

INSERT INTO
    WorkerApps
VALUES
    (15, 'Walmart');

INSERT INTO
    StudentApps
VALUES
    (16, 'UBCO');

INSERT INTO
    StudentApps
VALUES
    (17, 'UBCV');

INSERT INTO
    TouristApps
VALUES
    (18, 'Vancouver');

INSERT INTO
    WorkerApps
VALUES
    (19, 'Walmart');

INSERT INTO
    Visas
VALUES
    (1, 1, 1, 1, '15-NOV-2020', 'T1');

INSERT INTO
    Visas
VALUES
    (2, 2, 2, 2, '03-DEC-2020', 'T1');

INSERT INTO
    Visas
VALUES
    (3, 3, 2, 3, '22-OCT-2022', 'T1');

INSERT INTO
    Visas
VALUES
    (4, 4, 3, 4, '31-OCT-2021', 'T1');

INSERT INTO
    Visas
VALUES
    (5, 5, 5, 5, '18-NOV-2020', 'T2');

INSERT INTO
    Visas
VALUES
    (6, 16, 2, 2, '06-NOV-2020', 'S1');

INSERT INTO
    Visas
VALUES
    (7, 17, 2, 2, '05-NOV-2020', 'S2');

INSERT INTO
    Visas
VALUES
    (8, 18, 2, 2, '14-JAN-2021', 'T2');

INSERT INTO
    Visas
VALUES
    (9, 19, 2, 2, '18-MAR-2022', 'W1');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (1, 1, '22-FEB-2022', 'YVR');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (2, 1, '21-SEP-2022', 'YVR');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (3, 2, '31-MAR-2022', 'BLA');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (4, 3, '04-JUL-2022', 'PAC');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (5, 4, '17-JAN-2022', 'AKG');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (2, 8, '17-JAN-2022', 'BLA');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (2, 7, '31-JAN-2022', 'AKG');

INSERT INTO
    Admissions(aplctID, boID, grantDate, entryPoint)
VALUES
    (2, 6, '17-NOV-2022', 'YVR');

COMMIT;