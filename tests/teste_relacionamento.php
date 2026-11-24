
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Entities\Livro;
use BibliotecaORM\Entities\Autor;

// Criando livro
$livro = new Livro(
    "Engenharia de Software",
    "9780133943030",
    2014
);

// Criando autores
$autor1 = Autor::reconstruir(1, "Ian Sommerville");
$autor2 = Autor::reconstruir(2, "Martin Fowler");

// Associando autores
$livro->adicionarAutor($autor1);
$livro->adicionarAutor($autor2);

// Tentando adicionar novamente o primeiro autor
$livro->adicionarAutor($autor1);

// Exibindo relacionamento
echo "Livro: " . $livro->getTitulo() . PHP_EOL;

echo "Autores associados:" . PHP_EOL;

foreach ($livro->getAutores() as $autor) {
    echo "- " . $autor->getNome() . PHP_EOL;
}

echo "Total de autores: ";
echo count($livro->getAutores()) . PHP_EOL;
