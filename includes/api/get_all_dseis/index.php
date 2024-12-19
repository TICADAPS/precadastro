<?php
require_once('../../config.php');
require_once('../../database.php');

$data = json_decode(file_get_contents("php://input"), true);

// instância do banco
$db = new database();


$results = $db->EXE_QUERY("SELECT * FROM dseis;");

echo json_encode($results, 128);