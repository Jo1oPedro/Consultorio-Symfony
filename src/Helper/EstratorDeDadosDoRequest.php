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
        $informacoesDeOrdenacao = $request->query->get('sort');
        $informacoesDeFiltro = $request->query->all();
        unset($informacoesDeFiltro['sort']);

        /*self::$informacoes =*/ return [
            $informacoesDeOrdenacao,
            $informacoesDeFiltro
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buscaDadosOrdenacao(Request $request): Mixed
    {
        [$informacoesDeOrdenacao] = $this->buscaDadosRequest($request); //self::$informacoes;
        // em cima é igual utilizar list($informacoesDeOrdenacao);
        return $informacoesDeOrdenacao;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buscaDadosFiltro(Request $request): Mixed
    {
        [, $informacoesDeFiltro] = $this->buscaDadosRequest($request); //self::$informacoes;
        // em cima é igual utilizar list(, $informacoesDeOrdenacao);
        return $informacoesDeFiltro;
    }
}