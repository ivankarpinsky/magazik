<?php

namespace App\Controller;

use App\DTO\CartItem\CartItemRequestCreateDTO;
use App\DTO\CartItem\CartItemRequestRemoveDTO;
use App\DTO\CartItem\CartItemRequestUpdateQuantityDTO;
use App\DTO\Order\OrderCreateDTO;
use App\Entity\Product;
use App\Entity\User;
use App\Enums\RoleEnum;
use App\Services\CartItemService;
use App\Services\OrderService;
use App\Services\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/report', name: 'report_')]
class ReportController extends AbstractController {
    public function __construct(private ReportService $reportService,) {
    }

    #[Route('/generate', name: 'generate', methods: ['POST'])]
    public function store(): JsonResponse {
        $uuid = $this->reportService->startGenerate();

        return $this->json(['id' => $uuid], Response::HTTP_CREATED);
    }
}
