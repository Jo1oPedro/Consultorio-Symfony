<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{


    private ObjectRepository $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
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

}