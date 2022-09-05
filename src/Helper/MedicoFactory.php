<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory implements EntidadeFactory
{

    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {

        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @param string $json
     * @return Medico
     */
    public function criaEntidade(string $json): Medico
    {
        $dadoEmJson = json_decode($json);
        $especialidadeId = $dadoEmJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);
        $medico = new Medico();
        $medico
            ->setNome($dadoEmJson->nome)
            ->setCrm($dadoEmJson->crm)
            ->setEspecialidade($especialidade);

        return $medico;
    }

}