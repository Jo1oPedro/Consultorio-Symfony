<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Entity\Medico;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EspecialidadesController extends BaseController
{

    //private EntityManagerInterface $entityManager;

    private ManagerRegistry $doctrine;
    //private EspecialidadeFactory $factory;

    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFactory $especialidadeFactory,
    ) {
        $this->doctrine = $doctrine;
        //$this->>doctrine->getRepository(Especialidade::class);
        parent::__construct($especialidadeRepository, $entityManager, $especialidadeFactory);
        //$this->factory = $especialidadeFactory;
    }

    /**
     * @Route("especialidades", methods={"GET"})
     * @return JsonResponse
     */
    /*public function index()
    {
        $especialidades = $this->especialidadeRepository->findAll();
        return new JsonResponse(["especialidade" => $especialidades]);
    }*/

    /**
     * @Route("/especialidades", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    //#[Route('/especialidades', methods: ['POST'])]
    /*public function store(Request $request): JsonResponse
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
    }*/

    /**
     * @Route("especialidades/{id}", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    /*public function update(?Especialidade $especialidade, Request $request) : Response
    {
        //$especialidade = $this->especialidadeRepository($request->get("especialidadeId"));
        if($especialidade) {
            $dadosJson = json_decode($request->getContent());
            $especialidade->setDescricao($dadosJson->descricao);
            $this->entityManager->flush();
            return new JsonResponse($especialidade);
        }

        return new Response('', Response::HTTP_NOT_FOUND);

    }*/

    /**
     * @Route("especialidades/{id}", methods={"GET"})
     * @param Especialidade $especialidade
     * @return Response
     */
    /*public function show(?Especialidade $especialidade)
    {
        if($especialidade) {
            return new JsonResponse($especialidade, 200);
        }
        return new Response('', Response::HTTP_NO_CONTENT);
    }*/

    /**
     * @Route("especialidades/{especialidade}", methods={"DELETE"})
     * @param Especialidade|null $especialidade
     * @return Response
     */
    /*public function destroy(?Especialidade $especialidade) {

        $medicosComEspecialidade = $this->doctrine->getRepository(Medico::class);
        $medicosComEspecialidade = $medicosComEspecialidade->findBy(["especialidade" => $especialidade->getId()]);

        foreach($medicosComEspecialidade as $medico) {
            $this->entityManager->remove($medico);
            $this->entityManager->flush();
        }
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }*/

    public function atualizaEntidadeExistente(\JsonSerializable $entidadeExistente, Request $request): \JsonSerializable
    {
        $dadosEmJson = json_decode($request->getContent());
        return $entidadeExistente->setDescricao($dadosEmJson->descricao);
    }
}
