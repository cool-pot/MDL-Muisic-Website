<?php
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>

<?php 
function redirect($url){
    echo "<script>location.href='". $url ."';</script>";
}
?>
