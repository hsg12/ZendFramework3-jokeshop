<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Slider;
use Doctrine\ORM\EntityManagerInterface;

class SliderRepository extends EntityRepository
{
    public function getSlider(EntityManagerInterface $entityManager, $considerVisible = true)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('s');
        $qb->from(Slider::class, 'AS s');
        if ($considerVisible) {
            $qb->where('s.visible = 1');
        }
        $qb->orderBy('s.id', 'ASC');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result ? $result : false;
    }
}
