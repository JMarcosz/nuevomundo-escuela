<?php 
require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Propiedad;
$propiedad = new Propiedad;

//Conectarnos a la db
$db = conectarDB();
Propiedad::setDB($db);
