<?php

// gestion des erreurs
//error_reporting(E_ALL);
//ini_set("display_errors",1);

// chargement des librairies
require "vendor/autoload.php";
use vlucas\phpdotenv;

// chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// connexion a la BDD BATINV
try {
  $batinv_db = new PDO('mysql:host='.$_ENV['BATINV_DB_HOST'].';dbname='.$_ENV['BATINV_DB_NAME'],$_ENV['BATINV_DB_LOGIN'],$_ENV['BATINV_DB_PASSWORD']);
}catch (PDOException $e){
  print "Erreur de connexion a la BDD BATINV : <br/> ". $e->getMessage(). "<br/>";
}

$batinv_db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);