<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class EstratorDeDadosDoRequest
{

    //private static mixed $informacoes;

    /**
     * @param Request $request
     * @return mixed
     */
    private function buscaDadosRequest(Request $request): Mixed
    {
        $queryString = $request->query->all();

        $dadosOrdenacao = array_key_exists('sort', $queryString)
            ? $queryString['sort']
            : [];
        unset($queryString['sort']);

        $paginaAtual = array_key_exists('page', $queryString)
            ? $queryString['page']
            : 1;

        unset($queryString['page']);

        $itensPorPagina = array_key_exists('itensPorPagina', $queryString)
            ? $queryString['itensPorPagina']
            : 5;

        unset($queryString['itensPorPagina']);

        /*self::$informacoes =*/ return [
            $queryString,
            $dadosOrdenacao,
            $paginaAtual,
            $itensPorPagina,
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buscaDadosFiltro(Request $request): Mixed
    {
        [$informacoesDeOrdenacao, ] = $this->buscaDadosRequest($request); //self::$informacoes;
        // em cima é igual utilizar list($informacoesDeOrdenacao);
        return $informacoesDeOrdenacao;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buscaDadosOrdenacao(Request $request): Mixed
    {
        [, $informacoesDeFiltro] = $this->buscaDadosRequest($request); //self::$informacoes;
        // em cima é igual utilizar list(, $informacoesDeOrdenacao);
        return $informacoesDeFiltro;
    }

    public function buscaDadosPaginacao(Request $request): Mixed
    {
        [, , $paginaAtual, $itensPorPagina] = $this->buscaDadosRequest($request);
        return [$paginaAtual, $itensPorPagina];
    }
}