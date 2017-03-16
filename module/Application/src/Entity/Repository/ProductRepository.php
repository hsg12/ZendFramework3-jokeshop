<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;

class ProductRepository extends EntityRepository
{
    public function search(EntityManagerInterface $entityManager, $searchPattern)
    {
        $search = '%' . $searchPattern . '%';

        $qb = $entityManager->createQueryBuilder();
        $qb->select(['p.id, p.name'])
           ->from(Product::class, 'AS p')
           ->where('p.name LIKE :search')
           ->setParameter('search', $search);

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result ? $result : false;
    }

    public function getProductsQueryBuilder(EntityManagerInterface $entityManager)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('p')
           ->from(Product::class, 'AS p')
           ->where('p.status = 1')
           ->orderBy('p.id', 'DESC');

        return $qb ? $qb : false;
    }
}
