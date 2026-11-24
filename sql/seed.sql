USE biblioteca_orm;

START TRANSACTION;

-- 1. Cadastra os autores caso ainda não existam.

INSERT INTO autores (nome)
SELECT 'Robert C. Martin'
WHERE NOT EXISTS (
    SELECT 1 FROM autores
    WHERE nome = 'Robert C. Martin'
);

INSERT INTO autores (nome)
SELECT 'Martin Fowler'
WHERE NOT EXISTS (
    SELECT 1 FROM autores
    WHERE nome = 'Martin Fowler'
);

-- 2. Cadastra o livro caso ainda não exista.
-- O ISBN é um identificador de demonstração.

INSERT INTO livros (titulo, isbn, ano_publicacao)
SELECT
    'BibliotecaORM - Livro de Demonstracao',
    'SEED-9780000000001',
    2026
WHERE NOT EXISTS (
    SELECT 1 FROM livros
    WHERE isbn = 'SEED-9780000000001'
);

-- 3. Associa os dois autores ao livro.

INSERT IGNORE INTO livros_autores (livro_id, autor_id)
SELECT
    l.id,
    a.id
FROM livros l
INNER JOIN autores a
    ON a.nome IN ('Robert C. Martin', 'Martin Fowler')
WHERE l.isbn = 'SEED-9780000000001';

COMMIT;
