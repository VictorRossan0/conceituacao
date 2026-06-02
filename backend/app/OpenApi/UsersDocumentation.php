<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

class UsersDocumentation
{
    #[OA\Get(
        path: '/users',
        summary: 'Listar usuários',
        description: 'Lista usuários cadastrados. Suporta filtros opcionais para incluir registros excluídos via Soft Delete.',
        security: [['bearerAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'with_trashed',
                in: 'query',
                required: false,
                description: 'Quando true, retorna usuários ativos e excluídos logicamente.',
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'only_trashed',
                in: 'query',
                required: false,
                description: 'Quando true, retorna apenas usuários excluídos logicamente.',
                schema: new OA\Schema(type: 'boolean')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuários retornada com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
        ]
    )]
    public function index(): void
    {
    }

    #[OA\Post(
        path: '/users',
        summary: 'Criar usuário',
        description: 'Cria um novo usuário no sistema.',
        security: [['bearerAuth' => []]],
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'João Teste'),
                    new OA\Property(property: 'email', type: 'string', example: 'joao.teste@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Usuário criado com sucesso.'),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 422, description: 'Erro de validação.'),
        ]
    )]
    public function store(): void
    {
    }

    #[OA\Get(
        path: '/users/{user}',
        summary: 'Visualizar usuário',
        description: 'Retorna os dados de um usuário ativo específico.',
        security: [['bearerAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'ID do usuário.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Usuário encontrado.'),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 404, description: 'Usuário não encontrado.'),
        ]
    )]
    public function show(): void
    {
    }

    #[OA\Put(
        path: '/users/{user}',
        summary: 'Atualizar usuário',
        description: 'Atualiza os dados de um usuário existente. A senha é opcional na atualização.',
        security: [['bearerAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'ID do usuário.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'João Teste Atualizado'),
                    new OA\Property(property: 'email', type: 'string', example: 'joao.teste@example.com'),
                    new OA\Property(property: 'password', type: 'string', nullable: true, example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Usuário atualizado com sucesso.'),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 404, description: 'Usuário não encontrado.'),
            new OA\Response(response: 422, description: 'Erro de validação.'),
        ]
    )]
    public function update(): void
    {
    }

    #[OA\Delete(
        path: '/users/{user}',
        summary: 'Excluir usuário',
        description: 'Exclui logicamente um usuário usando Soft Delete.',
        security: [['bearerAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'ID do usuário.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Usuário excluído com sucesso.'),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 404, description: 'Usuário não encontrado.'),
        ]
    )]
    public function destroy(): void
    {
    }
}
