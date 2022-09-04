<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{


    protected ObjectRepository $repository;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $entityList = $this->repository->findAll();
        return new JsonResponse($entityList);
    }

    /**
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $object = $this->repository->find($id);
        if($object) {
            return new JsonResponse($object);
        }
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(int $id): Response
    {
        $object = $this->repository->find($id);
        $this->entityManager->remove($object);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

}