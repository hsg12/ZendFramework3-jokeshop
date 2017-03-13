<?php

namespace Admin\Service;

use Doctrine\ORM\EntityManagerInterface;

interface FormServiceInterface
{
    public function getAnnotationForm(EntityManagerInterface $entityManager, $formObj);
}
