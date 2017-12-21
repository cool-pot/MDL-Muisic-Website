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

$getprofilesql = "CALL get_profile('". $_SESSION['username'] . "')";
$result = $conn->query($getprofilesql);
$row = $result->fetch_assoc();
$username = $row['username'];
$uname = $row['uname'];
$uemail = $row['uemail'];
$ustate = $row['ustate'];
$ucity = $row['ucity'];
//console_log($row);
$conn->close();
?> 

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>


<script>
function submitProfileForm(form)
{ 

  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var params = "q=UPDATEPROFILE" + "&username="+ form.username.value
               + "&token="+ form.token.value +
               "&uname=" + form.profile_uname.value + "&uemail=" + form.profile_uemail.value +
              "&ustate=" + form.profile_ustate.value + "&ucity=" + form.profile_ucity.value;
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

      <div class="demo-card-profile  mdl-card mdl-shadow--2dp">
         <div class="mdl-card__title mdl-card--expand">
           <h2 class="mdl-card__title-text">
          Profile for 
          <?php 
            echo $_SESSION['username'];
          ?>
          </h2>
         </div>
         <div class="mdl-card__supporting-text">
           <form id="update_profile" onsubmit="submitProfileForm(this);">
           <input name="username" type="hidden" value=<?php echo "\"" . $_SESSION['username'] . "\""; ?> >
           <input name="token" type="hidden" value=<?php echo "\"" . $_SESSION['token'] . "\""; ?> >
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" name="profile_uname" type="text" id="sample6" value=<?php echo "\"" . htmlspecialchars($uname, ENT_QUOTES, 'UTF-8') . "\""; ?> >
              <label class="mdl-textfield__label" for="sample6">REAL NAME</label>
           </div>
           <br/>
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" name="profile_uemail" type="text" id="sample7" value=<?php echo "\"" . htmlspecialchars($uemail, ENT_QUOTES, 'UTF-8') . "\""; ?> >
              <label class="mdl-textfield__label" for="sample7">EMAIL</label>
           </div>
           <br/>
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" name="profile_ustate" type="text" id="sample8" value=<?php echo "\"" . htmlspecialchars($ustate, ENT_QUOTES, 'UTF-8') . "\""; ?> >
              <label class="mdl-textfield__label" for="sample8">STATE</label>
           </div>
           <br/>
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input" name="profile_ucity" type="text" id="sample9" value=<?php echo "\"" . htmlspecialchars($ucity, ENT_QUOTES, 'UTF-8') . "\""; ?> >
              <label class="mdl-textfield__label" for="sample9">CITY</label>
           </div>
           <br/>
           <div>
              <input class="mdl-button mdl-js-button mdl-button--primary" type="submit" value="UPDATE">
           </div>
           </form>
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