<?php

namespace App\Controller;

use App\Entity\Especialidade;
use  App\Entity\Medico;
use App\Helper\EstratorDeDadosDoRequest;
use App\Helper\MedicoFactory;
use App\Repository\EspecialidadeRepository;
use App\Repository\MedicosRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MedicosController extends BaseController
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $entityManager;
    private EspecialidadeRepository $especialidadeRepository;

    /**
     * @var MedicoFactory
     */
    //private MedicoFactory $factory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicosRepository $repository,
        EspecialidadeRepository $especialidadeRepository,
        EstratorDeDadosDoRequest $estratorDeDadosDoRequest,
        CacheItemPoolInterface $cacheItemPool,
        LoggerInterface $logger
    ) {
        //$this->factory = $medicoFactory;
        parent::__construct($repository, $entityManager, $medicoFactory, $estratorDeDadosDoRequest, $cacheItemPool, $logger);
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * 
     * @Route("/medicos", methods={"POST"})
     */
    /*public function create(Request $request) : Response
    {
        //$medico = MedicoFactory::criarMedico($request->getContent());

        $medico = $this->factory->criarMedico($request->getContent());
        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }*/

    /**
     * @Route("/medicos", methods={"GET"})
     */
    /*public function index(): Response
    {
        $repositorioDeMedicos = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medicoList = $repositorioDeMedicos->findAll();

        return new JsonResponse($medicoList);
    }*/

    /**
     * @Route("medicos/{id}", methods={"GET"})
     */
    /*public function show(Request $request): Response
    {
        $medico = $this->buscaMedico($request->attributes->get('id'));
        $codigoDeRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoDeRetorno);
    }*/

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    /*public function update(int $id, Request $request): Response
    {
       $medicoEnviado = $this->factory->criarMedico($request->getContent());

        $medicoExistente = $this->buscaMedico($id);

        if(is_null($medicoExistente)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $medicoExistente
            ->setNome($medicoEnviado->getNome())
            ->setCrm($medicoEnviado->getCrm());

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }*/

    /**
     * @Route("medicos/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $medico = $this->buscaMedicoDeUmaFormaMaisEficaz($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Medico\mixed|object|null
     */
    public function buscaMedico(int $id)
    {
        $repositorioDeMedico = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medico = $repositorioDeMedico->find($id);
        return $medico;
    }

    /**
     * @param int $id
     * @return Medico|mixed|object|null
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function buscaMedicoDeUmaFormaMaisEficaz(int $id)
    {
        // Evita que fa??amos qum select para s?? depois realizar as a????es desejadas
        // Dessa forma a gente cria uma entidade gerenciada pelo Doctrine, diretamente no c??digo, sem buscar no banco
        return $repoositorioDeMedico = $this
            ->entityManager
            ->getReference(Medico::class, $id);
    }

    /**
     * @Route("especialidades/{especialidade_id}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return Response
     */
    public function getMedicoPorEspecialidade(int $especialidade_id): Response
    {
        $medicos = $this->repository->findBy(["especialidade" => $especialidade_id]);

        return new JsonResponse($medicos);
    }

    /**
     * @param Medico $entidadeExistente
     * @param Request $request
     * @return Medico
     */
    public function atualizaEntidadeExistente(
        \JsonSerializable $entidadeExistente,
        Request $request
    ): \JsonSerializable {
        $dadosEmJson = json_decode($request->getContent());
        $especialidade = $this->especialidadeRepository->find($dadosEmJson->especialidadeId);
        return $entidadeExistente->setNome($dadosEmJson->nome)
                        ->setCrm($dadosEmJson->crm)
                        ->setEspecialidade($especialidade);
    }

    public function cachePrefix(): string
    {
        return 'medico_';
    }
}