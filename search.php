<?php
session_start();
include "console.php";
//console_log($_SESSION['signedin']);
//console_log($_SESSION['username']);
?>

<meta charset="utf-8">
<title>Cloud Music</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<link href="mystyle.css" rel="stylesheet" type="text/css"/>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

<script>
function submitSearchForm(form)
{ 
  var http = new XMLHttpRequest();
  var url = "data_manager.php";
  var params = "q=SEARCH" + "&keyword="+ form.s_query.value;
  //console.log(params);
  http.open("POST", url, true);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
    	var responseJSON = JSON.parse(http.responseText);
    	//console.log(responseJSON);
    	
    	//artists
    	s_results_artists = responseJSON[0];
    	var s_results_artists_html = "";
    	if(s_results_artists.length>0){
    		s_results_artists_html += '<ul class="demo-list-icon mdl-list">';
    		for (var i = 0; i < s_results_artists.length; ++i){
    		  var obj = JSON.parse(s_results_artists[i]);
			  s_results_artists_html += '<li class="mdl-list__item"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">face</i><a href="artist.php?artistid=' +
                      obj.artistid +
                      '">' + 
                      obj.aname+
                      '</a></span></li>';
    		}
    		s_results_artists_html += '</ul>';
    		//document.getElementById("s_results_artists").innerHTML = s_results_artists_html;
    	}

    	//tracks
    	s_results_tracks = responseJSON[1];
    	var s_results_tracks_html = "";
    	if(s_results_tracks.length>0){
    		s_results_tracks_html += '<ul class="demo-list-icon mdl-list">';
    		for (var i = 0; i < s_results_tracks.length; ++i){
    		  var obj = JSON.parse(s_results_tracks[i]);
			  s_results_tracks_html += '<li class="mdl-list__item"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">music_note</i><a target="_blank" href="play.php?trackid=' +
                      obj.trackid +
                      '&source=search&pdesc=none">' + 
                      obj.tracktitle+
                      '</a></span></li>';
    		}
    		s_results_tracks_html += '</ul>';
    		//document.getElementById("s_results_tracks").innerHTML = s_results_tracks_html;
    	}

    	//albums
    	s_results_albums = responseJSON[2];
    	var s_results_albums_html = "";
    	if(s_results_albums.length>0){
    		s_results_albums_html += '<ul class="demo-list-icon mdl-list">';
    		for (var i = 0; i < s_results_albums.length; ++i){
    		  var obj = JSON.parse(s_results_albums[i]);
			  s_results_albums_html += '<li class="mdl-list__item"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">album</i><a href="album.php?albumid=' +
                      obj.albumid +
                      '">' + 
                      obj.albumtitle+
                      '</a></span></li>';
    		}
    		s_results_albums_html += '</ul>';
    		//console.log(s_results_albums_html);
    		//document.getElementById("s_results_albums").innerHTML = s_results_albums_html;
    	}

    	//users
    	s_results_users = responseJSON[3];
    	var s_results_users_html = "";
    	if(s_results_users.length>0){
    		s_results_users_html += '<ul class="demo-list-icon mdl-list">';
    		for (var i = 0; i < s_results_users.length; ++i){
    		  var obj = JSON.parse(s_results_users[i]);
			  s_results_users_html += '<li class="mdl-list__item"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">person</i><a href="user.php?username=' +
                      obj.username +
                      '">' + 
                      obj.username+
                      '</a></span></li>';
    		}
    		s_results_users_html += '</ul>';
    		//console.log(s_results_users_html);
    		//document.getElementById("s_results_users").innerHTML = s_results_users_html;
    	}

    	//playlists
    	s_results_playlists = responseJSON[4];
    	var s_results_playlists_html = "";
    	if(s_results_playlists.length>0){
    		s_results_playlists_html += '<ul class="demo-list-icon mdl-list">';
    		for (var i = 0; i < s_results_playlists.length; ++i){
    		  var obj = JSON.parse(s_results_playlists[i]);
			  s_results_playlists_html += '<li class="mdl-list__item"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">queue_music</i><a href="playlist.php?playlistid=' +
                      obj.playlistid +
                      '">' + 
                      obj.playlisttitle+
                      '</a></span></li>';
    		}
    		s_results_playlists_html += '</ul>';
    		//console.log(s_results_playlists_html);
    		//document.getElementById("s_results_playlists").innerHTML = s_results_playlists_html;
    	}
    	s_results_all_html = "";
    	s_results_all_html += s_results_artists_html + s_results_tracks_html + s_results_albums_html + s_results_users_html +s_results_playlists_html;
    	document.getElementById("s_results_all").innerHTML = s_results_all_html;
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
  <!--Search box-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
       <div class="demo-card-search mdl-card mdl-shadow--2dp">
         <div class="mdl-card__title mdl-card--expand" >
          <h2 class="mdl-card__title-text">
          Search
          </h2>
         </div>
         <div class="mdl-card__supporting-text">
           <form id="search_query" onsubmit="return submitSearchForm(this);">
             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" name="s_query" type="text" id="sample8">
                <label class="mdl-textfield__label" for="sample8">Keywords</label>
             </div>
             <button type="submit" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
  				<i class="material-icons">search</i>
			 </button>
            </form>
          </div>
       </div>
    </div>
    <div class="mdl-cell mdl-cell--2-col"></div>
  </div>

  <!--Search Results-->
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>
    <div class="mdl-cell mdl-cell--8-col">
      <div class="demo-card-res  mdl-card mdl-shadow--2dp">
         <div class="mdl-card__supporting-text">
          <h2 class="mdl-card__title-text">
          Results
          </h2>
          <span id="s_results_all"></span>
          <span id="s_results_artists"></span>
          <span id="s_results_tracks"></span>
          <span id="s_results_albums"></span>
          <span id="s_results_users"></span>
          <span id="s_results_playlists"></span>
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

