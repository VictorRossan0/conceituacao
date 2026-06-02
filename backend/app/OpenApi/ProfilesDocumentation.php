<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

class ProfilesDocumentation
{
    #[OA\Get(
        path: '/profiles',
        summary: 'Listar perfis',
        description: 'Lista perfis cadastrados. Suporta filtros opcionais para incluir registros excluídos via Soft Delete. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'with_trashed',
                in: 'query',
                required: false,
                description: 'Quando true, retorna perfis ativos e excluídos logicamente.',
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'only_trashed',
                in: 'query',
                required: false,
                description: 'Quando true, retorna apenas perfis excluídos logicamente.',
                schema: new OA\Schema(type: 'boolean')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de perfis retornada com sucesso.',
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
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
        ]
    )]
    public function index(): void
    {
    }

    #[OA\Post(
        path: '/profiles',
        summary: 'Criar perfil',
        description: 'Cria um novo perfil de acesso. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['Profiles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Operador'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Perfil criado com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Perfil criado com sucesso.'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 422, description: 'Erro de validação.'),
        ]
    )]
    public function store(): void
    {
    }

    #[OA\Get(
        path: '/profiles/{profile}',
        summary: 'Visualizar perfil',
        description: 'Retorna os dados de um perfil ativo específico. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'profile',
                in: 'path',
                required: true,
                description: 'ID do perfil.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil encontrado.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 404, description: 'Perfil não encontrado.'),
        ]
    )]
    public function show(): void
    {
    }

    #[OA\Put(
        path: '/profiles/{profile}',
        summary: 'Atualizar perfil',
        description: 'Atualiza os dados de um perfil existente. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'profile',
                in: 'path',
                required: true,
                description: 'ID do perfil.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Operador Atualizado'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil atualizado com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Perfil atualizado com sucesso.'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 404, description: 'Perfil não encontrado.'),
            new OA\Response(response: 422, description: 'Erro de validação.'),
        ]
    )]
    public function update(): void
    {
    }

    #[OA\Delete(
        path: '/profiles/{profile}',
        summary: 'Excluir perfil',
        description: 'Exclui logicamente um perfil usando Soft Delete. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'profile',
                in: 'path',
                required: true,
                description: 'ID do perfil.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil excluído com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Perfil excluído com sucesso.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 404, description: 'Perfil não encontrado.'),
        ]
    )]
    public function destroy(): void
    {
    }
}
