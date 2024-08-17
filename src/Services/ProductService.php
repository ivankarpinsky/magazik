<?php

namespace App\Services;

use App\DTO\Product\OrderCreateDTO;
use App\DTO\Product\ProductCreateDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService {

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function store(ProductCreateDTO $productCreateDTO): Product {
        $product = new Product();
        $product->setName($productCreateDTO->name);
        $product->setPrice($productCreateDTO->price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}