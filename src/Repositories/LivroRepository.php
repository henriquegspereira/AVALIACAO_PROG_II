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

        $livro = Livro::reconstruir(
            (int) $registro['id'],
            $registro['titulo'],
            $registro['isbn'],
            $registro['ano_publicacao'] !== null
                ? (int) $registro['ano_publicacao']
                : null
        );

        // Recupera os autores associados ao livro.
        $registrosAutores = $this->buscarTodos(
            "SELECT a.id, a.nome
            FROM autores a
            INNER JOIN livros_autores la
                ON la.autor_id = a.id
            WHERE la.livro_id = :livro_id
            ORDER BY a.nome",
            ['livro_id' => $id]
        );

        foreach ($registrosAutores as $registroAutor) {
            $autor = \BibliotecaORM\Entities\Autor::reconstruir(
                (int) $registroAutor['id'],
                $registroAutor['nome']
            );

            $livro->adicionarAutor($autor);
        }

        return $livro;
    }


    
    public function listar(): array
    {
        $registros = $this->buscarTodos(
            "SELECT id, titulo, isbn, ano_publicacao
            FROM livros
            ORDER BY titulo"
        );

        $livros = [];

        // Primeiro, reconstruímos todos os livros.
        foreach ($registros as $registro) {
            $livro = Livro::reconstruir(
                (int) $registro['id'],
                $registro['titulo'],
                $registro['isbn'],
                $registro['ano_publicacao'] !== null
                    ? (int) $registro['ano_publicacao']
                    : null
            );

            $livros[$livro->getId()] = $livro;
        }

        // Depois, recuperamos todos os vínculos em uma única consulta.
        $registrosAutores = $this->buscarTodos(
            "SELECT
                la.livro_id,
                a.id AS autor_id,
                a.nome AS autor_nome
            FROM livros_autores la
            INNER JOIN autores a
                ON a.id = la.autor_id
            ORDER BY a.nome"
        );

        // Associamos cada autor ao respectivo livro.
        foreach ($registrosAutores as $registroAutor) {
            $livroId = (int) $registroAutor['livro_id'];

            if (!isset($livros[$livroId])) {
                continue;
            }

            $autor = \BibliotecaORM\Entities\Autor::reconstruir(
                (int) $registroAutor['autor_id'],
                $registroAutor['autor_nome']
            );

            $livros[$livroId]->adicionarAutor($autor);
        }

        return array_values($livros);
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


    public function salvarComAutores(Livro $livro): Livro
    {
        try {
            $this->conexao->beginTransaction();

            // Primeiro, cadastra o livro.
            $livroSalvo = $this->salvar($livro);

            
        // Depois, grava os vínculos com os autores.
        foreach ($livro->getAutores() as $autor) {

            if ($autor->getId() === null) {
                throw new InvalidArgumentException(
                    "Todos os autores precisam estar cadastrados no banco."
                );
            }

            // Verifica se o vínculo já existe.
            $vinculoExistente = $this->buscarUm(
                "SELECT livro_id
                FROM livros_autores
                WHERE livro_id = :livro_id
                AND autor_id = :autor_id",
                [
                    'livro_id' => $livroSalvo->getId(),
                    'autor_id' => $autor->getId()
                ]
            );

            if ($vinculoExistente !== null) {
                continue;
            }

            $this->executar(
                "INSERT INTO livros_autores (livro_id, autor_id)
                VALUES (:livro_id, :autor_id)",
                [
                    'livro_id' => $livroSalvo->getId(),
                    'autor_id' => $autor->getId()
                ]
            );

            $livroSalvo->adicionarAutor($autor);
        }


            $this->conexao->commit();

            return $livroSalvo;

        } catch (\Throwable $erro) {

            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }

            throw $erro;
        }
    }
}
