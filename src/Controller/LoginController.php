<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{

    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordHasher,
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    //#[Route('/login', name: 'app_login')]
    /**
     * @Route("/login", methods={"GET"})
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $dadosEmJson = json_decode($request->getContent());
        if(is_null($dadosEmJson->usuario) || is_null($dadosEmJson->senha)) {
            return new JsonResponse(
                ["erro" => "Favor enviar usuário e senha"],
                Response::HTTP_BAD_REQUEST);

        }

        $user = $this->userRepository->findOneBy(["username" => $dadosEmJson->usuario]);
        if($user) {
            if(!$this->passwordHasher->isPasswordValid($user, $dadosEmJson->senha)) {
                return new JsonResponse([
                        'erro' => "Usuário ou senha invalidos",
                    ], Response::HTTP_UNAUTHORIZED,
                );
            }
            $token = JWT::encode(
                ['username' => $user->getUsername()],
                'chave',
                'HS256'
            );

            return new JsonResponse([
                "access_token" => $token,
            ]);
        }

        return new JsonResponse([
                'erro' => "Usuário ou senha invalidos",
            ], Response::HTTP_UNAUTHORIZED,
        );
    }
}
