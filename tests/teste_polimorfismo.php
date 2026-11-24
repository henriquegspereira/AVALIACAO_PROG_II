<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Contracts\RepositoryInterface;
use BibliotecaORM\Repositories\LivroRepository;
use BibliotecaORM\Repositories\AutorRepository;

$conexao = Database::conectar();

// Ambos são tratados pelo mesmo contrato.
$repositorios = [
    new LivroRepository($conexao),
    new AutorRepository($conexao)
];

function demonstrarRepositorio(
    RepositoryInterface $repositorio
): void {
    echo "Tipo de repositório: "
        . get_class($repositorio) . PHP_EOL;

    $registros = $repositorio->listar();

    echo "Quantidade de registros: "
        . count($registros) . PHP_EOL;

    foreach ($registros as $registro) {
        echo "ID: " . $registro->getId() . PHP_EOL;
    }

    echo "------------------------" . PHP_EOL;
}

foreach ($repositorios as $repositorio) {
    demonstrarRepositorio($repositorio);
}
