<?php

namespace App\Controller;

use App\Entity\Medico;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController
{

    /**
     * 
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request) : Response 
    {
        $corpoDaRequesicao = $request->getContent();
        $dadoEmJson = json_decode($corpoDaRequesicao);
        
        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        return new JsonResponse($medico);
    }

}