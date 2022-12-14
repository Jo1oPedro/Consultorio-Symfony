<?php

namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAutenticador extends AbstractGuardAuthenticator
{

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    public function supports(Request $request)
    {
        // TODO: Implement supports() method.
        return $request->getPathInfo() !== '/login';
    }

    public function getCredentials(Request $request)
    {
        // TODO: Implement getCredentials() method.
        $token = str_replace(
            'Bearer ',
            '',
            $request->headers->get('Authorization')
        );
        try {
            return JWT::decode($token, new Key('chave', 'HS256'));
        } catch (\Exception $e) {
            return false;
        }

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // TODO: Implement getUser() method.
        if(!is_object($credentials) || !property_exists($credentials, 'username')) {
            return null;
        }
        $username = $credentials->username;
        return $this->repository->findOneBy(["username" => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
        return is_object($credentials) && property_exists($credentials, 'username');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
        return new JsonResponse([
            "erro" => "Falha na autentica????o"
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
        return null;
    }

    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
        return false;
    }
}