# Conceituação — Gerenciamento de Usuários e Perfis

Projeto desenvolvido como teste técnico para criação de uma aplicação full stack com **backend Laravel**, **frontend Vue 3** e ambiente com **Docker Compose**.

A aplicação permite autenticação de usuários, gerenciamento de usuários, gerenciamento de perfis, associação entre usuários e perfis e controle de acesso baseado no perfil `Administrador`.

---

## 1. Descrição do Projeto

O objetivo do projeto é disponibilizar uma aplicação para gerenciamento de usuários e seus respectivos perfis de acesso.

A solução é composta por duas aplicações:

* **Backend Laravel**: responsável pela API REST, autenticação, regras de negócio, persistência e testes automatizados.
* **Frontend Vue 3**: responsável pela interface de usuário e consumo da API Laravel.

Também foi configurado um ambiente Docker para facilitar a execução do backend, frontend e banco de dados MySQL.

---

## 2. Tecnologias Utilizadas

### Stack do Backend

* PHP 8.3
* Laravel 12
* Laravel Sanctum
* MySQL 8
* PHPUnit
* L5-Swagger / OpenAPI
* Docker

### Stack do Frontend

* Vue 3
* Vite
* Vue Router
* Axios
* Bootstrap
* Docker

### Infraestrutura e Ferramentas

* Docker Compose
* MySQL
* Postman
* Scripts auxiliares PowerShell

---

## 3. Funcionalidades Implementadas

### Autenticação

* Registro de usuário
* Login
* Logout
* Consulta do usuário autenticado
* Autenticação via token Bearer com Laravel Sanctum

### Usuários

* Listar usuários
* Criar usuários
* Visualizar usuário
* Editar usuários
* Excluir usuários com Soft Delete
* Listar usuários ativos
* Listar usuários ativos + excluídos
* Listar apenas usuários excluídos

### Perfis

* Listar perfis
* Criar perfis
* Visualizar perfil
* Editar perfis
* Excluir perfis com Soft Delete
* Listar perfis ativos
* Listar perfis ativos + excluídos
* Listar apenas perfis excluídos

### Associação Usuário-Perfis

* Associar perfil a usuário
* Desassociar perfil de usuário
* Listar perfis de um usuário
* Prevenção de associação duplicada
* Bloqueio de associação com perfil excluído logicamente

### Controle de Acesso

* Apenas usuários autenticados acessam o sistema.
* Apenas usuários com perfil `Administrador` podem gerenciar perfis e associações.
* Usuários comuns acessam apenas o dashboard no frontend.

---

## 4. Arquitetura Geral

A estrutura do projeto foi organizada separando backend, frontend, scripts auxiliares e collection Postman.

```text
conceituacao/
├── backend/
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── tests/
│   └── Dockerfile
├── frontend/
│   ├── src/
│   └── Dockerfile
├── collection_postman/
│   └── Conceituacao API.postman_collection.json
├── scripts/
│   ├── artisan.ps1
│   ├── backend-shell.ps1
│   └── fresh-db.ps1
├── docker-compose.yml
└── README-novo.md
```

### Organização do Backend

O backend foi estruturado utilizando recursos nativos do Laravel:

* Controllers para entrada das requisições
* Form Requests para validação
* Models para entidades e relacionamentos
* Middleware para controle de acesso
* Seeders para dados iniciais
* Tests para validação automatizada
* OpenApi para documentação Swagger

### Organização do Frontend

O frontend foi estruturado em camadas simples:

* `api/`: configuração centralizada do Axios
* `services/`: comunicação com endpoints da API
* `stores/`: estado reativo de autenticação
* `views/`: páginas principais da aplicação
* `components/`: componentes reutilizáveis
* `router/`: rotas e proteção de navegação

---

## 5. Etapas de Implementação

O projeto foi desenvolvido de forma incremental, separando a entrega em etapas para facilitar organização, validação e manutenção.

### Etapa 1 — Análise e Estrutura Arquitetural

* Análise dos requisitos do desafio.
* Organização inicial do projeto.
* Estruturação dos diretórios `backend` e `frontend`.
* Configuração do Docker e Docker Compose.
* Validação dos containers da aplicação, frontend e banco de dados.

### Etapa 2 — Backend Laravel Funcional

* Implementação da autenticação com Laravel Sanctum.
* CRUD de usuários.
* CRUD de perfis.
* Relacionamento muitos-para-muitos entre usuários e perfis.
* Associação e desassociação de perfis.
* Controle de acesso por perfil `Administrador`.
* Soft Delete para preservação lógica de dados.
* Seeders para criação do perfil `Administrador` e usuário de teste.
* Collection Postman para validação manual da API.
* Scripts auxiliares para execução de comandos no ambiente Docker.

### Etapa 3 — Frontend Vue Funcional

* Interface Vue para consumo da API Laravel.
* Login e armazenamento do token de autenticação.
* Dashboard com dados do usuário autenticado.
* Telas para gerenciamento de usuários.
* Telas para gerenciamento de perfis.
* Funcionalidade de associação e desassociação de perfis.
* Controle visual de acesso conforme perfil do usuário.
* Exibição de registros ativos e excluídos logicamente.

### Etapa 4 — Testes Automatizados Laravel

* Testes automatizados para autenticação.
* Testes de controle de acesso.
* Testes dos fluxos de usuários.
* Testes dos fluxos de perfis.
* Testes de associação entre usuários e perfis.
* Testes de Soft Delete.

### Etapa 5 — Swagger/API Docs

* Instalação e configuração do L5-Swagger.
* Documentação OpenAPI das rotas principais.
* Documentação dos fluxos de autenticação, usuários, perfis e associações.
* Disponibilização da interface Swagger UI.

### Etapa 5.5 — Refinamento Docker

* Criação de arquivos `.dockerignore`.
* Ajuste de cache no Dockerfile do backend.
* Uso de `npm ci` no Dockerfile do frontend.
* Healthcheck para backend, frontend e MySQL.
* Validação do ambiente com `docker compose build` e `docker compose up`.

### Etapa 6 — README-novo e Documentação de Execução

* Criação da documentação principal da entrega.
* Instruções para subir o ambiente.
* Instruções para rodar migrations e seeders.
* Usuário e senha de teste.
* Orientações para Postman, Swagger e testes automatizados.

### Etapa 7 — Revisão Final e Entrega

* Revisão completa do fluxo backend/frontend.
* Validação do Docker Compose.
* Execução dos testes automatizados.
* Revisão da documentação.
* Conferência final do repositório para entrega.

---

## 6. Como Executar o Projeto

### Pré-requisitos

Antes de executar o projeto, é necessário ter instalado:

* Docker
* Docker Compose
* Git

---

## 7. Subindo o Ambiente com Docker

Na raiz do projeto, execute:

```powershell
docker compose build
```

Depois suba os containers:

```powershell
docker compose up -d
```

Verifique se os containers estão em execução:

```powershell
docker ps
```

A aplicação utiliza os seguintes serviços:

```text
MySQL:    localhost:3306
Backend:  http://localhost:8000
Frontend: http://localhost:5173
```

---

## 8. Migrations e Seeders

Após subir os containers, rode as migrations e seeders:

```powershell
.\scripts\fresh-db.ps1
```

Esse comando executa:

```powershell
docker compose exec backend php artisan migrate:fresh --seed
```

Ele recria o banco de dados e popula os dados iniciais.

---

## 9. Usuário de Teste

Após executar os seeders, o sistema cria um usuário administrador padrão:

```text
E-mail: admin@example.com
Senha: password
```

Esse usuário possui o perfil:

```text
Administrador
```

---

## 10. Acessando a Aplicação

### URL do Frontend

```text
http://localhost:5173
```

### URL do Backend

```text
http://localhost:8000
```

### Healthcheck Laravel

```text
http://localhost:8000/up
```

### Swagger/API Docs

```text
http://localhost:8000/api/documentation
```

---

## 11. Scripts Auxiliares

O projeto possui scripts para facilitar o uso do Laravel dentro do Docker.

### Executar comandos Artisan

```powershell
.\scripts\artisan.ps1 route:list
```

Exemplos:

```powershell
.\scripts\artisan.ps1 migrate:status
.\scripts\artisan.ps1 test --env=testing
.\scripts\artisan.ps1 l5-swagger:generate
```

### Entrar no container do backend

```powershell
.\scripts\backend-shell.ps1
```

Dentro do container, é possível executar comandos Laravel diretamente:

```bash
php artisan route:list
php artisan test --env=testing
```

### Resetar banco com seed

```powershell
.\scripts\fresh-db.ps1
```

---

## 12. Testes Automatizados

A suíte de testes foi criada no backend Laravel utilizando PHPUnit.

Os testes cobrem:

* Autenticação
* Controle de acesso
* CRUD de usuários
* CRUD de perfis
* Associação usuário-perfis
* Soft Delete

Para executar os testes:

```powershell
.\scripts\artisan.ps1 test --env=testing
```

Resultado validado:

```text
29 passed
106 assertions
```

---

## 13. Collection Postman

A collection do Postman está disponível em:

```text
collection_postman/Conceituacao API.postman_collection.json
```

Para utilizar:

1. Abra o Postman.
2. Clique em `Import`.
3. Selecione o arquivo da collection.
4. Configure as variáveis necessárias, como `base_url` e `token`.
5. Execute os fluxos de autenticação, usuários, perfis e associações.

Sugestão de `base_url`:

```text
http://localhost:8000/api
```

---

## 14. Documentação Swagger

A documentação Swagger foi criada utilizando L5-Swagger/OpenAPI.

Acesse:

```text
http://localhost:8000/api/documentation
```

A documentação contempla os grupos:

* Auth
* Users
* Profiles
* User Profiles

Para regenerar a documentação:

```powershell
.\scripts\artisan.ps1 l5-swagger:generate
```

---

## 15. Regras de Negócio

### Perfil Administrador

O sistema possui um perfil principal:

```text
Administrador
```

Usuários com esse perfil podem:

* Gerenciar perfis
* Associar perfis a usuários
* Desassociar perfis de usuários

Usuários sem esse perfil acessam apenas o dashboard no frontend.

### Soft Delete

Usuários e perfis utilizam Soft Delete.

Isso significa que, ao excluir um registro, ele não é removido fisicamente do banco. O campo `deleted_at` é preenchido e o registro deixa de aparecer nas listagens padrão.

As listagens suportam filtros:

```text
?with_trashed=true
?only_trashed=true
```

Exemplos:

```text
GET /api/users?with_trashed=true
GET /api/users?only_trashed=true

GET /api/profiles?with_trashed=true
GET /api/profiles?only_trashed=true
```

---

## 16. Endpoints Principais

### Auth

```text
POST /api/register
POST /api/login
GET  /api/me
POST /api/logout
```

### Users

```text
GET    /api/users
POST   /api/users
GET    /api/users/{user}
PUT    /api/users/{user}
DELETE /api/users/{user}
```

### Profiles

```text
GET    /api/profiles
POST   /api/profiles
GET    /api/profiles/{profile}
PUT    /api/profiles/{profile}
DELETE /api/profiles/{profile}
```

### User Profiles

```text
GET    /api/users/{user}/profiles
POST   /api/users/{user}/profiles
DELETE /api/users/{user}/profiles/{profile}
```

---

## 17. Observações Técnicas

### Observações sobre Autenticação

A autenticação utiliza Laravel Sanctum com token Bearer.

Após login, o token deve ser enviado nas requisições protegidas:

```text
Authorization: Bearer {token}
```

### Observações sobre Controle de Acesso

O controle de acesso administrativo foi implementado com middleware próprio, validando se o usuário autenticado possui o perfil `Administrador`.

### Observações sobre Docker

O projeto utiliza Docker Compose com três serviços principais:

* `mysql`
* `backend`
* `frontend`

Também foram adicionados healthchecks para facilitar a validação da saúde dos containers.

### Observações sobre Frontend

O frontend consome a API Laravel através de Axios. A URL base está configurada em:

```text
frontend/src/api/api.js
```

Por padrão:

```text
http://localhost:8000/api
```

---

## 18. Comandos Úteis

Subir containers:

```powershell
docker compose up -d
```

Parar containers:

```powershell
docker compose down
```

Rebuild completo:

```powershell
docker compose build
```

Rodar migrations e seeders:

```powershell
.\scripts\fresh-db.ps1
```

Rodar testes:

```powershell
.\scripts\artisan.ps1 test --env=testing
```

Gerar Swagger:

```powershell
.\scripts\artisan.ps1 l5-swagger:generate
```

Ver rotas:

```powershell
.\scripts\artisan.ps1 route:list
```

---

## 19. Considerações Finais

O projeto foi desenvolvido com foco em clareza, organização e validação prática.

Além dos requisitos principais, foram adicionados diferenciais como:

* Testes automatizados
* Swagger/API Docs
* Collection Postman
* Soft Delete demonstrável
* Scripts auxiliares
* Documentação interna no código
* Refinamento Docker com healthchecks

Esses pontos foram implementados para facilitar manutenção, validação técnica e execução local do projeto.
