# Frontend — Conceituação

Frontend da aplicação **Conceituação**, desenvolvido em Vue 3 para consumir a API Laravel do projeto.

A interface permite autenticação, visualização do dashboard e gerenciamento de usuários, perfis e associações entre usuários e perfis.

## Tecnologias

* Vue 3
* Vite
* Vue Router
* Axios
* Bootstrap
* Docker

## Principais recursos

* Tela de login
* Armazenamento de token no `localStorage`
* Consumo da API Laravel com Axios
* Navbar reativa conforme autenticação
* Dashboard com dados do usuário autenticado
* Controle visual por perfil `Administrador`
* Módulo de usuários
* Módulo de perfis
* Módulo de associação usuário-perfis
* Exibição de registros ativos e excluídos logicamente via Soft Delete
* Interface responsiva com Bootstrap

## Usuário padrão

Após rodar o backend com migrations e seeders, é possível acessar o sistema com:

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

Acessar a aplicação:

```text
http://localhost:5173
```

## Desenvolvimento local

Instalar dependências:

```powershell
npm install
```

Rodar o servidor de desenvolvimento:

```powershell
npm run dev
```

Rodar lint:

```powershell
npm run lint
```

Gerar build:

```powershell
npm run build
```

## Configuração da API

O frontend consome a API Laravel usando a seguinte URL padrão:

```text
http://localhost:8000/api
```

Essa configuração está centralizada em:

```text
src/api/api.js
```

Caso necessário, pode ser sobrescrita por variável de ambiente:

```text
VITE_API_URL
```

## Observação

Este README descreve apenas o frontend. A documentação completa da entrega será concentrada no arquivo `README-novo.md`, localizado na raiz do projeto.
