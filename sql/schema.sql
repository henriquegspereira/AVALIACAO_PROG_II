-- =====================================================
-- BibliotecaORM
-- Banco de dados: biblioteca_orm
-- SGBD: MySQL 8.4
-- =====================================================

USE biblioteca_orm;

-- =====================================================
-- TABELA: livros
-- =====================================================

CREATE TABLE IF NOT EXISTS livros (

    id BIGINT UNSIGNED AUTO_INCREMENT,

    titulo VARCHAR(200) NOT NULL,

    isbn VARCHAR(20) NOT NULL,

    ano_publicacao SMALLINT UNSIGNED NULL,

    CONSTRAINT pk_livros
        PRIMARY KEY (id),

    CONSTRAINT uq_livros_isbn
        UNIQUE (isbn)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================
-- TABELA: autores
-- =====================================================

CREATE TABLE IF NOT EXISTS autores (

    id BIGINT UNSIGNED AUTO_INCREMENT,

    nome VARCHAR(150) NOT NULL,

    CONSTRAINT pk_autores
        PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================
-- TABELA: livros_autores
-- Relacionamento N:N
-- =====================================================

CREATE TABLE IF NOT EXISTS livros_autores (

    livro_id BIGINT UNSIGNED NOT NULL,

    autor_id BIGINT UNSIGNED NOT NULL,

    CONSTRAINT pk_livros_autores
        PRIMARY KEY (livro_id, autor_id),

    CONSTRAINT fk_livros_autores_livro
        FOREIGN KEY (livro_id)
        REFERENCES livros(id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,

    CONSTRAINT fk_livros_autores_autor
        FOREIGN KEY (autor_id)
        REFERENCES autores(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    INDEX idx_livros_autores_autor (autor_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
