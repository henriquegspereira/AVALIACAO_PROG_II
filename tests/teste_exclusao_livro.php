<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();
$repositorio = new LivroRepository($conexao);

// Busca o livro antes de excluir
$livro = $repositorio->buscar(1);

if ($livro === null) {
    die("Livro não encontrado." . PHP_EOL);
}

echo "Livro encontrado: " . $livro->getTitulo() . PHP_EOL;

// Exclusão
$excluido = $repositorio->excluir($livro->getId());

echo $excluido
    ? "Livro excluído com sucesso!" . PHP_EOL
    : "Não foi possível excluir o livro." . PHP_EOL;

// Confirmação
$consulta = $repositorio->buscar($livro->getId());

echo $consulta === null
    ? "Confirmação: livro não existe mais no banco." . PHP_EOL
    : "Erro: livro ainda existe no banco." . PHP_EOL;
