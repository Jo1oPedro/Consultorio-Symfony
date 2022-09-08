<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\EstratorDeDadosDoRequest;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{


    protected ObjectRepository $repository;
    protected EntityManagerInterface $entityManager;
    protected EntidadeFactory $entidadeFactory;
    private EstratorDeDadosDoRequest $estratorDeDadosDoRequest;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $entidadeFactory,
        EstratorDeDadosDoRequest $estratorDeDadosDoRequest,
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->entidadeFactory = $entidadeFactory;
        $this->estratorDeDadosDoRequest = $estratorDeDadosDoRequest;
    }

    /**
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $informacoesDeOrdenacao = $this->estratorDeDadosDoRequest->buscaDadosOrdenacao($request);
        $queryStringFilter = $this->estratorDeDadosDoRequest->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->estratorDeDadosDoRequest->buscaDadosPaginacao($request);

        $entityList = $this->repository->findBy(
            $queryStringFilter,
            $informacoesDeOrdenacao,
            $itensPorPagina,
            ($paginaAtual - 1) * $itensPorPagina,
            Response::HTTP_OK,
        );

        $fabricaResposta = new ResponseFactory(
            true,
            $paginaAtual,
            $itensPorPagina,
            $entityList,
        );

        return $fabricaResposta->getResponse();

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $entidade = $this->entidadeFactory->criaEntidade($request->getContent());
        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): Response
    {
        $entity = $this->repository->find($id);

        if(is_null($entity)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $entity = $this->atualizaEntidadeExistente($entity, $request);

        $this->entityManager->flush();

        return new JsonResponse($entity);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): Response
    {
        $entity = $this->repository->find($id);
        $statusResposta = is_null($entity)
            ? Response::HTTP_NOT_FOUND
            : Response::HTTP_OK;

        if($entity) {
            $fabricaResposta = new ResponseFactory(
                sucesso: 'sucesso',
                conteudoResposta: $entity,
                statusResposta: $statusResposta,
            );
            return $fabricaResposta->getResponse();
        }
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $object = $this->repository->find($id);
        $this->entityManager->remove($object);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    abstract public function atualizaEntidadeExistente(\JsonSerializable $entidadeExistente,Request $request): \JsonSerializable;

}