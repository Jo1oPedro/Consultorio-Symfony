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
        if(!property_exists($dadoEmJson, 'nome') ||
            !property_exists($dadoEmJson, 'crm') ||
            !property_exists($dadoEmJson, 'especialidadeId')
        ) {
            throw new EntityFactoryException('Médico precisa de nome, CRM e especialidade');
        }
        $especialidade_id = $dadoEmJson->especialidade_id;
        $especialidade = $this->especialidadeRepository->find($especialidade_id);
        $medico = new Medico();
        $medico
            ->setNome($dadoEmJson->nome)
            ->setCrm($dadoEmJson->crm)
            ->setEspecialidade($especialidade);

        return $medico;
    }

}