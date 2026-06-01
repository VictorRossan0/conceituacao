# Backend — Conceituação API

Backend da aplicação **Conceituação**, desenvolvido em Laravel para gerenciamento de usuários, perfis e associações entre usuários e perfis.

Esta API faz parte de uma solução composta por:

* Backend Laravel
* Frontend Vue 3
* Banco MySQL
* Ambiente Docker Compose

## Tecnologias

* PHP 8.3
* Laravel 12
* Laravel Sanctum
* MySQL 8
* PHPUnit
* Docker

## Principais recursos

* Autenticação com Laravel Sanctum
* Registro, login, logout e consulta do usuário autenticado
* CRUD de usuários
* CRUD de perfis
* Relacionamento muitos-para-muitos entre usuários e perfis
* Associação e desassociação de perfis
* Controle de acesso por perfil `Administrador`
* Soft Delete em usuários e perfis
* Seeders para criação de perfil e usuário administrador
* Testes automatizados de API

## Usuário padrão

Após rodar migrations e seeders, o sistema cria um usuário administrador para testes:

```text
E-mail: admin@example.com
Senha: password
```

## Execução via Docker

Os comandos principais devem ser executados a partir da raiz do projeto, onde está localizado o `docker-compose.yml`.

Subir os containers:

```powershell
docker compose up -d
```

Rodar migrations e seeders:

```powershell
.\scripts\fresh-db.ps1
```

Executar comandos Artisan:

```powershell
.\scripts\artisan.ps1 route:list
.\scripts\artisan.ps1 migrate:status
.\scripts\artisan.ps1 test --env=testing
```

Entrar no container do backend:

```powershell
.\scripts\backend-shell.ps1
```

Dentro do container, é possível executar comandos Laravel normalmente:

```bash
php artisan route:list
php artisan migrate:status
php artisan test --env=testing
```

## Testes automatizados

Os testes cobrem os principais fluxos da API:

* Autenticação
* Controle de acesso
* Gerenciamento de usuários
* Gerenciamento de perfis
* Associação entre usuários e perfis
* Soft Delete

Executar a suíte de testes:

```powershell
.\scripts\artisan.ps1 test --env=testing
```

## Collection Postman

A collection Postman da API está disponível na raiz do projeto:

```text
collection_postman/Conceituacao API.postman_collection.json
```

Ela pode ser importada no Postman para validação manual dos endpoints.

## Observação

Este README descreve apenas o backend. A documentação completa da entrega será concentrada no arquivo `README-novo.md`, localizado na raiz do projeto.
