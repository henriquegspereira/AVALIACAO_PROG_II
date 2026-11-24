<?php

namespace BibliotecaORM\Repositories;

use BibliotecaORM\Entities\Autor;
use InvalidArgumentException;

class AutorRepository extends AbstractRepository
{
    public function buscar(int $id): ?object
    {
        $registro = $this->buscarUm(
            "SELECT id, nome FROM autores WHERE id = :id",
            ['id' => $id]
        );

        if ($registro === null) {
            return null;
        }

        return Autor::reconstruir(
            (int) $registro['id'],
            $registro['nome']
        );
    }

    public function listar(): array
    {
        $registros = $this->buscarTodos(
            "SELECT id, nome FROM autores ORDER BY nome"
        );

        $autores = [];

        foreach ($registros as $registro) {
            $autores[] = Autor::reconstruir(
                (int) $registro['id'],
                $registro['nome']
            );
        }

        return $autores;
    }

    public function salvar(object $entidade): object
    {
        if (!$entidade instanceof Autor) {
            throw new InvalidArgumentException(
                "AutorRepository aceita apenas objetos Autor."
            );
        }

        $sql = "INSERT INTO autores (nome) VALUES (:nome)";

        $this->executar($sql, [
            'nome' => $entidade->getNome()
        ]);

        $id = (int) $this->conexao->lastInsertId();

        return Autor::reconstruir(
            $id,
            $entidade->getNome()
        );
    }

    public function excluir(int $id): bool
    {
        $consulta = $this->executar(
            "DELETE FROM autores WHERE id = :id",
            ['id' => $id]
        );

        return $consulta->rowCount() > 0;
    }
}
