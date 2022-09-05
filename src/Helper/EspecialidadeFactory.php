<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory implements EntidadeFactory
{

    /**
     * @param string $json
     * @return Especialidade
     */
    public function criaEntidade(string $json): Especialidade
    {
        $dadosEmJson = json_decode($json);
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        return $especialidade;
    }

}