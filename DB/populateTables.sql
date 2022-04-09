INSERT INTO User(userid, username, password) VALUES
    (1, 'bob', 'abc123'),
    (2, 'linda', '99tt55'),
    (3, 'jeremy', '1234'),
    (4, 'anthony', '909'),
    (5, 'linda_adm', 'admin44');
	

INSERT INTO Student(studentid, userid, firstname, lastName, email) VALUES
    (222666000, 1, 'Bob', 'Jonson', 'bob@mail.com');

INSERT  INTO TA(taid, userid, firstname, lastName, email) VALUES
    (111567887, 2, 'Lida', 'Thre', 'linda@gmail.com');

INSERT INTO Prof(proffesorid, userid, firstname, lastName, email) VALUES
    (1166, 3, 'Jeremy', 'Brown', 'JB.mail.mcgill.ca');

INSERT  INTO Admin(adminid, userid, firstname, lastName, email) VALUES
    (111567887, 5, 'Lida', 'Thre', 'linda@gmail.com');

INSERT INTO Sysop(sysopid, userid, firstname, lastName, email) VALUES
    (777, 4, 'Anthony', 'Bouchard', 'anthony_b.hotmail.com');
