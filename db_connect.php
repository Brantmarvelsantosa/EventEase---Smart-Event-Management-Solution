<?php

$db_config = [
  'host'     => 'mysql-2f13a7b8-eventmanagementsystem.k.aivencloud.com',
  'port'     => 10596,
  'username' => 'avnadmin',
  'password' => 'AVNS_4tmvd8c9NOpnIQOgOC3',
  'database' => 'aiven_db'
];

function getConnectionToDatabase()
{
  global $db_config;

  $conn = new mysqli(
    $db_config['host'],
    $db_config['username'],
    $db_config['password'],
    $db_config['database'],
    $db_config['port']
  );

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}
