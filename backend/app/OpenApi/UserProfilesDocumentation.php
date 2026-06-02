<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

class UserProfilesDocumentation
{
    #[OA\Get(
        path: '/users/{user}/profiles',
        summary: 'Listar perfis de um usuário',
        description: 'Lista os perfis associados a um usuário específico. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['User Profiles'],
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
            new OA\Response(
                response: 200,
                description: 'Perfis do usuário retornados com sucesso.',
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
            new OA\Response(response: 404, description: 'Usuário não encontrado.'),
        ]
    )]
    public function index(): void
    {
    }

    #[OA\Post(
        path: '/users/{user}/profiles',
        summary: 'Associar perfil ao usuário',
        description: 'Associa um perfil ativo a um usuário específico. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['User Profiles'],
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
                required: ['profile_id'],
                properties: [
                    new OA\Property(
                        property: 'profile_id',
                        type: 'integer',
                        example: 2,
                        description: 'ID do perfil ativo que será associado ao usuário.'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil associado ao usuário com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Perfil associado ao usuário com sucesso.'
                        ),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 404, description: 'Usuário, perfil não encontrado ou perfil excluído.'),
            new OA\Response(response: 422, description: 'Erro de validação.'),
        ]
    )]
    public function attach(): void
    {
    }

    #[OA\Delete(
        path: '/users/{user}/profiles/{profile}',
        summary: 'Desassociar perfil do usuário',
        description: 'Remove a associação entre um usuário e um perfil. O usuário e o perfil não são excluídos; apenas o vínculo na tabela pivot é removido. Esta rota exige autenticação e perfil Administrador.',
        security: [['bearerAuth' => []]],
        tags: ['User Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'ID do usuário.',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'profile',
                in: 'path',
                required: true,
                description: 'ID do perfil.',
                schema: new OA\Schema(type: 'integer', example: 2)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil desassociado do usuário com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Perfil desassociado do usuário com sucesso.'
                        ),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado.'),
            new OA\Response(response: 403, description: 'Acesso permitido apenas para administradores.'),
            new OA\Response(response: 404, description: 'Usuário ou perfil não encontrado.'),
        ]
    )]
    public function detach(): void
    {
    }
}
