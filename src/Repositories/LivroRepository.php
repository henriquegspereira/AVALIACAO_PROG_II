<?php

namespace BibliotecaORM\Repositories;

use BibliotecaORM\Entities\Livro;
use InvalidArgumentException;

class LivroRepository extends AbstractRepository
{
    public function buscar(int $id): ?object
    {
        $registro = $this->buscarUm(
            "SELECT id, titulo, isbn, ano_publicacao
             FROM livros
             WHERE id = :id",
            ['id' => $id]
        );

        if ($registro === null) {
            return null;
        }

        return Livro::reconstruir(
            (int) $registro['id'],
            $registro['titulo'],
            $registro['isbn'],
            $registro['ano_publicacao'] !== null
                ? (int) $registro['ano_publicacao']
                : null
        );
    }

    public function listar(): array
    {
        $registros = $this->buscarTodos(
            "SELECT id, titulo, isbn, ano_publicacao
             FROM livros
             ORDER BY titulo"
        );

        $livros = [];

        foreach ($registros as $registro) {
            $livros[] = Livro::reconstruir(
                (int) $registro['id'],
                $registro['titulo'],
                $registro['isbn'],
                $registro['ano_publicacao'] !== null
                    ? (int) $registro['ano_publicacao']
                    : null
            );
        }

        return $livros;
    }

    public function salvar(object $entidade): object
    {
        if (!$entidade instanceof Livro) {
            throw new InvalidArgumentException(
                "LivroRepository aceita apenas objetos Livro."
            );
        }

        $sql = "INSERT INTO livros
                (titulo, isbn, ano_publicacao)
                VALUES (:titulo, :isbn, :ano_publicacao)";

        $this->executar($sql, [
            'titulo' => $entidade->getTitulo(),
            'isbn' => $entidade->getIsbn(),
            'ano_publicacao' => $entidade->getAnoPublicacao()
        ]);

        $id = (int) $this->conexao->lastInsertId();

        return Livro::reconstruir(
            $id,
            $entidade->getTitulo(),
            $entidade->getIsbn(),
            $entidade->getAnoPublicacao()
        );
    }

    public function excluir(int $id): bool
    {
        $consulta = $this->executar(
            "DELETE FROM livros WHERE id = :id",
            ['id' => $id]
        );

        return $consulta->rowCount() > 0;
    }


    public function atualizar(Livro $livro): bool
    {
        if ($livro->getId() === null) {
            throw new InvalidArgumentException(
                "Não é possível atualizar um livro sem ID."
            );
        }

        $consulta = $this->executar(
            "UPDATE livros
            SET titulo = :titulo,
                isbn = :isbn,
                ano_publicacao = :ano_publicacao
            WHERE id = :id",
            [
                'titulo' => $livro->getTitulo(),
                'isbn' => $livro->getIsbn(),
                'ano_publicacao' => $livro->getAnoPublicacao(),
                'id' => $livro->getId()
            ]
        );

        return $consulta->rowCount() > 0;
    }
}
