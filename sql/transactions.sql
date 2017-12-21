DELIMITER //
CREATE PROCEDURE signup(IN username VARCHAR(45), IN upassword VARCHAR(255), IN uname VARCHAR(45))
BEGIN
	START TRANSACTION;
	INSERT INTO `User` (`username`, `upassword`, `uname`) VALUES(username,upassword,uname);
	COMMIT;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE get_profile(IN input VARCHAR(45))
BEGIN
	SELECT  username,uname,uemail,ustate,ucity FROM `User` WHERE username = input;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE update_profile(IN id VARCHAR(45), IN input1 VARCHAR(45), IN input2 VARCHAR(45), IN input3 VARCHAR(45),IN input4 VARCHAR(45))
BEGIN
	START TRANSACTION;
	UPDATE `User` 
    SET uname = input1, uemail=input2, ustate=input3, ucity=input4
    WHERE username = id;
	COMMIT;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE record_play(IN  trackid_ INT, IN username_ VARCHAR(45), IN ptime_ DATETIME, IN source_ VARCHAR(45), IN pdesc_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	INSERT INTO `Play` (`trackid`,`username`,`ptime`,`source`,`pdesc`) VALUES(trackid_, username_ ,ptime_, source_, pdesc_);
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE add_to_playlist(IN  trackid_ INT, IN  playlistid_ INT)
BEGIN
	START TRANSACTION;
	SET @nextorder = (SELECT MAX(porder) FROM Pcontain WHERE playlistid = playlistid)+1;
    INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES(playlistid_, trackid_, @nextorder);
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE add_rate(IN  trackid_ INT, IN username_ VARCHAR(45), IN rtime_ DATETIME, IN  score_ INT)
BEGIN
	START TRANSACTION;
	REPLACE INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES(trackid_, username_, rtime_,  score_);
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE add_follow(IN  followee_ VARCHAR(45), IN follower_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	REPLACE INTO  `Follow` (`followee`,`follower`) VALUES(followee_,follower_);
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE delete_follow(IN  followee_ VARCHAR(45), IN follower_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	DELETE FROM  Follow where followee =  followee_ and follower = follower_;
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE add_like(IN  username_ VARCHAR(45), IN artistid_ INT, IN ltime_ DATETIME)
BEGIN
	START TRANSACTION;
	REPLACE INTO  `Likes` (`username`,`artistid`,`ltime`) VALUES(username_,artistid_,ltime_);
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE delete_like(IN  username_ VARCHAR(45), IN artistid_ INT)
BEGIN
	START TRANSACTION;
	DELETE FROM  Likes where username =  username_ and artistid = artistid_;
	COMMIT;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE add_playlist(IN  playlisttitle_ VARCHAR(45), IN  playlisttime_ DATETIME, IN  ptype_ VARCHAR(45), IN  username_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	SET @nextorder = (select max(playlistid) from Playlist)+1;
    INSERT INTO `Playlist` (`playlistid`,`playlisttitle`,`playlisttime`,`ptype`,`owner`) VALUES(@nextorder,playlisttitle_,playlisttime_, ptype_, username_);
	COMMIT;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE delete_pcontain(IN  playlistid_ INT, IN trackid_ INT)
BEGIN
	START TRANSACTION;
	DELETE FROM  Pcontain where playlistid =  playlistid_ and trackid = trackid_;
	COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE delete_playlist(IN  playlistid_ INT)
BEGIN

	DECLARE exit handler for sqlexception
		BEGIN
		-- ERROR
		ROLLBACK;
		END;
    
	START TRANSACTION;
		DELETE FROM  Pcontain where playlistid =  playlistid_ ;
		DELETE FROM  Playlist where playlistid =  playlistid_ ;
	COMMIT;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE related_artist(IN  artistid_ INT)
BEGIN

	DECLARE exit handler for sqlexception
		BEGIN
		-- ERROR
		ROLLBACK;
		END;
    
	START TRANSACTION;

DROP TABLE temp;
CREATE  TABLE temp AS
(select username,artistid from Likes)
union
(select username,artistid
from Play natural join Track natural join Artist 
group by username,artistid
having count(*)  >= 10)
union
(select username,artistid
from Rate natural join Track natural join Artist 
group by username,artistid
having count(*)  >= 3 and avg(score)>=4);


select * from Artist natural join
(select  artist2 as artistid
from(select  distinct t1.username, t1.artistid as artist1,t2.artistid as artist2
		from temp as t1 , temp as t2 
		where t1.username = t2.username and t1.artistid != t2.artistid) as A
group by artist1, artist2
having count(*) >=2  and artist1 = artistid_) as B;


	COMMIT;
END//
DELIMITER ;




