<?php
session_start();
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
    </div>
  </header>
 
  <main class="mdl-layout__content">
	<div class="mdl-grid">
  	<div class="mdl-cell mdl-cell--4-col"></div>
  	<div class="mdl-cell mdl-cell--4-col">



    <?php
include "console.php";
$servername = "127.0.0.1";
$username = "root";
$password = "passmysql";
$dbname = "music"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);

// test connection
if ($conn->connect_error) {
    die("connect to mysql,failed: " . $conn->connect_error);
}

$upusername = mysqli_real_escape_string($conn,$_POST["upusername"]);
$upupassword = mysqli_real_escape_string($conn,$_POST["upupassword"]);
$upuname = mysqli_real_escape_string($conn,$_POST["upuname"]);

$empty = ($upusername=="") || ($upupassword=="") || ($upuname=="");


if($empty == True){
  $conn->close();
	echo "<div class=\"demo-card-event mdl-card mdl-shadow--2dp\">
  <div class=\"mdl-card__title mdl-card--expand\">
    <h4>
      Error, Failed. Empty Inputs are not accepted.
    </h4>
  </div>
  <div class=\"mdl-card__actions mdl-card--border\">
    <a href=\"index.html\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
      Go back.
    </a>
    <div class=\"mdl-layout-spacer\"></div>
    <i class=\"material-icons\">event</i>
  </div>
</div>";
}
else{

$upupassword_hashed = password_hash($upupassword, PASSWORD_DEFAULT);
//console_log($upupassword_hashed);
$signupsql = "CALL signup('" . $upusername . "','". $upupassword_hashed . "','" . $upuname . "');";
$result = $conn->query($signupsql);
$conn->close();
if ($result != True){
	echo "<div class=\"demo-card-event mdl-card mdl-shadow--2dp\">
  <div class=\"mdl-card__title mdl-card--expand\">
    <h4>
      Error, Failed. Something wrong with your input and our database.
    </h4>
  </div>
  <div class=\"mdl-card__actions mdl-card--border\">
    <a href=\"index.html\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
      Go back.
    </a>
    <div class=\"mdl-layout-spacer\"></div>
    <i class=\"material-icons\">event</i>
  </div>
</div>";

}else{
	echo "<div class=\"demo-card-success mdl-card mdl-shadow--2dp\">
  <div class=\"mdl-card__title mdl-card--expand\">
    <h4>
      You signed up successfully! 
    </h4>
  </div>
  <div class=\"mdl-card__actions mdl-card--border\">
    <a href=\"index.html\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
      Go back.
    </a>
    <div class=\"mdl-layout-spacer\"></div>
    <i class=\"material-icons\">event</i>
  </div>
</div>";
}
}
?>
  	</div>
  	<div class="mdl-cell mdl-cell--4-col"></div>
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