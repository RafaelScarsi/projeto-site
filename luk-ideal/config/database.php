<?php
// =============================================
// CONFIGURAÇÃO DO BANCO DE DADOS - Luk Ideal
// =============================================

define('DB_HOST', 'localhost'); // IP Fixo do servidor de banco de dados
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'luk_ideal');
define('DB_PORT', 3306);

/**
 * Conecta ao banco de dados MySQL
 * @return mysqli Objeto de conexão
 */
function conectarBanco() {
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conexao->connect_error) {
        die('<div class="alert alert-danger text-center m-4">
             <strong>Erro de conexão:</strong> ' . $conexao->connect_error . '
             </div>');
    }

    $conexao->set_charset('utf8mb4');
    return $conexao;
}

// Instância global da conexão
$conn = conectarBanco();
