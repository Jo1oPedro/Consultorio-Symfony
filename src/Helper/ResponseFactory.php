<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{

    private bool $sucesso;
    private int|null $paginaAtual;
    private int|null $itensPorPagina;
    private $conteudoResposta;
    private int $statusResposta;

    public function __construct(
        bool $sucesso = true,
        int|null $paginaAtual = null,
        int|null $itensPorPagina = null,
        mixed $conteudoResposta = [],
        int $statusResposta = Response::HTTP_OK,
    )
    {
        $this->sucesso = $sucesso;
        $this->paginaAtual = $paginaAtual;
        $this->itensPorPagina = $itensPorPagina;
        $this->conteudoResposta = $conteudoResposta;
        $this->statusResposta = $statusResposta;
    }

    public function getResponse(): JsonResponse
    {
        $conteudoResposta = [
            'sucesso' => $this->sucesso,
            'paginaAtual' => $this->paginaAtual,
            'itensPorPagina' => $this->itensPorPagina,
            'counteudoResposta' => $this->conteudoResposta,
        ];
        if(is_null($this->paginaAtual)) {
            unset($conteudoResposta['paginaAtual']);
            unset($conteudoResposta['itensPorPagina']);
        }

        return new JsonResponse($conteudoResposta, $this->statusResposta);
    }

}