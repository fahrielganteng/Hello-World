<?php

$server ="localhost";
$user ="root";
$password = "";
$nama_database ="kasir";

$db = mysqli_connect($server, $user, $password, $nama_database);

if(!$db) {
   die("gagal terhubungdengan database: " . mysqli_connet_error());
}