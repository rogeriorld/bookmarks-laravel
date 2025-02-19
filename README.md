# Sobre o Projeto

Este projeto visa fornecer uma solução para armazenar favoritos de forma criptografada em um banco de dados, permitindo o acesso de qualquer lugar, desde que o usuário possua as credenciais adequadas. A ideia é utilizar o próprio repositório do GitHub para armazenar o banco de dados SQLite, proporcionando assim uma solução simples e acessível.

## Tecnologias

- Laravel: Um framework PHP que facilita o desenvolvimento de aplicativos web.
- SQLite: Um sistema de gerenciamento de banco de dados SQL embutido.
- Git: Um sistema de controle de versão distribuído, ideal para armazenar o repositório do projeto no GitHub.

## Funcionalidades

- Armazenamento Criptografado: Os favoritos serão armazenados de forma criptografada no banco de dados, garantindo a segurança das informações.
- Acesso de Qualquer Lugar: Com as credenciais adequadas, o usuário poderá acessar seus favoritos de qualquer lugar.
- Repositório no GitHub: O banco de dados SQLite será armazenado no próprio repositório do GitHub, garantindo a disponibilidade e facilitando o versionamento do projeto.
- Integração com Laravel: O desenvolvimento será realizado utilizando o framework Laravel, o que proporciona uma estrutura robusta e moderna para a construção da aplicação.

## Instalação e Uso

1. Clone o repositório do GitHub para sua máquina local.
2. Certifique-se de ter o PHP e o Composer instalados em seu sistema.
3. Instale as dependências do projeto executando o comando `composer install`.
4. Configure o arquivo `.env` com as informações do banco de dados.
5. Execute as migrações do banco de dados com o comando `php artisan migrate`.
6. Inicie o servidor local com o comando `php artisan serve`.
7. Acesse a aplicação em seu navegador e comece a utilizar.

## Licença

---

Este projeto está licenciado sob a MIT License.
