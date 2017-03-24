<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\ProductOrder;

class ProductOrderRepository extends EntityRepository
{
    public function getOrders(EntityManagerInterface $entityManager, $userId)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('pO')
           ->from(ProductOrder::class, 'AS pO')
           ->where('pO.userId = ?1')
           ->orderBy('pO.id', 'DESC')
           ->setParameter(1, $userId);

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result ? $result : false;
    }

    public function getProductOrderQueryBuilder(EntityManagerInterface $entityManager)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('pO')
            ->from(ProductOrder::class, 'AS pO')
            ->orderBy('pO.id', 'DESC');

        return $qb ? $qb : false;
    }
}
