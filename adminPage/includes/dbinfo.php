<?php
  $servername = "searchengine355.cmbeixtswu4n.us-east-1.rds.amazonaws.com";
  $username = "admin";
  $password = "adminSE355";
  $dbname = "SearchEngine355";

  $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
