<?php

namespace BibliotecaORM\Contracts;

interface RepositoryInterface
{
    public function buscar(int $id): ?object;

    public function listar(): array;

    public function salvar(object $entidade): object;

    public function excluir(int $id): bool;
}
