<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Entities\Livro;
use BibliotecaORM\Repositories\AutorRepository;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();

$autorRepository = new AutorRepository($conexao);
$livroRepository = new LivroRepository($conexao);

// Recupera os autores já cadastrados.
$autor1 = $autorRepository->buscar(1);
$autor2 = $autorRepository->buscar(1);

// Neste primeiro teste, precisamos de dois autores distintos.
if ($autor1 === null || $autor2 === null) {
    die("Autores não encontrados." . PHP_EOL);
}

// Cria um segundo autor caso ainda não exista.
$autor2 = $autorRepository->salvar(
    new \BibliotecaORM\Entities\Autor("Martin Fowler")
);

// Cria o livro.
$livro = new Livro(
    "Refatoração",
    "9788575227244",
    2019
);

// Associa os autores.
$livro->adicionarAutor($autor1);
$livro->adicionarAutor($autor2);

// Salva livro e vínculos em uma transação.
$livroSalvo = $livroRepository->salvarComAutores($livro);

echo "Livro cadastrado com sucesso!" . PHP_EOL;
echo "ID: " . $livroSalvo->getId() . PHP_EOL;
echo "Título: " . $livroSalvo->getTitulo() . PHP_EOL;

echo PHP_EOL . "Autores associados:" . PHP_EOL;

foreach ($livroSalvo->getAutores() as $autor) {
    echo $autor->getId() . " - " . $autor->getNome() . PHP_EOL;
}

echo "Total de autores: " . count($livroSalvo->getAutores()) . PHP_EOL;
