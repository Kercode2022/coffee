<?php

// require('vendor/autoload.php');

// $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// echo('Hello world !');

// echo "<br/>";

// //PDO = PHP Data Object
// function bdd(){
//     try{
//         $bdd = new PDO("mysql:dbname=" . $_ENV["DB_NAME"] . ";host=" . $_ENV["DB_HOST"] . ":" . $_ENV["DB_PORT"] . ", " . $_ENV["DB_USERNAME"] . ", " . $_ENV["DB_PASSWORD"] . "");
//     }catch(PDOException $e){
//         echo "Connexion impossible: " . $e->getMessage();
//     }
//     return $bdd;

// }

// $bdd= bdd(); 
// // var_dump($bdd);

// function waiters(){
//     global $bdd;

//     $req = $bdd->query('SELECT name FROM waiter');

//     $waiters = $req->fetchAll();

//     return $waiters;
// }
// $waiters = waiters();

// foreach($waiters as $waiter){
//     echo "<br/>".$waiter['name'];
// }
require('vendor/autoload.php');

if($_SERVER['HTTP_HOST'] != "coffee-irish.herokuapp.com"){
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

echo("Hello world !");
echo "<br/>";

function dbaccess() {
  $dbConnection = "mysql:dbname=". $_ENV['DB_NAME'] ."; host=". $_ENV['DB_HOST'] .":". $_ENV['DB_PORT'] ."; charset=utf8";
  $user = $_ENV['DB_USERNAME'];
  $pwd = $_ENV['DB_PASSWORD'];
  
  $db = new PDO ($dbConnection, $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  return $db;
}
  
$db = dbaccess();

$req = $db->query('SELECT name FROM waiter')->fetchAll();

foreach ($req as $dbreq) {
  echo $dbreq['name'] . "<br>";
}