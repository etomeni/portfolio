<?php

    require 'constant.php';
    session_start();

    $con = new mysqli(dbHostName, dbUserName, dbPassword, dbName);

    if ($con->connect_error) {
        die('Database error' . $con->connect_error);
    }

?>