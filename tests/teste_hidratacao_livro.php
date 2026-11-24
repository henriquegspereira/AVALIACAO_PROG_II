<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();
$repositorio = new LivroRepository($conexao);

// Busca diretamente no banco.
$livro = $repositorio->buscar(2);

if ($livro === null) {
    die("Livro não encontrado." . PHP_EOL);
}

echo "Livro recuperado do banco:" . PHP_EOL;
echo "ID: " . $livro->getId() . PHP_EOL;
echo "Título: " . $livro->getTitulo() . PHP_EOL;

echo PHP_EOL . "Autores recuperados:" . PHP_EOL;

foreach ($livro->getAutores() as $autor) {
    echo $autor->getId() . " - " . $autor->getNome() . PHP_EOL;
}

echo PHP_EOL;
echo "Total de autores: " . count($livro->getAutores()) . PHP_EOL;
