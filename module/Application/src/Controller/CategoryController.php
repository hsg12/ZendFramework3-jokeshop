<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class CategoryController extends AbstractActionController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        return new ViewModel([
            'category' => $category,
        ]);
    }
}
