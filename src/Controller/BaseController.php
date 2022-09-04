<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{


    private ObjectRepository $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): JsonResponse
    {
        $entityList = $this->repository->findAll();
        return new JsonResponse($entityList);
    }

}