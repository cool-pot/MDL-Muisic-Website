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

$get_album_meta = "select * from album where albumid = ".$_GET['albumid'].";";
$result = $conn->query($get_album_meta);
$album_meta = $result->fetch_assoc();
//console_log($album_meta);

$get_album_song = "select * from Acontain natural join Track where albumid = ". $_GET['albumid']." order by aorder;";
$result = $conn->query($get_album_song);
$album_song = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $album_song[] = $row;
  }
}
//console_log($playlist_song);


$conn->close();
?>

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>


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
           <i class="material-icons mdl-list__item-icon">album</i>
          <?php 
            echo $album_meta['albumtitle'];
          ?>
          </h2>
         </div>
         <div class="mdl-card__supporting-text">
            <ul class="demo-list-icon mdl-list">

            <?php              
              foreach($album_song as $r){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">music_note</i>
                      <a target="_blank" href="play.php?trackid=' .
                      $r['trackid'].
                      '&source=album&pdesc='.
                      $_GET['albumid'].
                      '">' . 
                      $r['tracktitle'].
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