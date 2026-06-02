<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

class AuthDocumentation
{
    #[OA\Post(
        path: '/register',
        summary: 'Registrar usuário',
        description: 'Cria um novo usuário e retorna um token de autenticação.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Usuário Teste'),
                    new OA\Property(property: 'email', type: 'string', example: 'usuario.teste@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuário registrado com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário registrado com sucesso.'),
                        new OA\Property(property: 'token', type: 'string', example: '1|example-token'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação.'
            ),
        ]
    )]
    public function register(): void
    {
    }

    #[OA\Post(
        path: '/login',
        summary: 'Login',
        description: 'Autentica um usuário e retorna um token Bearer do Laravel Sanctum.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login realizado com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Login realizado com sucesso.'),
                        new OA\Property(property: 'token', type: 'string', example: '1|example-token'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Credenciais inválidas.'
            ),
        ]
    )]
    public function login(): void
    {
    }

    #[OA\Get(
        path: '/me',
        summary: 'Usuário autenticado',
        description: 'Retorna os dados do usuário autenticado com seus perfis.',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do usuário autenticado.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Não autenticado.'
            ),
        ]
    )]
    public function me(): void
    {
    }

    #[OA\Post(
        path: '/logout',
        summary: 'Logout',
        description: 'Revoga o token atual do usuário autenticado.',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout realizado com sucesso.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logout realizado com sucesso.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Não autenticado.'
            ),
        ]
    )]
    public function logout(): void
    {
    }
}
