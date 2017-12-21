<?php
session_start();
include "console.php";
//console_log($_SESSION['signedin']);
//console_log($_SESSION['username']);

$servername = "127.0.0.1";
$username = "root";
$password = "passmysql";
$dbname = "music"; 
$conn = new mysqli($servername, $username, $password, $dbname);
// test connection
if ($conn->connect_error) {
    die("connect to mysql,failed: " . $conn->connect_error);
}

$playlists = array();
$playlist_sql = "select * from Playlist where owner = '" . $_SESSION['username'] . "' order by playlisttime desc;";
$result = $conn->query($playlist_sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $playlists[] = $row;
  }
}
//console_log($playlists);
?>

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<script>
function submitAddPlaylistForm(form)
{ 
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var ptype = "public";
  if(form.private.checked){
  	ptype = "private";
  }
  var params = "q=ADDPLAYLIST" + "&username="+ form.username.value
               + "&token="+ form.token.value 
               + "&playlisttitle=" + form.playlisttitle.value
               + "&ptype=" + ptype;
  //console.log(params);
  http.open("POST", url, true);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
    }
  }
  http.send(params);
  //return false;
}
</script>
<script type="text/javascript">
function submitDeleteTrack(playlistid,trackid,username,token){
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var params = "q=DELETETRACKFROMPLAYLIST" + "&username="+ username
               + "&token="+ token
               + "&playlistid=" + playlistid
               + "&trackid=" + trackid;
  //console.log(params);
  http.open("POST", url, true);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
        window.location.reload();
    }
  }
  http.send(params);
  return false;
}
</script>
<script type="text/javascript">
function submitDeletePlaylist(playlistid,username,token){
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var params = "q=DELETEPLAYLIST" + "&username="+ username
               + "&token="+ token
               + "&playlistid=" + playlistid;
  //console.log(params);
  http.open("POST", url, true);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
        window.location.reload();
    }
  }
  http.send(params);
  return false;
               
}
	
</script>


<div class="demo-layout-transparent mdl-layout mdl-js-layout">
  <header class="mdl-layout__header mdl-layout__header--transparent">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title">Cloud Music. Inc</span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- Navigation -->
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="feed.php">Feed</a>
        <a class="mdl-navigation__link" href="profile.php">Profile</a>
        <a class="mdl-navigation__link" href="musictaste.php">MusicTaste</a>
        <a class="mdl-navigation__link" href="search.php">Search</a>
        <a class="mdl-navigation__link" href="manage_playlist.php">Playlist</a>
      </nav>
    </div>
  </header>
  <div class="mdl-layout__drawer">
  
    <span class="mdl-layout-title">
    <img src="assets/user.jpg" class="demo-avatar" >
    <?php 
      echo $_SESSION['username'];
    ?>
    </span>
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="feed.php">Feed</a>
        <a class="mdl-navigation__link" href="profile.php">Profile</a>
        <a class="mdl-navigation__link" href="musictaste.php">MusicTaste</a>
        <a class="mdl-navigation__link" href="search.php">Search</a>
        <a class="mdl-navigation__link" href="manage_playlist.php">Playlist</a>
      </nav>
  </div>

  <main class="mdl-layout__content">
  <!--playlists-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
       <div class="demo-card-search mdl-card mdl-shadow--2dp">
         <div class="mdl-card__title mdl-card--expand" >
         <h2 class="mdl-card__title-text">Manage your playlists.</h2>
         </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>
  </div>
  </div>
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
       <div class="demo-card-res mdl-card mdl-shadow--2dp">
       	 <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
           Create a new playlist?
          </h2>
         <!--new playlist form-->
         <div class="mdl-card__supporting-text">
           <form id="update_profile" onsubmit="return submitAddPlaylistForm(this);">
           <input name="username" type="hidden" value=<?php echo "\"" . $_SESSION['username'] . "\""; ?> >
           <input name="token" type="hidden" value=<?php echo "\"" . $_SESSION['token'] . "\""; ?> >
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" name="playlisttitle" type="text" id="sample6">
              <label class="mdl-textfield__label" for="sample6">Playlist Title</label>
           </div>
           <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1">
           		<span class="mdl-checkbox__label">Private?</span>
  				<input type="checkbox" id="checkbox-1" name="private" class="mdl-checkbox__input">
		   </label>
           <br/>
           <br/>
           <div>
              <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" type="submit" value="CREATE">
           </div>
           </form>
         </div>
         <!--new playlist form done-->


         </div>
       </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>
    </div>
    <?php 
    foreach($playlists as $p){
    echo '<div class="mdl-grid">';
   	echo '<div class="mdl-cell mdl-cell--2-col"></div>';
    echo '<div class="mdl-cell mdl-cell--8-col">';
       echo '<div class="demo-card-res mdl-card mdl-shadow--2dp">';
       	 echo '<div class="mdl-card__supporting-text">';
          echo '<h2 class="mdl-card__title-text">';
           echo $p['playlisttitle'] . '[' . $p['ptype'] .']';
           echo '<span><a class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored"  onclick="submitDeletePlaylist(\''.
                      	$p['playlistid'].
                      	'\',\''.
                      	$_SESSION['username'] .
                      	'\',\''.
                      	$_SESSION['token'] .
                      	'\')">
  						<i class="material-icons">delete</i>
						</a></span>';

          echo '</h2>';

          //get_playlist_songs
          $servername = "127.0.0.1";
          $username = "root";
          $password = "passmysql";
          $dbname = "music"; 
          $conn = new mysqli($servername, $username, $password, $dbname);
          // test connection
          if ($conn->connect_error) {
              die("connect to mysql,failed: " . $conn->connect_error);
          }          

          $playlist_songs = array();
          $get_playlist_song = "select * from Pcontain natural join Track where playlistid = ". $p['playlistid']." order by porder;";
          $result = $conn->query($get_playlist_song);
          $playlist_song = array();
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $playlist_song[] = $row;
            }
          }
          //output songs info
          foreach($playlist_song as $r){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">music_note</i>
                      <a target="_blank" href="play.php?trackid=' .
                      $r['trackid'].
                      '&source=playlist&pdesc='.
                      $r['playlistid'].
                      '">' . 
                      $r['tracktitle'].
                      '</a>
                      </span>
                      <span>
                      	<a class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"  onclick="submitDeleteTrack('.
                      	$r['playlistid'].
                      	','.
                      	$r['trackid'].
                      	',\''.
                      	$_SESSION['username'] .
                      	'\',\''.
                      	$_SESSION['token'] .
                      	'\')">
  						<i class="material-icons">delete</i>
						</a>
                      </span>
                      </li>';
           }



         echo '</div>';
       echo'</div>';
      echo'</div>';
    echo '<div class="mdl-cell mdl-cell--2-col"></div>';
    echo '</div>';
	}
    ?>
  
  


  

  </main>



  <footer class="mdl-mini-footer">
    <div class="mdl-mini-footer--left-section">
        <ul class="mdl-mini-footer--link-list">
            <li><a href="#">Help</a></li>
            <li><a href="#">Privacy and Terms</a></li>
            <li><a href="#">User Agreement</a></li>
        </ul>
    </div>
  </footer>
</div>

