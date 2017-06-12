<?php 
$dsn = 'mysql:host=localhost;dbname=member;charset=utf8';
$user = 'root';
$pass = 'reimurata977';

//try catchでDBに接続。
try{
    $db = new PDO($dsn, $user, $pass, array(PDO::ATTR_EMULATE_PREPARES => false));
} catch( PDOException $e ){
    exit('データベース接続失敗。'.$e->getMessage());
    die();
}
?>