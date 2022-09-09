<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EspecialidadeRepository")
 */
class Medico implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     */
    private int $id;

    /**
     * @ORM\Column
     */
    private int $crm;

    /**
     * @ORM\Column
     */
    private string $nome;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCrm(): ?int
    {
        return $this->crm;
    }

    /**
     * @param int $crm
     * @return Medico
     */
    public function setCrm(int $crm): Medico
    {
        $this->crm = $crm;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Medico
     */
    public function setNome(string $nome): Medico
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    /**
     * @param Especialidade|null $especialidade
     * @return $this
     */
    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;
        return $this;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            'id' => $this->getId(),
            'crm' => $this->getCrm(),
            'nome' => $this->getNome(),
            'dadosEspecialidade' => $this->getEspecialidade(),
            '_links' => [
                [
                    "rel" => "self",
                    "path" => "/medicos/" . self::getId(),
                ],
                [
                    "rel" => "especialidade" /*"rel" = relativo*/,
                    "path" => "/especialidades/{$this->getEspecialidade()->getId()}",
                ],
            ],
        ];
    }
}