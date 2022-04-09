CREATE TABLE User (
    userid INTEGER PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    password TEXT UNIQUE NOT NULL,
    ticket INTEGER,
    ticketexpires TEXT
);

CREATE TABLE Student(
    studentid INTEGER PRIMARY KEY NOT NULL,
    userid INTEGER NOT NULL,
    firstname TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email TEXT NOT NULL,
    Foreign key (userid) references USER(userid)
);

CREATE TABLE TA(
    taid INTEGER PRIMARY KEY NOT NULL,
    userid INTEGER NOT NULL,
    firstname TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email TEXT NOT NULL,
    Foreign key (userid) references USER(userid)
);

CREATE TABLE Prof(
    proffesorid INTEGER PRIMARY KEY NOT NULL,
    userid INTEGER NOT NULL,
    firstname TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email TEXT NOT NULL,
    Foreign key (userid) references USER(userid)
);

CREATE TABLE Admin(
    adminid INTEGER PRIMARY KEY NOT NULL,
    userid INTEGER NOT NULL,
    firstname TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email TEXT NOT NULL,
    Foreign key (userid) references USER(userid)
);

CREATE TABLE Sysop(
    sysopid INTEGER PRIMARY KEY NOT NULL,
    userid INTEGER NOT NULL,
    firstname TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email TEXT NOT NULL,
    Foreign key (userid) references USER(userid)
);



