<?php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\QueryBuilder;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getPaginatedProducts(PaginatorInterface $paginator, int $page, string $sortField = 'title', string $sortDirection = 'asc')
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy("p.$sortField", $sortDirection)
            ->getQuery();

        return $paginator->paginate($query, $page, 5); 
    }
}

