<?php
// database dingen voor mysql
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'schoenreus herkansing');
 
//met mysql verbinden
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// check connectie
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>