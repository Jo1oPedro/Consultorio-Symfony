<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     */
    public int $id;

    /**
     * @ORM\Column
     */
    public int $crm;

    /**
     * @ORM\Column
     */
    public string $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function getEspecialidade(): ?especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

}