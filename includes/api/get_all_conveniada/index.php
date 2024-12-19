<?php
require_once('../../config.php');
require_once('../../database.php');

$data = json_decode(file_get_contents("php://input"), true);

// instância do banco
$db = new database();

$params = [
    ':id' => $data['id_conveniada']
];

$results = $db->EXE_NON_QUERY("SELECT * FROM orgaos WHERE id = :id", $params);

echo json_encode($results, 128);