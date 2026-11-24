<?php

namespace BibliotecaORM\Entities;

class Autor
{
    private ?int $id;

    private string $nome;

    public function __construct(string $nome)
    {
        $this->id = null;

        $this->setNome($nome);
    }

    /**
     * Reconstrói um autor existente no banco.
     */
    public static function reconstruir(
        int $id,
        string $nome
    ): self {

        if ($id <= 0) {
            throw new \InvalidArgumentException(
                "O identificador deve ser positivo."
            );
        }

        $autor = new self($nome);

        $autor->id = $id;

        return $autor;
    }

    // GETTERS

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    // SETTERS

    public function setNome(string $nome): void
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new \InvalidArgumentException(
                "O nome do autor não pode ser vazio."
            );
        }

        if (mb_strlen($nome) > 150) {
            throw new \InvalidArgumentException(
                "O nome não pode ultrapassar 150 caracteres."
            );
        }

        $this->nome = $nome;
    }
}
