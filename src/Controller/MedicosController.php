<?php

namespace App\Controller;

use  App\Entity\Medico;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 
     * @Route("/medicos", methods={"POST"})
     */
    public function create(Request $request) : Response 
    {
        $corpoDaRequesicao = $request->getContent();
        $dadoEmJson = json_decode($corpoDaRequesicao);
        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function index(): Response
    {
        $repositorioDeMedicos = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medicoList = $repositorioDeMedicos->findAll();

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("medicos/{id}", methods={"GET"})
     */
    public function show(Request $request): Response
    {
        $repositorioDeMedico = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medico = $repositorioDeMedico->find($request->attributes->get('id'));
        $codigoDeRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoDeRetorno);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $dadoEmJson = json_decode($request->getContent());
        $medicoEnviado = new Medico();
        $medicoEnviado->nome = $dadoEmJson->nome;
        $medicoEnviado->crm = $dadoEmJson->crm;

        $repositorioDeMedico = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medicoExistente = $repositorioDeMedico->find($id);

        if(is_null($medicoExistente)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->nome = $medicoEnviado->nome;
        $medicoExistente->crm = $medicoEnviado->crm;

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

}