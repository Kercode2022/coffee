<?php

require('vendor/autoload.php');
use Carbon\Carbon;

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

echo("Hello world !") . "<br>";
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
echo "<br>";
// $edible = $db->query('SELECT name, price FROM edible')->fetchAll();
// foreach ($edible as $dbedible) {
//     echo "Le café " . $dbedible['name'] . " coûte ". $dbedible ['price'] . "€" . "<br>";
// }
// echo "<br>";
// //pour présenter les consos coutant 1.3€
// $prices = $db->query('SELECT name, price FROM edible WHERE FORMAT(price, 1) = 1.3')->fetchAll(); //(price, 1) veut dire qu'on ne veut qu'un chiffre après la virgule
// foreach ($prices as $dbprix) {
//     echo "Le café " . $dbprix['name'] . " coûte ". $dbprix ['price'] . "€" . "<br>";
// }

echo("<h2>Total Facture 1</h2>");
$total = $db->query('SELECT price, quantity FROM orderedible WHERE idOrder=1')->fetchAll();
$count=0;
foreach($total as $dbtotal){
     $count += ($dbtotal['price'] * $dbtotal['quantity']);
}
echo "Le total de la facture est de : " . $count . " €.";


echo("<h2>Chiffre d'affaire du serveur 2</h2>");
$CA = $db->query('SELECT FORMAT(SUM(price*quantity),2) AS ca FROM orderedible, `order` WHERE orderedible.idOrder=`order`.id AND idWaiter=2')->fetch(); //SELECT FORMAT(SUM(price*quantity),2) AS ca FROM order INNER JOIN orderedible ON order.id=orderedible.idOrder WHERE idWaiter=2;
echo "Le chiffre d'affaire du serveur 2 est: " . $CA['ca'] . " €.";



echo("<h2>Nom des serveurs pour table 3</h2>");
$nomTable= $db->query('SELECT DISTINCT(name) FROM waiter, `order` WHERE waiter.id=`order`.`idWaiter` AND idRestaurantTable=3')->fetchAll();
foreach($nomTable as $dbNomTable){
echo $dbNomTable['name'] . "<br>";
}


echo("<h2>Café(s) le(s) plus consommé(s)</h2>");
$top= $db->query('SELECT name, SUM(quantity) FROM orderedible, edible WHERE orderedible.idEdible=edible.id GROUP BY idEdible ORDER BY SUM(quantity) DESC LIMIT 1')->fetchAll(); //SELECT name, SUM(quantity) AS total FROM orderedible INNER JOIN edible ON orderedible.idEdible=edible.id 
                       //GROUP BY orderedible.idEdible HAVING total>=(SELECT SUM(quantity) AS total FROM orderedible INNER JOIN edible 
                       //ON orderedible.idEdible=edible.id GROUP BY orderedible.idEdible ORDER BY total DESC LIMIT 1); solution si plusieurs cafés ont la même valeur la plus haute!!!!!
foreach($top as $dbTop){
echo "Notre café le plus consommé est le " . $dbTop['name'] . "<br>";
}


echo("<h2>Serveur 2</h2>");
$waiter2= $db->query('SELECT name, createdAt, FORMAT(SUM(price), 2) AS facture FROM waiter,`order`, orderedible WHERE waiter.id=`order`.`idWaiter` AND `order`.`id`=orderedible.idOrder AND idWaiter=2 GROUP BY idOrder')->fetchAll();
foreach($waiter2 as $dbWaiter2){
  $carbon = Carbon::parse($dbWaiter2['createdAt']);
  echo $dbWaiter2['name'] . " " . $carbon->locale('fr')->diffForHumans() . " " . $dbWaiter2['facture'] . '<br>';
}