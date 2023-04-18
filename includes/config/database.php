<?php

function conectarDB()
{
    $db = '';
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbConect = 'bienesraices_crud';
    try {
        $db = new mysqli($host, $user, $password, $dbConect);
    } catch (Throwable $ex) {
        echo 'Ha ocurrido un problema: ' . $ex->getMessage() . '<br>' . ' Código de error: ' . $ex->getCode();
        exit;
    }

    return $db;
}
