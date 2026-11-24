
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Entities\Autor;
use BibliotecaORM\Repositories\AutorRepository;

$conexao = Database::conectar();

$repositorio = new AutorRepository($conexao);

// Cadastro
$autor = new Autor("Robert C. Martin");
$autorSalvo = $repositorio->salvar($autor);

echo "Autor cadastrado!" . PHP_EOL;
echo "ID: " . $autorSalvo->getId() . PHP_EOL;
echo "Nome: " . $autorSalvo->getNome() . PHP_EOL;

// Busca
$autorEncontrado = $repositorio->buscar(
    $autorSalvo->getId()
);

echo PHP_EOL . "Autor encontrado:" . PHP_EOL;
echo $autorEncontrado->getNome() . PHP_EOL;

// Listagem
echo PHP_EOL . "Lista de autores:" . PHP_EOL;

foreach ($repositorio->listar() as $item) {
    echo $item->getId() . " - " . $item->getNome() . PHP_EOL;
}
