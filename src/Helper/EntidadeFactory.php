<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

interface EntidadeFactory
{
    public function criaEntidade(string $json): \JsonSerializable;
}