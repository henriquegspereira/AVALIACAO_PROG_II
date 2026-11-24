<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();
$repositorio = new LivroRepository($conexao);

// Recupera o livro já cadastrado, incluindo seus autores.
$livro = $repositorio->buscar(2);

if ($livro === null) {
    exit("Livro não encontrado." . PHP_EOL);
}

echo "Livro: " . $livro->getTitulo() . PHP_EOL;

echo "Autores antes: " . count($livro->getAutores()) . PHP_EOL;

// Adiciona novamente um autor que já está associado.
$autorExistente = $livro->getAutores()[0];
$livro->adicionarAutor($autorExistente);

echo "Autores depois: " . count($livro->getAutores()) . PHP_EOL;