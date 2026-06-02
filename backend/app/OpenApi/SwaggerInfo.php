<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Conceituação API',
    description: 'Documentação da API REST do projeto Conceituação, responsável por autenticação, usuários, perfis e associações entre usuários e perfis.'
)]
#[OA\Server(
    url: 'http://localhost:8000/api',
    description: 'Ambiente local via Docker'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Token Bearer gerado pelo Laravel Sanctum.'
)]
class SwaggerInfo
{
}
