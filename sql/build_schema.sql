CREATE SCHEMA `music` ;

CREATE TABLE `User` (
    `username` VARCHAR(45)  NOT NULL,
    `upassword` VARCHAR(255) NOT NULL,
    `uname` VARCHAR(45) NULL,
    `uemail` VARCHAR(45) NULL,
    `ustate` VARCHAR(45) NULL,
    `ucity` VARCHAR(45) NULL,
    PRIMARY KEY (`username`)
);

INSERT INTO `User` (`username`, `upassword`, `uname`, `uemail`,`ustate`,`ucity`) VALUES ('coolpot', 'password123', 'Yi Zhou', 'peterzhouyi@gmail.com','NY', 'Brooklyn');
INSERT INTO `User` (`username`, `upassword`, `uname`, `uemail`,`ustate`,`ucity`) VALUES ('kibo', 'passkibo', 'Zhangqian Liu', 'kibo@gmail.com','PA' ,'Pittsburgh');
INSERT INTO `User` (`username`, `upassword`, `uname`, `uemail`,`ustate`,`ucity`) VALUES ('bufan666', 'passbufan', 'Bufan Song','bufan@outlook.com','NY', 'Brooklyn');
INSERT INTO `User` (`username`, `upassword`, `uname`, `uemail`,`ustate`,`ucity`) VALUES ('NancyInQueens', 'passnancy', 'Nancy','nancy@outlook.com','NY', 'Queens');

CREATE TABLE `Follow` (
    `followee` VARCHAR(45) NOT NULL,
    `follower` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`followee`,`follower`),
    FOREIGN KEY (`followee`)
        REFERENCES `User` (`username`),
	FOREIGN KEY (`follower`)
        REFERENCES `User` (`username`)
);

INSERT INTO `Follow` (`followee`,`follower`) VALUES('coolpot','kibo');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('coolpot','bufan666');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('bufan666','kibo');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('kibo','NancyInQueens');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('coolpot','NancyInQueens');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('bufan666','coolpot');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('kibo','coolpot');
INSERT INTO `Follow` (`followee`,`follower`) VALUES('NancyInQueens','coolpot');

CREATE TABLE `Artist` (
     `artistid` INT NOT NULL,
    `aname` VARCHAR(45) NOT NULL,
    `adesc` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`artistid`)
);

INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('0','Taylor Swift','Pop');
INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('1','Katy Perry','world');
INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('2','Marroon5','rock band');
INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('3','Coldplay','rock band');
INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('4','Justin Bieber','Pop');
INSERT INTO `Artist` (`artistid`,`aname`,`adesc`) VALUES('5','Jason Mraz','Pop');

CREATE TABLE `Track` (
    `trackid` INT NOT NULL,
    `tracktitle` VARCHAR(45) NOT NULL,
    `duration` INT NOT NULL,
    `genre` VARCHAR(45) NOT NULL,
    `artistid` INT NOT NULL,
    PRIMARY KEY (`trackid`),
    FOREIGN KEY (`artistid`)
        REFERENCES `Artist` (`artistid`)
);

INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('0','We Are Never Ever Getting Back Together','191', 'pop','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('1','Firework','225', 'pop','1');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('2','Maps','189', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('3','Animals','231', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('4','It Was Always You','239', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('5','Unkiss Me','238', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('6','Sugar','235', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('7','Leaving California','203', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('8','In Your Pocket','219', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('9','New Love','196', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('10','Coming Back For You','226', 'funk rock','2');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('11','Feelings','194', 'funk rock','2');

INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('12','Yellow','269', 'love','3');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('13','Help Is Round The Corner','156', 'love','3');

INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('14','Baby','316', 'love','4');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('15','Sorry','200', 'love','4');

INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('16','Fearless','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('17','Fifteen','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('18','Love Story','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('19','Hey Stephen','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('20','White Horse','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('21','You Belong With Me','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('22','Breath','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('23','Tell Me Why','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('24','You Are Not Sorry','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('25','The Way I loved You','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('26','Forever And Always ','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('27','The Best Day','200', 'love','0');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('28','Change','200', 'love','0');

INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('29','Make It Mine','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('30','I m Yours','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('31','Lucky','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('32','Butterfly','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('33','Live High','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('34','Love For A Child','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('35','Details in the Fabric  ','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('36','Coyotes','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('37','Only Human','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('38','The Dynamo Of Volition','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('39','If It Kills Me','200', 'Pop','5');
INSERT INTO `Track` (`trackid`,`tracktitle`,`duration`,`genre`,`artistid`) VALUES('40','A Beautiful Mess','200', 'Pop','5');


CREATE TABLE `Play` (
    `trackid` INT NOT NULL,
    `username` VARCHAR(45) NOT NULL,
    `ptime` DATETIME NOT NULL,
    `source` VARCHAR(45) NOT NULL,
    `pdesc` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`trackid`,`username`,`ptime`),
    FOREIGN KEY (`trackid`)
        REFERENCES `Track` (`trackid`),
    FOREIGN KEY (`username`)
        REFERENCES `User` (`username`)
);


CREATE TABLE `Rate` (
    `trackid` INT NOT NULL,
    `username` VARCHAR(45) NOT NULL,
    `rtime` DATETIME NOT NULL,
    `score` INT NOT NULL,
    PRIMARY KEY (`trackid`,`username`),
    FOREIGN KEY (`trackid`)
        REFERENCES `Track` (`trackid`),
    FOREIGN KEY (`username`)
        REFERENCES `User` (`username`)
);

DELIMITER $$
CREATE TRIGGER BAD_SCORE_INSERT BEFORE INSERT ON Rate
FOR EACH ROW
BEGIN
IF(NEW.score <= 0  or NEW.score >=6) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Detected score out of range, should be 1,2,3,4,5';
END IF;
END$$
DELIMITER ;


INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('0','coolpot','2016-1-9 14:55:00', '5');
INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('1','coolpot','2016-1-10 14:55:00', '4');
INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('6','kibo','2016-1-9 14:55:00', '5');
INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('3','kibo','2016-1-10 14:55:00', '4');
INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('3','bufan666','2016-1-9 14:55:00', '1');
INSERT INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES('10','bufan666','2016-1-10 14:55:00', '2');


CREATE TABLE `Playlist` (
    `playlistid` INT NOT NULL,
    `playlisttitle` VARCHAR(45) NOT NULL,
    `playlisttime` DATETIME NOT NULL,
    `ptype` VARCHAR(45) NOT NULL,
    `owner` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`playlistid`),
    FOREIGN KEY (`owner`)
        REFERENCES `User` (`username`)
);

INSERT INTO `Playlist` (`playlistid`,`playlisttitle`,`playlisttime`,`ptype`,`owner`) VALUES('0','coolpot favorite','2016-1-2 14:55:00', 'private','coolpot');
INSERT INTO `Playlist` (`playlistid`,`playlisttitle`,`playlisttime`,`ptype`,`owner`) VALUES('1','coolpot reading time BGM','2016-1-3 14:55:00', 'public','coolpot');


CREATE TABLE `Pcontain` (
    `playlistid` INT NOT NULL,
    `trackid` INT NOT NULL,
    `porder` INT NOT NULL,
    PRIMARY KEY (`playlistid`,`trackid`),
	FOREIGN KEY (`trackid`)
        REFERENCES `Track` (`trackid`),
	FOREIGN KEY (`playlistid`)
        REFERENCES `Playlist` (`playlistid`)
);

INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES('0','0','1');
INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES('0','10','2');
INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES('1','2','1');
INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES('1','7','2');

CREATE TABLE `Likes` (
    `username` VARCHAR(45) NOT NULL,
    `artistid`  INT NOT NULL,
    `ltime` DATETIME NOT NULL,
    PRIMARY KEY (`artistid`,`username`),
    FOREIGN KEY (`artistid`)
        REFERENCES `Artist` (`artistid`),
    FOREIGN KEY (`username`)
        REFERENCES `User` (`username`)
);

INSERT INTO `Likes` (`username`,`artistid`,`ltime`) VALUES('coolpot','0','2016-1-10 14:55:00');
INSERT INTO `Likes` (`username`,`artistid`,`ltime`) VALUES('coolpot','1','2016-2-10 14:55:00');
INSERT INTO `Likes` (`username`,`artistid`,`ltime`) VALUES('bufan666','0','2016-1-10 14:55:00');
INSERT INTO `Likes` (`username`,`artistid`,`ltime`) VALUES('kibo','1','2016-2-10 14:55:00');

CREATE TABLE `Album` (
    `albumid` INT NOT NULL,
    `albumtitle` VARCHAR(45) NOT NULL,
    `albumtime` DATETIME NOT NULL,
    PRIMARY KEY (`albumid`)
);


INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('0','We Are Never Ever Getting Back Together','2012-8-13 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('1','Firework','2010-11-24 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('2','V(Deluxe)','2015-5-14 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('3','Yellow','2000-6-25 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('4','Baby','2010-3-6 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('5','Sorry','2015-10-22 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('6','Fearless','2009-3-7 12:00:00');
INSERT INTO `Album` (`albumid`,`albumtitle`,`albumtime`) VALUES('7','We Sing We Dance We Steal Things','2012-1-19 12:00:00');

CREATE TABLE `Acontain` (
    `albumid` INT NOT NULL,
    `trackid` INT NOT NULL,
    `aorder` INT NOT NULL,
    PRIMARY KEY (`albumid`,`trackid`)
);

-- We Are Never Ever Getting Back Together
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('0','0','1');
-- Firework
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('1','1','1');
-- V
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','2','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','3','2');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','4','3');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','5','4');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','6','5');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','7','6');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','8','7');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','9','8');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','10','9');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('2','11','10');
-- Yellow
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('3','12','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('3','13','2');
-- Baby
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('4','14','1');
-- Sorry
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('5','15','1');
-- Fearless
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','16','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','17','2');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','18','3');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','19','4');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','20','5');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','21','6');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','22','7');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','23','8');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','24','9');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','25','10');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','26','11');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','27','12');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('6','28','13');

-- We Sing. We Dance. We Steal Things
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','29','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','30','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','31','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','32','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','33','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','34','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','35','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','36','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','37','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','38','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','39','1');
INSERT INTO `Acontain` (`albumid`,`trackid`,`aorder`) VALUES('7','40','1');