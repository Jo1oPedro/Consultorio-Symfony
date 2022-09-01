<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private ManagerRegistry $doctrine;
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine, EspecialidadeRepository $especialidadeRepository)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
        //$this->>doctrine->getRepository(Especialidade::class);
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @Route("especialidades", methods={"GET"})
     * @return JsonResponse
     */
    public function index()
    {
        $especialidades = $this->especialidadeRepository->findAll();
        return new JsonResponse(["especialidade" => $especialidades]);
    }

    #[Route('/especialidades', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return $this->json([
            $especialidade,
        ]);
    }

    /**
     * @Route("especialidades/{id}, methods={PUT})
     * @param Request $request
     * @return void
     */
    public function update(Especialidade $especialidade, Request $request)
    {
        dump($especialidade);
        exit();
        //$especialidade = $this->especialidadeRepository($request->get("especialidadeId"));

        //$especialidade->descricao = $requetst

        dump($especialidade);
        exit();
    }
}
