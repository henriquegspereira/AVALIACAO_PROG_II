
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;

try {
    $conexao = Database::conectar();

    echo "Conexão com MySQL estabelecida com sucesso!" . PHP_EOL;

    $resultado = $conexao->query("SELECT DATABASE()")->fetchColumn();

    echo "Banco conectado: " . $resultado . PHP_EOL;

} catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage() . PHP_EOL;
}
