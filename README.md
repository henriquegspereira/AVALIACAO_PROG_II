# BibliotecaORM

**Implementação de um ORM próprio utilizando PHP, PDO e MySQL.**

Projeto desenvolvido como avaliação prática individual da disciplina de Programação II — UNOESC.

## 1. Sobre o projeto

O BibliotecaORM é uma implementação de um *Object-Relational Mapping* (ORM) desenvolvido em PHP puro, utilizando PDO para comunicação com o banco de dados MySQL.

O objetivo é demonstrar como uma aplicação pode mapear registros relacionais em objetos PHP, utilizando conceitos de Programação Orientada a Objetos, como encapsulamento, herança, abstração, interfaces e polimorfismo.

O projeto utiliza duas entidades principais:

- **Livro:** representa os livros cadastrados na biblioteca.
- **Autor:** representa os autores associados aos livros.

O relacionamento entre livros e autores é do tipo muitos-para-muitos (N:N), implementado por meio da tabela intermediária `livros_autores`.

## 2. Tecnologias utilizadas

- PHP 8.3
- MySQL 8.4
- PDO (PHP Data Objects)
- Docker e Docker Compose
- Composer (autoload PSR-4)
- phpMyAdmin

Não são utilizados frameworks ou bibliotecas ORM de terceiros.

## 3. Arquitetura do projeto

A estrutura está organizada em camadas:

```text
BibliotecaORM/
├── public/
│   └── index.php
├── src/
│   ├── Config/
│   │   └── Database.php
│   ├── Contracts/
│   │   └── RepositoryInterface.php
│   ├── Entities/
│   │   ├── Autor.php
│   │   └── Livro.php
│   └── Repositories/
│       ├── AbstractRepository.php
│       ├── AutorRepository.php
│       └── LivroRepository.php
├── sql/
│   ├── schema.sql
│   └── seed.sql
├── tests/
│   ├── teste_atualizacao_livro.php
│   ├── teste_autor_repository.php
│   ├── teste_conexao.php
│   ├── teste_duplicidade.php
│   ├── teste_entidade.php
│   ├── teste_exclusao_livro.php
│   ├── teste_hidratacao_livro.php
│   ├── teste_listagem_com_autores.php
│   ├── teste_livro_autores.php
│   ├── teste_livro_repository.php
│   ├── teste_polimorfismo.php
│   ├── teste_relacionamento.php
│   └── teste_rollback.php
├── docs/
├── .env.example
├── .gitignore
├── composer.json
├── docker-compose.yml
├── Dockerfile
└── apache-vhost.conf
```

### 3.1 Config

Contém a classe `Database`, responsável por estabelecer a conexão com o MySQL utilizando PDO e variáveis de ambiente.

### 3.2 Contracts

Contém a interface `RepositoryInterface`, que define as operações comuns aos repositórios:

- `buscar(int $id)`
- `listar()`
- `salvar(object $entidade)`
- `excluir(int $id)`

### 3.3 Entities

Contém as entidades `Livro` e `Autor`, com propriedades privadas, construtores, métodos de acesso e validações.

A entidade `Livro` também mantém uma coleção de autores associados, evitando duplicidades na própria coleção.

Os identificadores são definidos internamente durante a reconstrução de objetos recuperados do banco de dados.

### 3.4 Repositories

Contém a implementação das operações de persistência.

A classe abstrata `AbstractRepository` centraliza comportamentos compartilhados de execução e recuperação de consultas SQL.

`LivroRepository` e `AutorRepository` especializam essa implementação para suas respectivas entidades.

### 3.5 Public

Contém o ponto de entrada HTTP da aplicação, `index.php`, utilizado atualmente para verificar o funcionamento do ambiente PHP.

## 4. Banco de dados

O banco utilizado é `biblioteca_orm`, composto pelas seguintes tabelas:

| Tabela | Responsabilidade |
|---|---|
| `livros` | Armazena os livros. |
| `autores` | Armazena os autores. |
| `livros_autores` | Armazena os relacionamentos N:N. |

O relacionamento utiliza chaves estrangeiras e uma chave primária composta por `livro_id` e `autor_id`, impedindo vínculos duplicados.

As regras de exclusão são:

- Livro: `ON DELETE CASCADE` nos vínculos.
- Autor: `ON DELETE RESTRICT` quando existem vínculos associados.

O índice adicional em `livros_autores.autor_id` auxilia consultas que partem do identificador do autor.

Os scripts SQL estão disponíveis em:

- `sql/schema.sql`: criação da estrutura do banco.
- `sql/seed.sql`: inserção de dados demonstrativos.

## 5. Funcionalidades implementadas

### Livros

- Cadastro de livros.
- Busca por identificador.
- Listagem de livros.
- Atualização de dados.
- Exclusão.
- Associação de autores.
- Recuperação dos autores associados.
- Carregamento dos autores durante a listagem, utilizando consulta em lote.

### Autores

- Cadastro de autores.
- Busca por identificador.
- Listagem de autores.
- Exclusão, respeitando as restrições de integridade referencial.

### Recursos de persistência

- Reconstrução de registros em objetos PHP.
- Relacionamento muitos-para-muitos.
- Prepared statements por PDO.
- Transações para cadastro de livro e associação de autores.
- Rollback em caso de falha.
- Prevenção de autores duplicados na coleção de livros.
- Consultas parametrizadas e identificadores SQL definidos internamente.
- Utilização de uma interface comum para demonstrar polimorfismo entre repositórios.

## 6. Como executar o projeto

### Pré-requisitos

- Docker Desktop instalado e em execução.
- Docker Compose disponível no terminal.

### 6.1 Configurar variáveis de ambiente

Copie o arquivo `.env.example` para `.env` e configure as credenciais locais:

```dotenv
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=biblioteca_orm
DB_USERNAME=biblioteca_user
DB_PASSWORD=sua_senha_local
DB_ROOT_PASSWORD=sua_senha_root_local
```

As credenciais são utilizadas exclusivamente no ambiente local. O arquivo `.env` não deve ser versionado.

### 6.2 Iniciar os containers

Na raiz do projeto, execute:

```bash
docker compose up -d --build
```

### 6.3 Gerar o autoload

```bash
docker compose exec app composer dump-autoload
```

### 6.4 Criar a estrutura do banco

Acesse o MySQL pelo phpMyAdmin ou pelo terminal e execute o conteúdo de:

```text
sql/schema.sql
```

O banco `biblioteca_orm` é criado pelo Docker Compose na inicialização.

### 6.5 Acessar os serviços

| Serviço | Endereço |
|---|---|
| Aplicação PHP | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| MySQL | localhost:3307 |

### 6.6 Inserir dados iniciais

Após criar a estrutura do banco, execute o script:

```text
sql/seed.sql
```

No PowerShell, utilize:

```powershell
Get-Content .\sql\seed.sql -Raw | docker compose exec -T mysql sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'
```

O script insere dados demonstrativos de livros e autores, incluindo um relacionamento entre dois autores e um livro.

## 7. Executar os testes

Os testes estão organizados na pasta `tests/`.

Para executar um teste:

```bash
docker compose exec app php tests/nome_do_teste.php
```

### 7.1 Relação dos testes

| Arquivo | Objetivo |
|---|---|
| `teste_conexao.php` | Verificar a conexão PDO com o banco. |
| `teste_entidade.php` | Verificar construção, validação e reconstrução de entidades. |
| `teste_relacionamento.php` | Verificar a coleção de autores em um livro. |
| `teste_autor_repository.php` | Verificar operações do repositório de autores. |
| `teste_livro_repository.php` | Verificar cadastro, busca e listagem de livros. |
| `teste_atualizacao_livro.php` | Verificar atualização de dados de um livro. |
| `teste_exclusao_livro.php` | Verificar exclusão de livro. |
| `teste_livro_autores.php` | Verificar cadastro de livro com associação de autores. |
| `teste_hidratacao_livro.php` | Verificar recuperação de livro e autores a partir do banco. |
| `teste_listagem_com_autores.php` | Verificar listagem de livros com autores carregados em lote. |
| `teste_duplicidade.php` | Verificar prevenção de autores duplicados na coleção. |
| `teste_polimorfismo.php` | Verificar utilização dos repositórios por meio da interface comum. |
| `teste_rollback.php` | Provocar falha de integridade referencial e verificar rollback. |

Exemplos de execução:

```bash
docker compose exec app php tests/teste_conexao.php

docker compose exec app php tests/teste_hidratacao_livro.php

docker compose exec app php tests/teste_polimorfismo.php

docker compose exec app php tests/teste_rollback.php
```

Os testes demonstram as operações de persistência, reconstrução de entidades, relacionamento N:N, polimorfismo e rollback transacional.

**Observação:** alguns testes realizam inserções, atualizações e exclusões no banco. Eles devem ser executados considerando os dados existentes.

## 8. Segurança e integridade

O projeto utiliza prepared statements por PDO para parametrização dos valores enviados às consultas SQL.

Os identificadores de tabelas e colunas são definidos internamente no código, não sendo recebidos como valores arbitrários do consumidor.

As operações que envolvem o cadastro de um livro e seus relacionamentos são realizadas em transação, garantindo que uma falha na associação dos autores provoque o rollback da operação.

A integridade dos relacionamentos também é protegida pelas chaves estrangeiras e restrições definidas no MySQL.

As credenciais locais são armazenadas em `.env`, arquivo que não deve ser versionado. O repositório disponibiliza `.env.example` como modelo de configuração.

## 9. Limitações e possíveis melhorias

Como implementação acadêmica, o BibliotecaORM possui escopo reduzido e não pretende substituir ORMs completos utilizados em aplicações de produção.

A operação `salvarComAutores()` realiza o cadastro de um novo livro e seus vínculos com autores existentes. A sincronização dos autores de um livro já cadastrado não está implementada.

O projeto também não possui uma interface completa de gerenciamento pela aplicação web. As operações são demonstradas por meio dos repositórios e dos scripts de teste.

Possíveis evoluções incluem:

- Implementação de operações adicionais para outras entidades.
- Sincronização de relacionamentos existentes.
- Ampliação dos testes automatizados.
- Implementação de novas entidades e repositórios.
- Criação de uma interface de gerenciamento.

## 10. Autor

Projeto desenvolvido como avaliação prática individual da disciplina de Programação II.

**UNOESC — Universidade do Oeste de Santa Catarina**

**Professor:** Leandro Otavio Cordova Vieira