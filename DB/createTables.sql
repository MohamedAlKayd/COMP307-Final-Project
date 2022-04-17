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

CREATE TABLE Course(
    courseid INTEGER PRIMARY KEY NOT NULL,
    term_year TEXT NOT NULL,
    course_num TEXT NOT NULL, 
    course_name TEXT NOT NULL, 
    instructor_assigned_name TEXT NOT NULL
);


CREATE TABLE TAreview(
    reviewid INTEGER PRIMARY KEY NOT NULL,
    taid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    rating INTEGER NOT NULL,
    review TEXT,
    FOREIGN key (taid) references TA(taid),
    FOREIGN key (courseid) references Course(courseid)
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

CREATE TABLE TAPerformanceLog(
    logid INTEGER PRIMARY KEY NOT NULL,
    taid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    profid INTEGER NOT NULL,
    comment TEXT,
    FOREIGN key (taid) references TA(taid),
    FOREIGN key (profid) references Prof(proffesorid),
    FOREIGN key (courseid) references Course(courseid)
);

CREATE TABLE TAWishlist(
    taid INTEGER NOT NULL,
    courseid INTEGER NOT NULL,
    profid INTEGER NOT NULL,
    FOREIGN key (taid) references TA(taid),
    FOREIGN key (profid) references Prof(proffesorid),
    FOREIGN key (courseid) references Course(courseid)
    PRIMARY key (taid,courseid,profid)
);

CREATE VIEW UserInfo(userid,firstname,lastname,username,password,usertype) AS
SELECT  s.userid, s.firstname, s.lastName, u.username, u.password, ('Student') as usertype
From Student s, User u
Where s.userid = u.userid
UNION
SELECT  ta.userid, ta.firstname, ta.lastName, u.username, u.password, ('TA') as usertype
From TA ta, User u
Where ta.userid = u.userid
UNION
SELECT  p.userid, p.firstname, p.lastName, u.username, u.password, ('Prof') as usertype
From Prof p, User u
Where p.userid = u.userid
UNION
SELECT  a.userid, a.firstname, a.lastName, u.username, u.password, ('Admin') as usertype
From Admin a, User u
Where a.userid = u.userid
UNION
SELECT  s.userid, s.firstname, s.lastName, u.username, u.password, ('Sysop') as usertype
From Sysop s, User u
Where s.userid = u.userid;





