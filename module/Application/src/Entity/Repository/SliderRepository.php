<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Slider;

class SliderRepository extends EntityRepository
{
    public function getSlider($considerVisible = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
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
