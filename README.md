# MDL-Muisic-Website: A Music Website

It's the final project for database course in my 2017 fall semester in NYU.

A functional music-website, based on MySql and PHP. Using Material Design Lite for UI.

Multiple users can signed up, signed in, search, follow other users, update profiles, manage playlists,etc .. and of course enjoy music here.

Support Concurency Control, Security and Recommendation.

## Content List

1. Project-File-Structure

2. Database
 - 2.1 Database Design
 - 2.2 Corresponding relational tables, and constraints
3. Web-Based User Interface
 - 3.1 Details of Each Page
 - 3.2 Feedbacks of the site
 - 3.3 Privacy
 - 3.4 Security
 - 3.5 Concurrency
 - 3.6 Extra Features


## Project-File-Structure

1. PHP+HTML+CSS
	- 1.1 assets: some pics used in the project
	- 1.2 follow-btn.css
	- 1.3 like-btn.css
	- 1.4 mystyle.css
	- 1.5 album.php : the album page
	- 1.6 artist.php : the artist page
	- 1.7 console.php : some helper function
	- 1.8 index.html : login page
	- 1.9 manage\_playlist.php : the page that can modify user's playlist
	- 1.10 the page that displays the user's rate,follow and like
	- 1.11 play.php : the music player page
	- 1.12 profile.php : the page that display user's profile 
	- 1.13 search.php : the search page
	- 1.14 signup.php: the signup page
	- 1.15 signin.php : the signin page
	- 1.16 data_manager.php : the php file that deal all the requests from user side.
2. sql
	- 2.1 build-schema.sql 
	- 2.2 transactions.sql : transactions are define here
3. screenshots 

Note that, there should also be track folder in order to make this site work(I download 40 tracks in my machine, they are too big to be uploaded).  Also, the insert operations of User table in the `build-schema.sql` is not the right way, because actually we only store encrypted password.


## Database

### 1.Database Design

**ER Diagram as follows:**

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/DB-PJ-ERDiagram.jpg?raw=true)

Most of the attributes are obvious in this ER Diagram. 

And I want to note the following note the following assumptions and designing choices when design this ER diagram:

* I distinguish `Album` with `Playlist` -- "user-generated album" by creating two types of entities , although they have similar attrinbutes. I would like to treat them differently,
* Every time a track is played, we record this event's time, source and description of this event in `Play`. The source and the pdesc combinations can be 

| source| pdesc| 
|-----|-----|
|album| albumid|
|playlist|playlistid|
|search|keyword| 
|other| some descriptions|

The source and desc record that how users get accessed to a particular track. User can get accessed to a track by listening in a playlist, listening in an album or finding it in search results.

* `Pcontain` and `Acontain` stores the relationship between playlist/album and track.  The attribute aorder in `Acontain` for a specific album should be continuous integer. For example, if an album "Thriller" contains 9 tracks, then there will be nine aorders for this album, 1,2,3 .. 9, in our system. The attribute porder in `Pcontain` for a specific album should be ascending, but not necessarily continuous integers, since user can modify their playlists.  For example, if a playlist "MyFavourite" contains 9 tracks, then there will be nine porders for this playlist, 1,2,3 .. 9, in our system at first. After the owner of the playlist delete the track of porder 2 and then add a new track into this playlist, then there will be nine porders for this playlist, 1,3,4 .. 9, 10 in our system.

* ptype could be 'private' or 'public', which indicates if the playlist can be accessed by other users.

* `Play` is a weak entity, and the ptime is its partial key.

* I assume users can re-rate tracks, or cancel their "like"s. There will be atmost one rate for each (user,track) combination. And our system will only keep the most recent rate. In the same way, our system will only keep the most recent "like" state. 

* The duration of a track would be stored as the count of seconds.


* The rating score of a track  by a user should be in {1,2,3,4,5}.

### 2.Corresponding relational tables, and constraints

*Relational Tables:*

| Tables|
|-------|
|`User`(**username**,upassword,uname,uemail,ustate,ucity)|
|`Follow`(**followee**,**follower**)|
|`Track`(**trackid**,tracktitle,duration,genre, artistid)|
|`Play`(**trackid**,**username**,**ptime**,source,pdesc)|
|`Rate`(**trackid**,**username**,rtime,score)|
|`Playlist`(**playlistid**,playlisttitle,playlisttime,ptype, owner)|
|`Pcontain`(**playlistid**,**trackid**,porder)|
|`Artist`(**artistid**,aname,adesc)|
|`Likes`(**username**,**artist**,ltime)|
|`Album`(**albumid**,albumtitle,albumtime)|
|`Acontain`(**albumid**,**trackid**,aorder)|



*Foreign key constraints:*


* FOREIGN KEY `Follow` (followee) REFERENCES `User` (username)

* FOREIGN KEY `Follow` (follower) REFERENCES `User` (username)

* FOREIGN KEY `Track` (artistid) REFERENCES `Artist` (artistid)

* FOREIGN KEY `Play` (username) REFERENCES `User` (username)

* FOREIGN KEY `Play` (trackid) REFERENCES `Track` (trackid)

* FOREIGN KEY `Rate` (username) REFERENCES `User` (username)

* FOREIGN KEY `Rate` (trackid) REFERENCES `Track` (trackid)

* FOREIGN KEY `Playlist` (owner) REFERENCES `User` (username)

* FOREIGN KEY `Pcontain` (playlistid) REFERENCES `Playlist` (playlistid)

* FOREIGN KEY `Pcontain` (trackid) REFERENCES `Track` (trackid)

* FOREIGN KEY `Likes` (username) REFERENCES `User` (username)

* FOREIGN KEY `Likes` (artistid) REFERENCES `Artist` (artistid)

* FOREIGN KEY `Acontain` (albumid) REFERENCES `Album` (albumid)

* FOREIGN KEY `Acontain` (trackid) REFERENCES `Track` (trackid)

*Other constraints:*

* Make sure the the score is in {1,2,3,4,5}. A trigger should be created to check this. I created the following trigger in MySQL;

~~~sql
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
~~~

* NOT NULL constraints

Almost all of the attributes are required to be **NOT NULL**. The only exceptions are uname,uemail ustate and ucity in `User`. They can be accepted to be NULL, but users should be recommended to complete this information after signing up.



## Web-Based User Interface

### Signup.php and Signin.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/signup&signin.png?raw=true)

At sign up and signin page, you will see a sign up card and a sign in card.

In sign up phase, you need to give your username and password as well as your realname.

User Inputs will be examined to be not too short and not empty.

* Require userpassword at least 4 chars.This is done by front-end(others are similar):

~~~html
<input class="mdl-textfield__input" type="text" id="sample1" name="upusername" minlength="4" >
~~~

* Prevent empty inputs. This is done by backend PHP

~~~php
$empty = ($upusername=="") || ($upupassword=="") || ($upuname=="");
if($empty == True){
	#generate some pages tells you that your inputs are illegal.
}
~~~

* Password will be encrypted and only encrypted password will be stored in our database system.

~~~php
//in signup.php
$upupassword_hashed = password_hash($upupassword, PASSWORD_DEFAULT);
$signupsql = "CALL signup('" . $upusername . "','". $upupassword_hashed . "','" . $upuname . "');";


//in signin.php
$sign_in_result = password_verify( $inupassword , $hash );
~~~

* Signup page will Call a procedure in database and insert a user tuple in database

~~~sql
DELIMITER //
CREATE PROCEDURE signup(IN username VARCHAR(45), IN upassword VARCHAR(255), IN uname VARCHAR(45))
BEGIN
	START TRANSACTION;
	INSERT INTO `User` (`username`, `upassword`, `uname`) VALUES(username,upassword,uname);
	COMMIT;
END//
DELIMITER ;
~~~

* If Signed successfully, user will be redirect to the feed page.

~~~php
#After successfully checking in...
redirect("feed.php");
~~~


### Feed.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/feed.png?raw=true)
signup&signin.png
In this page, you will see the albums,playlists,tracks,artists that sre recommended by our system.

The recommendation are from two source.

* Personaized Recommendation.

In this section, our system will pick out things that the user might like but may be haven't noticed.

For example, a playlist that contains a 5-star rate track he just rated.

~~~sql
-- Tracks by artists the user recently like
select trackid,tracktitle from Likes natural join Artist  natural join Track  
where username = 'coolpot' order by ltime desc limit 5;

-- Albun that contains a recent high score of the user
select distinct albumid,albumtitle from 
(select albumid,rtime from Acontain natural join Rate  where username = "coolpot" and score = 5 
order by rtime desc) as A
natural join Album
limit 5;

-- Playlist that contains a recent high score of the user
select distinct playlistid,playlisttitle from 
(select playlistid,rtime from Pcontain natural join Rate  
where username = "coolpot"  and score = 5 order by rtime desc) as A
natural join Playlist
where owner != "coolpot"
and ptype = "public"
limit 5;

-- Artist the user listen a lot recently but forget to like it!
select artistid,aname,count(*) as val from Play natural join Track natural join Artist 
where username = "coolpot" and artistid not in 
(select artistid from Likes where username = "coolpot")
group by artistid,aname order by val
limit 5;
~~~

* General Recommendation 

In this section, our system will recommend the popular tracks(played a lot by other users this month) and arising artists(liked a lot by other users this month).

I think it's especially important for the new users, even they don't have any data, they will be recomended something.

~~~sql
-- Recent popular songs
select trackid,tracktitle,count(*) as val from play natural join Track 
where datediff(curdate(),ptime)<30 group by trackid,tracktitle order by val desc limit 3;

-- Recent arising artist
select artistid,aname,count(*) as val  from Likes natural join Artist 
where datediff(curdate(),ltime)<30 group by artistid,aname order by val desc limit 3;
~~~


* Shuffle and Output

The Personal Recommendation and General Recommendation phases will generate a lot candidates to feed the user.

I randomly select 10(atmost) tuples to put into the feed page. 

In this way, every time you visit the feed page,  it will be different.

~~~php
# ...select candidates into $cand
shuffle($cand);
$cand = array_slice($cand,0,10);
#... then generate the feed page
~~~

### profile.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/profile.png?raw=true)

In this page, the backend first select your profile infomation from dataset and use them as default value for the text input fileds.

Then, if you put some data in the text fields and click the update. I will send a XMLrequest to our server's "data_manager.php". After it got a response from "data_manager.php", it will reload this page.

~~~js
function submitProfileForm(form)
{ 
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  http.open("POST", url, true);
  
  //...
  //Put info into paramas
  //...
  
  http.send(params); 
}
~~~

data_manager.php deal with the request.

~~~php
$q = $_POST["q"];
if($q == "UPDATEPROFILE"){
		//...	    
	    //...connected to my sql
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		//...
		//...do some check
		$updatefile_sql = "CALL update_profile('" . $secure_username . "','". $secure_uname . "','" . $secure_email . "','". $secure_ustate. "','". $secure_ucity . "');";
		$result = $conn->query($updatefile_sql);
		$conn->close();
		if ($result != True){
			echo "=>UPDATEPROFILE,Faild";
		}else{
			echo "=>UPDATEPROFILE,Success";
		}
}
~~~

Then, it call a procedure in mysql to update the user profile information.

~~~sql
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
~~~

### musictaste.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/musictaste.png?raw=true)
In this page, user can get ti know their liked artist, rated songs and followees.

To the implementing persepective, it's simple.

Select the related infomation according to the Session's username. And output the information into the page.

### search.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/search.png?raw=true)


Too make the search experience smooth. This page is all implemented using AJAX techniques.

After user submit some keyword, the user-side will send a XMLHttpRequest, then after get a response, it changes this pages inner HTML to display the results.

~~~js
function submitSearchForm(form)
{ 
  var http = new XMLHttpRequest();
  //...
  //...send a request 
    http.onreadystatechange = function() {
    //Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
    	var responseJSON = JSON.parse(http.responseText);
    	//...
    	//...decode the response and put it into "s_results_all"
    	//...
    	
   //Change the page's innerHTML
   	document.getElementById("s_results_all").innerHTML = s_results_all_html;
    }
  }

  http.send(params); 
  return false;
}
</script>
~~~

The search phase will try to match these attribbutes.

1. Artist.aname
2. Artist.adesc
3. Track.tracktitle
4. Track.genre
5. Album.albumtitle
6. User.username
7. (Public) Playlist.playlisttitle

This search phase is implemented in `data_manager.php`.

Example for searching the artist part.

~~~php
$search_artist_sql = "select * from Artist where aname like '%" . $keyword . "%'" .
						 " or adesc like '%" . $keyword . "%'";
$result = $conn->query($search_artist_sql);
$s_artists = array();
if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_artists[] = json_encode($row);
    	}
    }
~~~

### manage_playlist.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/manage_playlist.png?raw=true)


In this page, user is able to add new playlist, delete track from his playlist or delete a playlist.

This page is similar to the update profile page. This three actions are all done by sending an XMLhttprequest to the data_manager.php. After get a response, it will reload this page so that user can see the changes he just made.

To be noted, to delete a playlist, related pcontain info should also be deleted. So I use a transaction with sqlexception handler to assure that it's atomotic procedure. If something goes wrong, it will rollback.

~~~sql
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
~~~

Also, to add a newplaylist, we need to search the next available playlistid, and then assign it to the new playlist.

~~~sql
DELIMITER //
CREATE PROCEDURE add_playlist(IN  playlisttitle_ VARCHAR(45), IN  playlisttime_ DATETIME, IN  ptype_ VARCHAR(45), IN  username_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	SET @nextorder = (select max(playlistid) from Playlist)+1;
    INSERT INTO `Playlist` (`playlistid`,`playlisttitle`,`playlisttime`,`ptype`,`owner`) VALUES(@nextorder,playlisttitle_,playlisttime_, ptype_, username_);
	COMMIT;
END//
DELIMITER ;
~~~

### Browse the site: play.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/play.png?raw=true)

Every track title dsiplayed in any page of this site has a link to "play.php?trackid=...&username=...&token=...", where you can enjoy the music, give a rate(or re-rate) and add it to your playlist.

Every time you click the link, a play record will be recorded in the database system.

* Record the play

When you click the name of the track and enter the play page, a play record will be inserted into the database.

~~~sql
DELIMITER //
CREATE PROCEDURE record_play(IN  trackid_ INT, IN username_ VARCHAR(45), IN ptime_ DATETIME, IN source_ VARCHAR(45), IN pdesc_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	INSERT INTO `Play` (`trackid`,`username`,`ptime`,`source`,`pdesc`) VALUES(trackid_, username_ ,ptime_, source_, pdesc_);
	COMMIT;
END//
DELIMITER ;
~~~

* Rate a song

When you click the a start icon, a rate will be recorded by sending a 'ADDRATE' XMLHttpRequest to `data_manager.php`.

If there's already a rate of this user and this track, the rate will be updated by using "REPLACE TO" in mysql.

~~~js
function submitAddPlaylist(playlistid,trackid,username,token){
	var http = new XMLHttpRequest();
  	var url = "data_manager.php";
  	var params = "q=ADDTOPLAYLIST" + "&playlistid="+ playlistid +
  				"&trackid=" + trackid +"&username=" + username + "&token=" + token;
  	console.log(params);
  	http.open("POST", url, true);
  	//Send the proper header information along with the request
  	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  	http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
    		alert(http.responseText);
    	}
	}
	http.send(params); 
}
~~~


`data_manager.php` will trigger mysql to do a add_rate procedure.

~~~sql
DELIMITER //
CREATE PROCEDURE add_rate(IN  trackid_ INT, IN username_ VARCHAR(45), IN rtime_ DATETIME, IN  score_ INT)
BEGIN
	START TRANSACTION;
	REPLACE INTO `Rate` (`trackid`,`username`,`rtime`,`score`) VALUES(trackid_, username_, rtime_,  score_);
	COMMIT;
END//
DELIMITER ;
~~~

* Add this track to users' playlist

When you click the "+" icon, you will send a  XMLHttpRequest to `data_manager.php`

If the track is already in the playlist, return "Already in Playlist" Alert to userside, else return a "Success" Alert.

`data_manager.php` send feedback to userside.

~~~php
$result = $conn->query($add_to_playlist_sql);
$conn->close();
if ($result != True){
	echo "Already in your playlist.";
	}else{
	echo "Success";
	}
}
~~~

In the sql part, first select the next available order, and insert into the `Pcontain` table.

~~~sql
DELIMITER //
CREATE PROCEDURE add_to_playlist(IN  trackid_ INT, IN  playlistid_ INT)
BEGIN
	START TRANSACTION;
	SET @nextorder = (SELECT MAX(porder) FROM Pcontain WHERE playlistid = playlistid)+1;
    INSERT INTO `Pcontain` (`playlistid`,`trackid`,`porder`) VALUES(playlistid_, trackid_, @nextorder);
	COMMIT;
END//
DELIMITER ;
~~~


### Browse the site: user.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/user.png?raw=true)

In the user page, you can see the music tastes of other users and follow/unfollow the user.

* Follow a user

If the checkbox is not checked, then by clicked the "+Follow" button, you will send a XMLHttpRequest to insert a follow tuple into database.

As before, "REPLACE INTO" is used in order to keep the latest result.

~~~sql
DELIMITER //
CREATE PROCEDURE add_follow(IN  followee_ VARCHAR(45), IN follower_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	REPLACE INTO  `Follow` (`followee`,`follower`) VALUES(followee_,follower_);
	COMMIT;
END//
DELIMITER ;
~~~


* Unfollow a user

If the checkbox is not checked, then by clicked the "Following" button, you will send a XMLHttpRequest to delete a follow tuple from database.

~~~sql
DELIMITER //
CREATE PROCEDURE delete_follow(IN  followee_ VARCHAR(45), IN follower_ VARCHAR(45))
BEGIN
	START TRANSACTION;
	DELETE FROM  Follow where followee =  followee_ and follower = follower_;
	COMMIT;
END//
DELIMITER ;
~~~



### Browse the site: artist.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/artist.png?raw=true)

From implementing perspective, it's almost same to the user page.


One thing is to be noted that, similar artist will be displayed in this place.

If two artist have many common fans, then they should be considered as similar artists.

If a user lieten a lot the artist's song or liked the artist, or give good rate to the artist's song. the user should be considered as a fan.

* select similar artist

~~~sql
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

~~~



* add a like to artist

~~~sql
DELIMITER //
CREATE PROCEDURE add_like(IN  username_ VARCHAR(45), IN artistid_ INT, IN ltime_ DATETIME)
BEGIN
	START TRANSACTION;
	REPLACE INTO  `Likes` (`username`,`artistid`,`ltime`) VALUES(username_,artistid_,ltime_);
	COMMIT;
END//
DELIMITER ;
~~~


* delete a like to artist


~~~sql
DELIMITER //
CREATE PROCEDURE delete_like(IN  username_ VARCHAR(45), IN artistid_ INT)
BEGIN
	START TRANSACTION;
	DELETE FROM  Likes where username =  username_ and artistid = artistid_;
	COMMIT;
END//
DELIMITER ;
~~~


### Browse the site: album.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/album.png?raw=true)

From implementing perspective, it's trivial. Just select the infomation according to albumid.

### Browse the site: playlist.php

![](https://github.com/cool-pot/MDL-Muisic-Website/blob/master/screenshots/playlist.png?raw=true)

From implementing perspective, it's trivial. Just select the infomation according to playlistid.

### Deal with requests: data_manager.php

This php file deal with all the XMLHttpRequest. 

It parse the request, trigger the sql server to do something and send feedbacks.

A typical pattern like this.

1. decide the request type, 
2. parse and make sure it's secure if necessary
3. trigger a sql query
4. return a result.



### User Friendly Feedbacks

To give user friendly-feed backs. I classify the interactions into 4 class.

* AJAX smooth feedcak

In the search.php, I use pure AJAX way to implement the search function. After you get a response, then it will trigger a JS function to change the inner html. 

* front-end instant feedback

The rate button in play.php follow button in user.php and like button in artist.php.

User will get a feedback the moment he clicks. Because, I think for such a small operation, user might not want to wait until he get a feedback.

* reload the page

For managing playlist and updating the profile, after the userside get a response, it will reload the page, so that user can see the updated page.

I implement it this way, because it works and easy to implement. A pure AJAX way should be better.

* alert

For adding a track into playlist, after get a response, user will know what's the result of this operation and not reload the page. Because user can be listening to the music, this operetion should not annoy the user by reloading the page.


### Privacy

Some playlist are marked private. 

Such playlist won't be displayed in the "feed" or "search" page of any user. But its owner can manage the private playlist in its own manage_playlist page.


### Security

* Against SQL Injection
 
There're 4 pages that contain text input fileds, I use mysqli_real_escape_string wrapper to build legal SQL string.

~~~php
// escape variables for security

//in signin.php
$inusername = mysqli_real_escape_string($conn,$_POST["inusername"]);
$inupassword = mysqli_real_escape_string($conn,$_POST["inupassword"]);

//in signup.php
$upusername = mysqli_real_escape_string($conn,$_POST["upusername"]);
$upupassword = mysqli_real_escape_string($conn,$_POST["upupassword"]);
$upuname = mysqli_real_escape_string($conn,$_POST["upuname"]);

//in data_manager.php (for update profile request from profile.php)
$secure_username = mysqli_real_escape_string($conn,$_POST['username']);
$secure_uname = mysqli_real_escape_string($conn,$_POST['uname']);
$secure_email = mysqli_real_escape_string($conn,$_POST['uemail']);
$secure_ustate = mysqli_real_escape_string($conn,$_POST['ustate']);
$secure_ucity = mysqli_real_escape_string($conn,$_POST['ucity']);

//in data_manager.php (for search request from search.php)
$keyword = mysqli_real_escape_string($conn,$_POST["keyword"]);

//in data_manager.php (for add playlist request from manage_playlist.php)
$playlisttitle = mysqli_real_escape_string($conn,$_POST["playlisttitle"]);
~~~


>This function is used to create a legal SQL string that you can use in an SQL statement. The given string is encoded to an escaped SQL string, taking into account the current character set of the connection.

* Against XSS(cross-site scripting)

Outputs (which related to userinputs) return to user's browser should be checked to avoid scripts;


There are three place(profile.php,search.php, manage_playlist.php) in my projects that will have user-server interactions after submit a text fild.
(Note That signin or signout page will only echo constant content, so no need to worry them.);

For `profile.php`, use htmlspecialchars to assure secure outputs.

~~~php
//in profile.php
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" name="profile_uname" type="text" id="sample6" 
      value=<?php echo "\"" . htmlspecialchars($uname, ENT_QUOTES, 'UTF-8') . "\""; ?> >
    <label class="mdl-textfield__label" for="sample6">REAL NAME</label>
</div>
~~~

For `search.php`, no "echo", only decode json data from mysql server.

(As long as no 'scripts' in our data base, it should be safe.)

~~~php
//in search.php, we use AJAX to rechieve search results from data_manager.php
//in data_manager.php
echo json_encode($s_results);

//then decode it in search.php
document.getElementById("s_results_all").innerHTML = s_results_all_html;
~~~

For `manage_playlist.php`, reload the page after submiting form, avoids "echo".

~~~php
//in manage_playlist.php
http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
        window.location.reload();
    }
  }
~~~

### Concurrency

The concurrency of this website is assured by two part.

1. PHP will automaitically create a session of each user.

2. All modifications of database are written into transactions. If there is more than one actions to be done, a exception handler will be created in that transaction, and ROLLBACK when necessary. The details can be seen in the `transactions.sql`


### Extra Feature

* token

After signing in, every sesssion is assigned a token.

And every request that is sent to the `data_manager.php` will be checked if the token and username is matched. 

By this setting,  anonymous link will not pass the Oath check, only signed in user can modify his data.

* randomized feed with recommendations reasons.

Add some random features into the feed page, and user can see different feed every time.

Also, I give the reasons why this item is recommended here.

* encrypted password

Only encrypted password is stored and checked in this site, thus it's more secure.



