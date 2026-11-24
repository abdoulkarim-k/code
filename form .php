<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "example";

$join = new mysqli($servername, $username, $password, $dbname);

if($join->connect_error){
    die("connection failed: " .$join->connect_error);
}
