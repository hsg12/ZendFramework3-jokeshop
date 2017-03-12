<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class CategoryRepository extends EntityRepository
{
    public function getCategoriesArray(EntityManagerInterface $entityManager)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('c')
           ->from(Category::class, 'AS c')
           ->orderBy('c.id', 'ASC');

        $query = $qb->getQuery();
        $result = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result ? $result : false;
    }
}
