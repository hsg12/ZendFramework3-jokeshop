<?php

namespace Admin\Service;

use Admin\Service\FormServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class FormService implements FormServiceInterface
{
    public function getAnnotationForm(EntityManagerInterface $entityManager, $formObj)
    {
        $builder = new AnnotationBuilder($entityManager);
        $form = $builder->createForm($formObj);
        $form->setHydrator(new DoctrineObject($entityManager));
        $form->bind($formObj);

        return ($form) ? $form : false;
    }
}
