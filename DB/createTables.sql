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

CREATE TABLE TAreview(
    reviewid INTEGER PRIMARY KEY NOT NULL,
    taid INTEGER NOT NULL,
    studentid INTEGER NOT NULL,
    rating INTEGER NOT NULL,
    review TEXT,
    FOREIGN key (taid) references TA(taid),
    FOREIGN key (studentid) references Student(studentid)
);

CREATE TABLE Course(
    courseid INTEGER PRIMARY KEY NOT NULL,
    term_month_year TEXT NOT NULL,
    course_num TEXT NOT NULL, 
    course_name TEXT NOT NULL, 
    instructor_assigned_name TEXT NOT NULL
);

CREATE TABLE TakingCourse(
    studentid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    FOREIGN key (studentid) references Student(studentid),
    FOREIGN key (courseid) references Course(courseid),
    PRIMARY key (studentid,courseid)
);

CREATE TABLE AssistingCourse(
    taid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    FOREIGN key (taid) references TA(taid),
    FOREIGN key (courseid) references Course(courseid),
    PRIMARY key (taid,courseid)
);

CREATE TABLE TeachingCourse(
    proffesorid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    FOREIGN key (proffesorid) references Prof(studentid),
    FOREIGN key (courseid) references Course(courseid),
    PRIMARY key (proffesorid,courseid)
);




