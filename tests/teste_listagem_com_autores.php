<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();

$repositorio = new LivroRepository($conexao);

$livros = $repositorio->listar();

echo "Livros cadastrados:" . PHP_EOL . PHP_EOL;

foreach ($livros as $livro) {

    echo "Livro: " . $livro->getTitulo() . PHP_EOL;
    echo "ID: " . $livro->getId() . PHP_EOL;

    echo "Autores:" . PHP_EOL;

    if (count($livro->getAutores()) === 0) {
        echo "- Nenhum autor associado." . PHP_EOL;
    }

    foreach ($livro->getAutores() as $autor) {
        echo "- " . $autor->getNome() . PHP_EOL;
    }

    echo "------------------------" . PHP_EOL;
}
