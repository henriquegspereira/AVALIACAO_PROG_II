<?php

namespace BibliotecaORM\Config;

use PDO;
use PDOException;

class Database
{
    public static function conectar(): PDO
    {
        $host = getenv('DB_HOST');
        $porta = getenv('DB_PORT');
        $banco = getenv('DB_DATABASE');
        $usuario = getenv('DB_USERNAME');
        $senha = getenv('DB_PASSWORD');

        $dsn = "mysql:host={$host};port={$porta};dbname={$banco};charset=utf8mb4";

        try {
            return new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $erro) {
            throw new PDOException(
                "Não foi possível conectar ao banco de dados.",
                (int) $erro->getCode(),
                $erro
            );
        }
    }
}
