<?php

namespace BibliotecaORM\Repositories;

use BibliotecaORM\Contracts\RepositoryInterface;
use PDO;
use PDOStatement;

abstract class AbstractRepository implements RepositoryInterface
{
    protected PDO $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * Prepara e executa uma instrução SQL.
     */
    protected function executar(
        string $sql,
        array $parametros = []
    ): PDOStatement {
        $consulta = $this->conexao->prepare($sql);
        $consulta->execute($parametros);

        return $consulta;
    }

    /**
     * Retorna todos os registros encontrados.
     */
    protected function buscarTodos(
        string $sql,
        array $parametros = []
    ): array {
        return $this->executar($sql, $parametros)->fetchAll();
    }

    /**
     * Retorna apenas um registro ou null.
     */
    protected function buscarUm(
        string $sql,
        array $parametros = []
    ): ?array {
        $resultado = $this->executar($sql, $parametros)->fetch();

        return $resultado === false ? null : $resultado;
    }
}
