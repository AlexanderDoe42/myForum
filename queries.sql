CREATE TABLE Users (
UserID INT NOT NULL AUTO_INCREMENT,
Username varchar(20) NOT NULL UNIQUE,
Password varchar(20) NOT NULL,
PRIMARY KEY (UserID)
);

INSERT INTO Users (Username, Password)
VALUES ('adminasdfasdfasdf', 'admin');

SELECT Username, Password
FROM Users
WHERE Username = 'admin' AND
      Password = 'admin';

CREATE TABLE Posts (
PostID INT NOT NULL AUTO_INCREMENT,
AuthorID INT NOT NULL,
PostContent varchar(255) NOT NULL,
PRIMARY KEY (PostID)
);

CREATE TABLE Subjects (
SubjectID INT NOT NULL AUTO_INCREMENT,
AuthorID INT NOT NULL,
Created DATETIME(0),
Title varchar(255) NOT NULL,
NumberOfPosts INT DEFAULT 0,
PRIMARY KEY (SubjectID)
);

INSERT INTO Posts (AuthorID, PostContent)
VALUES ('16', 'NASA''s next Mars rover has a new name - PerseveranceThe name was announced Thursday by Thomas Zurbuchen, associate administrator of the Science Mission Directorate, during a celebration at Lake Braddock Secondary School in Burke, Virginia. Zurbuchen was at the school to congratulate seventh grader Alexander Mather, who submitted the winning entry to the agency''s "Name the Rover" essay contest, which received 28,000 entries from K-12 students from every U.S. state and territory.');

ALTER TABLE Posts MODIFY PostContent varchar(255) NOT NULL;

DELETE FROM Posts WHERE PostID = '8';

ALTER TABLE Users ADD NumberOfSubjects INT DEFAULT 0;
ALTER TABLE Subjects ADD Content varchar(255) NOT NULL;
ALTER TABLE Posts ADD SubjectID INT NOT NULL;
ALTER TABLE Posts ADD Created DATETIME(0);

ALTER TABLE tablename AUTO_INCREMENT = 1; // to reset AUTO_INCREMENT

UPDATE Users
SET NumberOfPosts = 42
WHERE UserID = '$usrID';

UPDATE Posts
SET Created = '2020-01-01 00:00:00';

ALTER TABLE Posts CONVERT TO CHARACTER SET utf8;
