<?php
// Conexão com o banco de dados
include 'includes/config.php'; 
include 'includes/database.php'; 

if (isset($_GET['dsei_id'])) {
    $dseiId = intval($_GET['dsei_id']);
    
    // Query para buscar as organizações relacionadas ao DSEI
    $query = "SELECT distinct o.id,o.nome_orgao from dseis d inner join orgaos o on d.conveniada_id = o.id where d.id = $dseiId ;";
    $db = new database();
    $result = $db->EXE_QUERY($query);

    // Retorna os dados em formato JSON
    echo json_encode($result);
} else {
    echo json_encode([]);
}
