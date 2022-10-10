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
        $this->checkAllProperties($dadoEmJson);
        $especialidade_id = $dadoEmJson->especialidade_id;
        $especialidade = $this->especialidadeRepository->find($especialidade_id);
        $medico = new Medico();
        $medico
            ->setNome($dadoEmJson->nome)
            ->setCrm($dadoEmJson->crm)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    /**
     * @param mixed $dadoEmJson
     * @return void
     * @throws EntityFactoryException
     */
    private function checkAllProperties(mixed $dadoEmJson): void
    {
        if (!property_exists($dadoEmJson, 'nome')) {
            throw new EntityFactoryException('Médico precisa de nome');
        }
        if (!property_exists($dadoEmJson, 'crm')) {
            throw new EntityFactoryException('Médico precisa de crm');
        }
        if (!property_exists($dadoEmJson, 'especialidade_id')) {
            throw new EntityFactoryException('Médico precisa de especialidade');
        }
    }

}