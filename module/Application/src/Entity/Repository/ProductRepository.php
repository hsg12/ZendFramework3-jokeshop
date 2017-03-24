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

    public function getProductsQueryBuilderForHomePage(EntityManagerInterface $entityManager, $considerStatus = true)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('p');
        $qb->from(Product::class, 'AS p');
        if ($considerStatus) {
            $qb->where('p.status = 1');
        }
        $qb->orderBy('p.id', 'DESC');

        return $qb ? $qb : false;
    }

    public function getProductsQueryBuilderForCategoryPage(EntityManagerInterface $entityManager, $categoryId)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'AS p')
            ->where('p.category = :categoryId')
            ->andWhere('p.status = 1')
            ->orderBy('p.id', 'DESC')
            ->setParameter('categoryId', $categoryId);

        return $qb ? $qb : false;
    }

    public function getSelectedProductsByIds(EntityManagerInterface $entityManager, $ids)
    {
        $sql  = "SELECT p ";
        $sql .= "FROM " . Product::class;
        $sql .= " AS p ";
        $sql .= "WHERE p.id IN (:ids)";

        $query = $entityManager->createQuery($sql)->setParameter('ids', $ids);
        $result = $query->getResult();

        return $result ? $result : false;
    }
}
