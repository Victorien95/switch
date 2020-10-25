<?php

//Connexion à la BDD
$host_db = 'mysql:host=localhost;dbname=switch';
$login = 'root';
$password = '';
//$host_db = 'mysql:host=fatondevej603.mysql.db;dbname=fatondevej603';
//$login = 'fatondevej603';
//$password = 'Virginie971';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);
$pdo = new PDO($host_db, $login, $password, $options);

$msg = "";

session_start();



define('URL', 'http://switch/');


define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/switch/');


define('SITE_ROOT', '/switch/');




require_once 'fonctions.php';
