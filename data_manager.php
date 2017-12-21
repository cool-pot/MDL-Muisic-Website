<?php
session_start();
ob_start();
?>

<?php
$q = $_POST["q"];
if($q == "UPDATEPROFILE"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success.";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$secure_username = mysqli_real_escape_string($conn,$_POST['username']);
		$secure_uname = mysqli_real_escape_string($conn,$_POST['uname']);
		$secure_email = mysqli_real_escape_string($conn,$_POST['uemail']);
		$secure_ustate = mysqli_real_escape_string($conn,$_POST['ustate']);
		$secure_ucity = mysqli_real_escape_string($conn,$_POST['ucity']);

		$updatefile_sql = "CALL update_profile('" . $secure_username . "','". $secure_uname . "','" . $secure_email . "','". $secure_ustate. "','". $secure_ucity . "');";
		$result = $conn->query($updatefile_sql);
		$conn->close();
		if ($result != True){
			echo "=>UPDATEPROFILE,Faild";
		}else{
			echo "=>UPDATEPROFILE,Success";
		}
	}else{
		echo "UPDATEPROFILE, Oath Failed";
	}
}elseif($q == "SEARCH"){
	$servername = "127.0.0.1";
	$username = "root";
	$password = "passmysql";
	$dbname = "music";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// test connection
	if ($conn->connect_error) {
   		die("connect to mysql,failed: " . $conn->connect_error);
	} 
	$keyword = mysqli_real_escape_string($conn,$_POST["keyword"]);


	$s_results = array();
	$search_artist_sql = "select * from Artist where aname like '%" . $keyword . "%'" .
						 " or adesc like '%" . $keyword . "%'";
	$result = $conn->query($search_artist_sql);
	$s_artists = array();
	if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_artists[] = json_encode($row);
    	}
    }
	
    $search_track_sql = "select * from Track where tracktitle like '%" . $keyword . "%'" .
						 " or genre like '%" . $keyword . "%'";
    $result = $conn->query($search_track_sql);
	$s_tracks = array();
	if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_tracks[] = json_encode($row);
    	}
    }

    $search_album_sql = "select * from Album where albumtitle like '%" . $keyword . "%';";
    $result = $conn->query($search_album_sql);
	$s_albums = array();
	if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_albums[] = json_encode($row);
    	}
    }

    $search_user_sql = "select username from User where username like '%" . $keyword . "%';";
    $result = $conn->query($search_user_sql);
	$s_users = array();
	if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_users[] = json_encode($row);
    	}
    }

    $search_playlist_sql = "select playlistid,playlisttitle from Playlist where ptype ='public' and playlisttitle like '%" . $keyword . "%';";
    $result = $conn->query($search_playlist_sql);
	$s_playlists = array();
	if ($result->num_rows > 0) {
	  while ($row = $result->fetch_assoc()){
	  		$s_playlists[] = json_encode($row);
    	}
    }

    $s_results[] = $s_artists;
    $s_results[] = $s_tracks;
    $s_results[] = $s_albums;
    $s_results[] = $s_users;
    $s_results[] = $s_playlists;
    echo json_encode($s_results);
    //echo $keyword;
	$conn->close();	 
    
}elseif($q == "ADDTOPLAYLIST"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		//echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}
		$add_to_playlist_sql = "CALL add_to_playlist('". $_POST['trackid']. "','". $_POST['playlistid'] . "')";
		$result = $conn->query($add_to_playlist_sql);
		$conn->close();
		if ($result != True){
			//echo "=>ADD_TO_PLAYLIST";
			echo "Already in your playlist.";
			//echo $add_to_playlist_sql;
		}else{
			//echo "=>ADD_TO_PLAYLIST";
			echo "Success";
		}
	}
	else{
		echo "Oath Failed";
	}
}elseif($q == "ADDRATE"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$timestamp = date('Y-m-d H:i:s');
		$add_rate_sql = "CALL add_rate('" . $_POST['trackid']. "','". $_POST['username'].
						"','" . $timestamp . "','" . $_POST['score'] . "');";
		
		$result = $conn->query($add_rate_sql);
		$conn->close();
		if ($result != True){
			echo "=>ADDRATE,Faild";
			echo $add_rate_sql;
		}else{
			echo "=>ADDRATE,Success";
		}

	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "ADDFOLLOW"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$add_follow_sql = "CALL add_follow('" . $_POST['followee']. "','". $_POST['username'] . "');";
		$result = $conn->query($add_follow_sql);
		$conn->close();
		if ($result != True){
			echo "=>ADDFOLLOW,Faild";
			echo $add_follow_sql;
		}else{
			echo "=>ADDFOLLOW,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "DELETEFOLLOW"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$delete_follow_sql = "CALL delete_follow('" . $_POST['followee']. "','". $_POST['username'] . "');";
		$result = $conn->query($delete_follow_sql);
		$conn->close();
		if ($result != True){
			echo "=>DELETEFOLLOW,Faild";
			echo $delete_follow_sql;
		}else{
			echo "=>DELETEFOLLOW,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "ADDLIKE"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}
		$timestamp = date('Y-m-d H:i:s');
		$add_like_sql = "CALL add_like('" . $_POST['username']. "','". $_POST['artistid'] . "','". $timestamp ."');";
		$result = $conn->query($add_like_sql);
		$conn->close();
		if ($result != True){
			echo "=>ADDLIKE,Faild";
			echo $add_like_sql;
		}else{
			echo "=>ADDLIKE,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "DELETELIKE"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$delete_like_sql = "CALL delete_like('" . $_POST['username']. "','". $_POST['artistid'] . "');";
		$result = $conn->query($delete_like_sql);
		$conn->close();
		if ($result != True){
			echo "=>DELETELIKE,Faild";
			echo $delete_like_sql;
		}else{
			echo "=>DELETELIKE,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "ADDPLAYLIST"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}
		$playlisttitle = mysqli_real_escape_string($conn,$_POST["playlisttitle"]);
		$timestamp = date('Y-m-d H:i:s');
		$add_playlist_sql = "CALL add_playlist('" . $playlisttitle . "','". $timestamp ."','". $_POST['ptype']."','". $_POST['username']."');";
		$result = $conn->query($add_playlist_sql);
		$conn->close();
		if ($result != True){
			echo "=>ADDPLAYLIST,Faild";
			echo $add_playlist_sql;
		}else{
			echo "=>ADDPLAYLIST,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "DELETETRACKFROMPLAYLIST"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$delete_track_sql = "CALL delete_pcontain('" . $_POST['playlistid']. "','". $_POST['trackid'] . "');";
		$result = $conn->query($delete_track_sql);
		$conn->close();
		if ($result != True){
			echo "=>DELETETRACKFROMPLAYLIST,Faild";
			echo $delete_track_sql;
		}else{
			echo "=>DELETETRACKFROMPLAYLIST,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}elseif($q == "DELETEPLAYLIST"){
	if($_POST['username']==$_SESSION['username'] and $_POST['token']==$_SESSION['token']){
		echo "Oath Success";
		$servername = "127.0.0.1";
		$username = "root";
		$password = "passmysql";
		$dbname = "music"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		// test connection
		if ($conn->connect_error) {
    		die("connect to mysql,failed: " . $conn->connect_error);
		}

		$delete_playlist_sql = "CALL delete_playlist('" . $_POST['playlistid']. "');";
		$result = $conn->query($delete_playlist_sql);
		$conn->close();
		if ($result != True){
			echo "=>DELETEPLAYLIST,Faild";
			echo $delete_playlist_sql;
		}else{
			echo "=>DELETEPLAYLIST,Success";
		}
	}
	else{
		echo "Oath Failed";
		echo $_POST['username'];
		echo $_POST['token'];
	}
}
else{
	echo  "NOT SUPPOTED";	
}
?>