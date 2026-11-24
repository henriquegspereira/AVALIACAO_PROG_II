<?php

namespace BibliotecaORM\Entities;

class Livro
{
    private ?int $id;

    private string $titulo;

    private string $isbn;

    private ?int $anoPublicacao;

    /**
     * @var Autor[]
     */
    private array $autores = [];

    /**
     * Construtor de um novo livro.
     */
    public function __construct(
        string $titulo,
        string $isbn,
        ?int $anoPublicacao = null
    ) {
        $this->id = null;

        $this->setTitulo($titulo);
        $this->setIsbn($isbn);
        $this->setAnoPublicacao($anoPublicacao);
    }

    /**
     * Reconstrói um livro existente no banco.
     */
    public static function reconstruir(
        int $id,
        string $titulo,
        string $isbn,
        ?int $anoPublicacao = null
    ): self {

        if ($id <= 0) {
            throw new \InvalidArgumentException(
                "O identificador deve ser positivo."
            );
        }

        $livro = new self($titulo, $isbn, $anoPublicacao);

        $livro->id = $id;

        return $livro;
    }

    // GETTERS

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getAnoPublicacao(): ?int
    {
        return $this->anoPublicacao;
    }

    // SETTERS COM VALIDAÇÃO

    public function setTitulo(string $titulo): void
    {
        $titulo = trim($titulo);

        if ($titulo === '') {
            throw new \InvalidArgumentException(
                "O título do livro não pode ser vazio."
            );
        }

        if (mb_strlen($titulo) > 200) {
            throw new \InvalidArgumentException(
                "O título não pode ultrapassar 200 caracteres."
            );
        }

        $this->titulo = $titulo;
    }

    public function setIsbn(string $isbn): void
    {
        $isbn = trim($isbn);

        if ($isbn === '' || mb_strlen($isbn) > 20) {
            throw new \InvalidArgumentException(
                "O ISBN deve possuir entre 1 e 20 caracteres."
            );
        }

        $this->isbn = $isbn;
    }

    public function setAnoPublicacao(?int $ano): void
    {
        if ($ano !== null && ($ano < 1 || $ano > 9999)) {
            throw new \InvalidArgumentException(
                "Ano de publicação inválido."
            );
        }

        $this->anoPublicacao = $ano;
    }

    public function adicionarAutor(Autor $autor): void
    {
        $idAutor = $autor->getId();

        foreach ($this->autores as $autorExistente) {

            // Evita duplicidade de autores já persistidos.
            if (
                $idAutor !== null &&
                $autorExistente->getId() === $idAutor
            ) {
                return;
            }

            // Evita adicionar duas vezes a mesma instância.
            if ($autorExistente === $autor) {
                return;
            }
        }

        $this->autores[] = $autor;
    }

    /**
     * @return Autor[]
     */
    public function getAutores(): array
    {
        return $this->autores;
    }

    /**
     * Substitui a coleção de autores.
     */
    public function definirAutores(array $autores): void
    {
        $this->autores = [];

        foreach ($autores as $autor) {

            if (!$autor instanceof Autor) {
                throw new \InvalidArgumentException(
                    "A coleção deve conter apenas objetos Autor."
                );
            }

            $this->adicionarAutor($autor);
        }
    }
}
