<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class IndexController extends AbstractActionController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        //$products = $this->entityManager->getRepository(Product::class)->findAll();

        $productsQueryBuilder = $this->entityManager
                                     ->getRepository(Product::class)
                                     ->getProductsQueryBuilder($this->entityManager);

        $adapter = new DoctrinePaginator(new ORMPaginator($productsQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        $itemCountPerPage = 1;
        $paginator->setItemCountPerPage($itemCountPerPage);

        return new ViewModel([
            'products' => $paginator,
        ]);
    }
}
