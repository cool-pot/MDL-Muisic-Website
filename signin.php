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

// escape variables for security
$inusername = mysqli_real_escape_string($conn,$_POST["inusername"]);
$inupassword = mysqli_real_escape_string($conn,$_POST["inupassword"]);

$empty = ($inusername=="") || ($inupassword=="");

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
  $signinsql = "select upassword from User where username ='" .$inusername. "';";
  $result = $conn->query($signinsql);
  $conn->close();
  $row = $result->fetch_assoc();
  $hash = $row['upassword'];
  $sign_in_result = password_verify( $inupassword , $hash );
  //console_log($inusername);
  //console_log($inupassword);
  //console_log($hash);
  //console_log($sign_in_result);
  if ($sign_in_result != True){

  echo "<div class=\"demo-card-event mdl-card mdl-shadow--2dp\">
  <div class=\"mdl-card__title mdl-card--expand\">
    <h4>
      Error, Failed. Username and password don't match.
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
  $_SESSION["signedin"]= True;
  $_SESSION["username"]= $inusername;
  $_SESSION['token'] = md5(uniqid(mt_rand(),true));
  echo "<div class=\"demo-card-success mdl-card mdl-shadow--2dp\">
  <div class=\"mdl-card__title mdl-card--expand\">
    <h4>
      You signed in successfully! 
    </h4>
  </div>
  <div class=\"mdl-card__actions mdl-card--border\">
    <a href=\"ui.php\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
      Forward.
    </a>
    <div class=\"mdl-layout-spacer\"></div>
    <i class=\"material-icons\">event</i>
  </div>
</div>";
  redirect("feed.php");
      
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