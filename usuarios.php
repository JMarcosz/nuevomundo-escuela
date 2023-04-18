<?php

//Importar la conexíon
require 'includes/app.php';
$db = conectarDB();
//Crear un email y password
$email = "correo@correo.com";
$password = "123456";
$password = password_hash($password, PASSWORD_BCRYPT);

//Query para crear usuario
$query = " INSERT INTO usuarios(email, password) VALUES ('$email', '$password') ";

//Agregarlo a la base de datos

mysqli_query($db, $query);

