<?php
session_start();
include "console.php";
//console_log($_SESSION['signedin']);
//console_log($_SESSION['username']);
?>

<?php
$servername = "127.0.0.1";
$username = "root";
$password = "passmysql";
$dbname = "music"; 
$conn = new mysqli($servername, $username, $password, $dbname);
// test connection
if ($conn->connect_error) {
    die("connect to mysql,failed: " . $conn->connect_error);
}



$likes = array();
$like_query = "select artistid,aname,ltime from Likes natural join Artist where username='" . $_GET['username'] . "' order by ltime desc;";
$result = $conn->query($like_query);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $likes[] = $row;
  }
}


$rates = array();
$rate_query = "select trackid,tracktitle,score,rtime from Rate natural join Track  where username='" . $_GET['username'] . "' order by rtime desc;";
$result = $conn->query($rate_query);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $rates[] = $row;
  }
}




$follow = array();
$follow_query = "select followee from Follow where follower = '" . $_GET['username'] . "';";
$result = $conn->query($follow_query);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $follow[] = $row;
  }
}


$isfollowing_query = "select * from Follow where followee = '" . $_GET['username'] . "' and follower = '" .$_SESSION['username'] ."';";
$result = $conn->query($isfollowing_query);
$isfollowing = ! is_null($result->fetch_assoc());


$conn->close();

?> 

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<link href="follow-btn.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<script type="text/javascript">
  function submitFollow(checked,token,follower,followee){
    //console.log("checked:",checked);
    //console.log("token:",token);
    //console.log("follower:",follower);
    //console.log("followee:",followee);
    if(checked){
      var http = new XMLHttpRequest();
      var url = "data_manager.php";
      var params = "q=ADDFOLLOW" + "&token="+ token +
          "&username=" + follower +"&followee=" + followee;
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
    }
    else{
      var http = new XMLHttpRequest();
      var url = "data_manager.php";
      var params = "q=DELETEFOLLOW" + "&token="+ token +
          "&username=" + follower +"&followee=" + followee;
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
       <div class="demo-card-taste mdl-card mdl-shadow--2dp">
         <div class="mdl-card__title mdl-card--expand" >
           <h2 class="mdl-card__title-text">
              <i class="material-icons mdl-list__item-icon">person</i>
              <?php 
                echo $_GET['username'];
                if($isfollowing){
                  echo '<input id="follow" type="checkbox" checked="checked" onclick = submitFollow(this.checked,\''.$_SESSION['token']. '\',\'' . $_SESSION['username'] .'\',\''. $_GET['username'] .'\') />';
                }
                else{
                  echo '<input id="follow" type="checkbox" onclick = submitFollow(this.checked,\''.$_SESSION['token']. '\',\'' . $_SESSION['username'] .'\',\''. $_GET['username'] .'\') />';
                }
              ?> 

    
           </h2>
         </div>

       </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>
  </div>

  <!--Likes-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">

      <div class="demo-card-res  mdl-card mdl-shadow--2dp">
         <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Artists Liked
          </h2>
          <ul class="demo-list-icon mdl-list">
            <?php
              foreach($likes as $l){
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
  </div>

  <!--Rate-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
      <div class="demo-card-res  mdl-card mdl-shadow--2dp">
         <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Music Rated
          </h2>
          <ul class="demo-list-icon mdl-list">
            <?php
              foreach($rates as $r){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">music_note</i>
                      <a target="_blank" href="play.php?trackid=' .
                      $r['trackid'].
                      '&source=other&pdesc=none">' . 
                      $r['tracktitle'].
                      '</a>
                      </span>
                      <span class="mdl-list__item-primary-content">' .
                      $r['score'].
                      '<i class="material-icons mdl-list__item-icon">grade</i>
                      </span>
                      </li>';
              }
            ?>
          </ul>
         </div>
      </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>
  </div>

  <!--Follow-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">

      <div class="demo-card-res  mdl-card mdl-shadow--2dp">
         <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Users Follows
          </h2>
          <ul class="demo-list-icon mdl-list">
            <?php
              foreach($follow as $f){
                echo '<li class="mdl-list__item">
                      <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">person</i>
                      <a href="user.php?username=' .
                      $f['followee'].
                      '">' . 
                      $f['followee'].
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
  </div>

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