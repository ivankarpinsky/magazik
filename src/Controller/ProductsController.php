<?php

namespace App\Controller;

use App\DTO\Product\OrderCreateDTO;
use App\DTO\Product\ProductCreateDTO;
use App\Entity\Product;
use App\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{
    public function __construct(private ProductService $productService, private EntityManagerInterface $entityManager) {}

    #[Route('/products/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        return $this->json($products);
    }

    #[Route('/products/', name: 'store', methods: ['POST'])]
    public function store(
        #[MapRequestPayload] ProductCreateDTO $productCreateDTO
    ): JsonResponse
    {
        $product = $this->productService->store($productCreateDTO);

        return $this->json(['id' => $product->getId()], Response::HTTP_CREATED);
    }
}
