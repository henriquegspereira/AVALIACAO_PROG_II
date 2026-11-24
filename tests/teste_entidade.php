<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Entities\Livro;

$livro = new Livro(
    "Engenharia de Software",
    "9780133943030",
    2014
);

echo "Título: " . $livro->getTitulo() . PHP_EOL;
echo "ISBN: " . $livro->getIsbn() . PHP_EOL;
echo "Ano: " . $livro->getAnoPublicacao() . PHP_EOL;

echo "ID: ";

var_dump($livro->getId());

// Testando alteração
$livro->setTitulo("Engenharia de Software Moderna");

echo "Novo título: " . $livro->getTitulo() . PHP_EOL;

// Testando validação
try {
    $livro->setTitulo("");
} catch (InvalidArgumentException $e) {
    echo "Erro esperado: " . $e->getMessage() . PHP_EOL;
}

// Simulando reconstrução de um livro do banco
$livroBanco = Livro::reconstruir(
    15,
    "Banco de Dados",
    "9788521613258",
    2020
);

echo "Livro reconstruído: ";
echo $livroBanco->getId() . " - ";
echo $livroBanco->getTitulo() . PHP_EOL;