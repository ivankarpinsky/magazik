<?php

namespace App\Controller;

use App\DTO\User\UserRegisterDTO;
use App\Services\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController {
    public function __construct(private AuthService $authService) {
    }

    #[Route('/register', name: 'register')]
    public function register(#[MapRequestPayload] UserRegisterDTO $userRegisterDTO): JsonResponse {
        $user = $this->authService->register($userRegisterDTO);

        return $this->json([
            'id' => $user->getId(),
        ], Response::HTTP_CREATED);
    }
}
