<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BibliotecaORM\Config\Database;
use BibliotecaORM\Repositories\LivroRepository;

$conexao = Database::conectar();
$repositorio = new LivroRepository($conexao);

// Recupera o livro cadastrado anteriormente
$livro = $repositorio->buscar(1);

if ($livro === null) {
    die("Livro não encontrado." . PHP_EOL);
}

// Altera os dados
$livro->setTitulo("Código Limpo - Edição Atualizada");
$livro->setAnoPublicacao(2020);

// Atualiza no banco
$atualizado = $repositorio->atualizar($livro);

echo $atualizado
    ? "Livro atualizado com sucesso!" . PHP_EOL
    : "Nenhuma alteração realizada." . PHP_EOL;

// Consulta novamente para confirmar
$livroConsultado = $repositorio->buscar(1);

echo "Título: " . $livroConsultado->getTitulo() . PHP_EOL;
echo "Ano: " . $livroConsultado->getAnoPublicacao() . PHP_EOL;
