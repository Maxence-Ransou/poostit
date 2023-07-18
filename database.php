<?php
define('HOST', '127.0.0.1');
define('DB_NAME', 'promptverse');
define('USER', 'root');
define('PASS', '');
try{
    $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASS);
    $db -> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e;
}