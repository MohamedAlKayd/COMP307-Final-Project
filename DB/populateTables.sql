INSERT INTO User(userid, username, password) VALUES
    (1, 'bob', 'abc123'),
    (2, 'linda', '99tt55'),
    (3, 'jeremy', '1234'),
    (4, 'anthony', '909'),
    (5, 'linda_adm', 'admin44'),
    (6, 'jvythal', 'rano343'),
    (7, 'joD', '964er'),
    (8, 'Jess', 'Lapmt102'),
    (9, 'BOBBYta', 'JO877');
	

INSERT INTO Student(studentid, userid, firstname, lastName, email) VALUES
    (222666000, 1, 'Bob', 'Jonson', 'bob@mail.com'),
    (270856020, 8, 'Jessica', 'Poirier', 'Jess_P@gmail.com');

INSERT  INTO TA(taid, userid, firstname, lastName, email) VALUES
    (111567887, 2, 'Lida', 'Thre', 'linda@gmail.com'),
    (222666000, 9, 'Bob', 'Jonson', 'bob@mail.com');

INSERT INTO Prof(proffesorid, userid, firstname, lastName, email) VALUES
    (1166, 3, 'Jeremy', 'Brown', 'JB.mail.mcgill.ca'),
    (1167, 6, 'Joseph', 'Vybihal', 'jvythal.mail.mcgill.ca'),
    (1168, 7, 'Joseph', 'Dsilva', 'josephD.mail.mcgill.ca');

INSERT  INTO Admin(adminid, userid, firstname, lastName, email) VALUES
    (111567887, 5, 'Lida', 'Thre', 'linda@gmail.com');

INSERT INTO Sysop(sysopid, userid, firstname, lastName, email) VALUES
    (777, 4, 'Anthony', 'Bouchard', 'anthony_b.hotmail.com');

INSERT INTO TAreview(reviewid, taid, courseid, rating, review) VALUES 
    (1, 111567887, 1, 5, 'Very helpful with homework.');

INSERT INTO Course(courseid, term_year, course_num, course_name, instructor_assigned_name) VALUES
    (1, 'winter_2021', 'COMP 307', 'Web Dev', 'Joseph Vybihal'),
    (2, 'fall_2022', 'COMP 424', 'AI', 'Jeremy Brown'),
    (3, 'fall_2021', 'COMP 421', 'AI', 'Joseph Dsilva'),
    (4, 'winter_2022', 'COMP 307', 'Web Dev', 'Joseph Vybihal');

INSERT INTO TakingCourse (studentid,courseid) VALUES
    (222666000, 1),
    (222666000, 3),
    (270856020, 2),
    (270856020, 4);

INSERT INTO AssistingCourse (taid,courseid) VALUES
    (111567887, 1),
    (111567887, 2),
    (111567887, 3),
    (222666000, 4);

INSERT INTO TeachingCourse (proffesorid,courseid) VALUES
    (1167, 1),
    (1166, 2),
    (1168, 3),
    (1167, 4);




