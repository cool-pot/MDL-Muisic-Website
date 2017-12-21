<?php
session_start();
include "console.php";
//console_log($_SESSION['signedin']);
//console_log($_SESSION['username']);
$trackid = $_GET['trackid'];
$timestamp = date('Y-m-d H:i:s');
$source = $_GET['source'];
$pdesc = $_GET['pdesc'];
//console_log($trackid);
//console_log($timestamp);

$servername = "127.0.0.1";
$username = "root";
$password = "passmysql";
$dbname = "music"; 
$conn = new mysqli($servername, $username, $password, $dbname);
// test connection
if ($conn->connect_error) {
    die("connect to mysql,failed: " . $conn->connect_error);
}
$record_play_sql = "call record_play('". $trackid  ."','".$_SESSION['username']."','" . $timestamp . "','" . $source ."','". $pdesc ."');";
$result = $conn->query($record_play_sql);


$play_playlists_sql = "select playlistid,playlisttitle from Playlist where owner ='" . $_SESSION['username'] . "';";
$result = $conn->query($play_playlists_sql);
$p_playlists = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()){
  		$p_playlists[] = $row;
   	}
}

$track_info_sql = "select * from Track natural join Artist where trackid = " . $trackid . ";";
$result = $conn->query($track_info_sql);
$info = $result->fetch_assoc();
//console_log($info);


$rate_sql = "select score from Rate where username = '".$_SESSION['username']. "'and trackid = ". $trackid .";";
$result = $conn->query($rate_sql);
$score = $result->fetch_assoc()['score'];
//console_log($score);
$conn->close();

?>


<meta charset="utf-8">
<title>Cloud Music Play Page</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<script>
function submitAddPlaylist(playlistid,trackid,username,token){
	//console.log("submitAddPlaylist!");
	//console.log(playlistid);
	//console.log(trackid);
	//console.log(username);
	//console.log(token);
	var http = new XMLHttpRequest();
  	var url = "data_manager.php";
  	var params = "q=ADDTOPLAYLIST" + "&playlistid="+ playlistid +
  				"&trackid=" + trackid +"&username=" + username + "&token=" + token;
  	//console.log(params);
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
</script>

<script>
function submitRate(score,username,token,trackid){
  //console.log(score);
  //console.log(username);
  //console.log(token);
  //console.log(trackid);
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var params = "q=ADDRATE" + "&username="+ username + "&token=" + token +
              "&trackid=" + trackid +"&score=" + score; 
  http.open("POST", url, true);
    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
      }
  }
  http.send(params); 
}
</script>




<div class="demo-card-play mdl-card mdl-shadow--2dp">
	<div class="mdl-card__title mdl-card--expand">
        <h2 class="mdl-card__title-text"><?php echo $info['tracktitle']?></h2>
        <br/>
    </div>
    <div class="mdl-card__supporting-text">
    	<audio src=<?php echo '"' . 'track/' . $trackid . '.mp3"' ?> controls>
    	<h6><?php echo $info['aname']?></h6>	
    </div>
</div>

<div class="demo-card-res  mdl-card mdl-shadow--2dp">
	<div class="mdl-card__supporting-text">
   	<fieldset class="rating">
       <legend>Give a rate:</legend>
       <?php
         if(is_null($score))
         {
          for ($x=5; $x>=1; $x--) {
            echo '<input type="radio" onclick="submitRate(this.value,\'' .$_SESSION['username'].'\',\''. $_SESSION['token'] . '\',' .$trackid. ')" id="star' . $x .'" name="rating" value="'.$x.'" /><label for="star'.$x.'">'.$x.' stars</label>';
          } 
         }
         else{
           for ($x=5; $x>=1; $x--) {
            if($x == $score){
              echo '<input type="radio" checked onclick="submitRate(this.value,\'' .$_SESSION['username'].'\',\''. $_SESSION['token'] . '\',' .$trackid. ')" id="star' . $x .'" name="rating" value="'.$x.'" /><label for="star'.$x.'">'.$x.' stars</label>';
            }
            else{
              echo '<input type="radio" onclick="submitRate(this.value,\'' .$_SESSION['username'].'\',\''. $_SESSION['token'] . '\',' .$trackid. ')" id="star' . $x .'" name="rating" value="'.$x.'" /><label for="star'.$x.'">'.$x.' stars</label>';
            }
          } 
         }
       ?>
   	</fieldset>
	</div>
	<div class="mdl-card__supporting-text">
		<p>Artist:</p>
		<ul class="demo-list-icon mdl-list">
			<?php echo '<li class="mdl-list__item">
					  <span class="mdl-list__item-primary-content"> 
					  <i class="material-icons mdl-list__item-icon">face</i>               
                      <a href="artist.php?artistid=' .
                      $info['artistid'].
                      '">' . 
                      $info['aname'].
                      '</a>
                      </span>
                      </li>'?>
        </ul>
        <p>Add to your list?</p>
        <ul class="demo-list-icon mdl-list">
            <?php
              foreach($p_playlists as $p){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">queue_music</i>
                      <a href="playlist.php?playlistid=' .
                      $p['playlistid'].
                      '">' . 
                      $p['playlisttitle'].
                      '</a>
                      </span>
                      <span>
                      	<a class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onclick="submitAddPlaylist('.
                      	$p['playlistid'].
                      	','.
                      	$trackid.
                      	',\''.
                      	$_SESSION['username'] .
                      	'\',\''.
                      	$_SESSION['token'] .

                      	'\')">
  						<i class="material-icons">add</i>
						</a>
                      </span>
                      </li>';
              }
            ?>
          </ul>
	</div> 
</div>



