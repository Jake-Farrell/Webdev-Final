<?php
    define('DB_DSN','mysql:host=localhost;dbname=final;charset=utf8');
    define('DB_USER','BaseUser');
    define('DB_PASS','123456789');     
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);   
    
    
function function_alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}



?>

