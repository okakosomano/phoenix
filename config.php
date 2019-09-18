<?php
/*database credentials.*/
define('DB_SERVER', 'sql311.byethost.com');
define('DB_USERNAME', 'b3_24500132');
define('DB_PASSWORD', 'phoenix');
define('DB_NAME', 'b3_24500132_users');

/*connect to database*/
$link = mysqli_connect('sql311.byethost.com','b3_24500132','phoenix','b3_24500132_users');

//check connection
if($link === false){
    die("ERROR: Could not connect.".mysqli_connect_error());
}
?>