<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $repo): JsonResponse {
        return $this->json($repo->findAll());
    }

    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function getProduct(Product $product): JsonResponse {
        return $this->json($product);
    }
}
