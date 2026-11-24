<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Entities\Livro;
use BibliotecaORM\Entities\Autor;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();
$repositorio = new LivroRepository($conexao);

// Livro com ISBN exclusivo para este teste.
$livro = new Livro(
    "Livro de Teste Rollback",
    "9999999999999",
    2026
);

// Associa um autor que não existe no banco.
$autorInexistente = Autor::reconstruir(
    999999,
    "Autor Inexistente"
);

$livro->adicionarAutor($autorInexistente);

try {

    $repositorio->salvarComAutores($livro);

    echo "Atenção: a operação foi concluída inesperadamente."
        . PHP_EOL;

} catch (PDOException $erro) {

    echo "Erro de integridade referencial provocado!"
        . PHP_EOL;

    echo "Mensagem: " . $erro->getMessage() . PHP_EOL;
}

// Verifica se o livro permaneceu no banco.
$consulta = $conexao->prepare(
    "SELECT COUNT(*) FROM livros WHERE isbn = :isbn"
);

$consulta->execute([
    'isbn' => '9999999999999'
]);

$total = (int) $consulta->fetchColumn();

echo PHP_EOL;

if ($total === 0) {
    echo "ROLLBACK confirmado: o livro não foi cadastrado."
        . PHP_EOL;
} else {
    echo "ERRO: o livro permaneceu cadastrado no banco."
        . PHP_EOL;
}
