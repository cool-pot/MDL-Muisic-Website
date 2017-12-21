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

$get_artist_meta = "select * from artist where artistid = ".$_GET['artistid'].";";
$result = $conn->query($get_artist_meta);
$artist_meta = $result->fetch_assoc();
//console_log($artist_meta);

$get_artist_song ="select trackid,tracktitle from Artist natural join Track where artistid =". $_GET['artistid'].";";
$result = $conn->query($get_artist_song);
$artist_song = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $artist_song[] = $row;
  }
}
//console_log($artist_song);

$isliked_query = "select * from Likes where artistid = '" . $_GET['artistid'] . "' and username = '" .$_SESSION['username'] ."';";
$result = $conn->query($isliked_query);
$isliked = ! is_null($result->fetch_assoc());
console_log($isliked);

$related_artist_query = "call related_artist('". $_GET['artistid'] ."');";
$result = $conn->query($related_artist_query);
$related_artist = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $related_artist[] = $row;
  }
}
//console_log($related_artist);

$conn->close();
?>

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<link href="like-btn.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<script type="text/javascript">
  function submitLike(checked,token,username,aid){
    //console.log("checked:",checked);
    //console.log("token:",token);
    //console.log("username:",username);
    //console.log("aid:",aid);
    if(checked){
      var http = new XMLHttpRequest();
      var url = "data_manager.php";
      var params = "q=ADDLIKE" + "&token="+ token +
          "&username=" + username +"&artistid=" + aid;
      console.log(params);
      http.open("POST", url, true);
      //Send the proper header information along with the request
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.onreadystatechange = function() {//Call a function when the state changes.
      if(http.readyState == 4 && http.status == 200) {
        //alert(http.responseText);
      }
     }
     http.send(params); 
    }else{
      var http = new XMLHttpRequest();
      var url = "data_manager.php";
      var params = "q=DELETELIKE" + "&token="+ token +
          "&username=" + username +"&artistid=" + aid;
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
  }
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
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
      <div class="demo-card-play mdl-card mdl-shadow--2dp">
         <div class="mdl-card__title mdl-card--expand">
           <h2 class="mdl-card__title-text">
           <i class="material-icons mdl-list__item-icon">face</i>
          <?php 
            echo $artist_meta['aname'];
            if($isliked){
                  echo '<input id="like" type="checkbox" checked="checked" onclick = submitLike(this.checked,\''.$_SESSION['token']. '\',\'' . $_SESSION['username'] .'\',\''. $_GET['artistid'] .'\') />';
                }
                else{
                  echo '<input id="like" type="checkbox" onclick = submitLike(this.checked,\''.$_SESSION['token']. '\',\'' . $_SESSION['username'] .'\',\''. $_GET['artistid'] .'\') />';
              }

                
          ?>
          </h2>
         </div>
         <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Tracks
          </h2>
            <ul class="demo-list-icon mdl-list">

            <?php              
              foreach($artist_song as $r){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">music_note</i>
                      <a target="_blank" href="play.php?trackid=' .
                      $r['trackid'].
                      '&source=artist&pdesc='.
                      $_GET['artistid'].
                      '">' . 
                      $r['tracktitle'].
                      '</a>
                      </span>
                      </li>';
              }
            ?>
            </ul>
         </div>
        <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Similar Artists
          </h2>
          <ul class="demo-list-icon mdl-list">
            <?php
              foreach($related_artist as $l){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">face</i>
                      <a href="artist.php?artistid=' .
                      $l['artistid'].
                      '">' . 
                      $l['aname'].
                      '</a>
                      </span>
                      </li>';
              }
            ?>
          </ul>
         </div>
      </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>



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