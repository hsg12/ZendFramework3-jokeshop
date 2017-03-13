<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\Product;

class ProductController extends AbstractActionController
{
    private $entityManager;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService   = $formService;
    }

    public function indexAction()
    {

        return new ViewModel();
    }

    public function addAction()
    {
        $product = new Product();
        $form = $this->formService->getAnnotationForm($this->entityManager, $product);
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $product = new Product();
        $form = $this->formService->getAnnotationForm($this->entityManager, $product);
        return new ViewModel();
    }

    public function deleteAction()
    {

        return new ViewModel();
    }
}
