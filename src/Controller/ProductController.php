<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/product')]
final class ProductController extends AbstractController
{
    // ✅ Fixed: Added "/" to define a proper path for the index route
    #[Route('/', name: 'app_product_index', methods: ['GET'])]

    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $productRepository->createQueryBuilder('p')->getQuery();
        $pagination = $paginator->paginate(
            $query, // Query (not an array)
            $request->query->getInt('page', 1), // Current page, default 1
            10 // Items per page
        );

        return $this->render('product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    // public function index(ProductRepository $productRepository): Response
    // {
    //     return $this->render('product/index.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index');
        }
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index');
    }

    // #[Route('/list', name: 'product_list')]
    // public function list(ProductRepository $repository): Response
    // {
    //     $products = $repository->findAll();
    //     return $this->render('product/list.html.twig', ['products' => $products]);
    // }

    #[Route('/products', name: 'product_list')]
    public function list(ProductRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $sortField = $request->query->get('sort', 'title');
        $sortDirection = $request->query->get('direction', 'asc');

        $products = $repository->getPaginatedProducts($paginator, $page, $sortField, $sortDirection);

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    // #[Route('/products', name: 'product_list')]
    // public function list(ProductRepository $repository, PaginatorInterface $paginator, Request $request): Response
    // {
    //     $page = $request->query->getInt('page', 1);
    //     $sortField = $request->query->get('sort', 'title');
    //     $sortDirection = $request->query->get('direction', 'asc');

    //     $products = $repository->getPaginatedProducts($paginator, $page, $sortField, $sortDirection);

    //     return $this->render('product/list.html.twig', [
    //         'products' => $products,
    //     ]);
    // }

    #[Route('/detail/{id}', name: 'product_detail')]
    public function detail(Product $product): Response
    {
        return $this->render('product/detail.html.twig', ['product' => $product]);
    }
}

