<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Entities\Livro;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();

$repositorio = new LivroRepository($conexao);

// Cadastro
$livro = new Livro(
    "Código Limpo",
    "9788576082675",
    2009
);

$livroSalvo = $repositorio->salvar($livro);

echo "Livro cadastrado!" . PHP_EOL;
echo "ID: " . $livroSalvo->getId() . PHP_EOL;
echo "Título: " . $livroSalvo->getTitulo() . PHP_EOL;

// Busca por ID
$livroEncontrado = $repositorio->buscar(
    $livroSalvo->getId()
);

echo PHP_EOL . "Livro encontrado:" . PHP_EOL;

if ($livroEncontrado !== null) {
    echo "ID: " . $livroEncontrado->getId() . PHP_EOL;
    echo "Título: " . $livroEncontrado->getTitulo() . PHP_EOL;
    echo "ISBN: " . $livroEncontrado->getIsbn() . PHP_EOL;
    echo "Ano: " . $livroEncontrado->getAnoPublicacao() . PHP_EOL;
}

// Listagem
echo PHP_EOL . "Lista de livros:" . PHP_EOL;

foreach ($repositorio->listar() as $item) {
    echo $item->getId() . " - " . $item->getTitulo() . PHP_EOL;
}
