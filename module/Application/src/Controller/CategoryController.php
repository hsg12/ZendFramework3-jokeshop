<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Application\Entity\Product;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class CategoryController extends AbstractActionController
{
    const URL_FOR_CATEGORY_PAGE = './data/items-count-per-page/products-count-per-category-page.txt';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        $productsQueryBuilder = $this->entityManager
                                     ->getRepository(Product::class)
                                     ->getProductsQueryBuilderForCategoryPage($this->entityManager, $id);

        $adapter = new DoctrinePaginator(new ORMPaginator($productsQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        if (is_file(self::URL_FOR_CATEGORY_PAGE)) {
            $itemCountPerCategoryPage = file_get_contents(self::URL_FOR_CATEGORY_PAGE);
        }
        $itemCountPerPage = $itemCountPerCategoryPage ? $itemCountPerCategoryPage : 8;
        $paginator->setItemCountPerPage($itemCountPerPage);

        return new ViewModel([
            'category' => $category,
            'products' => $paginator,
        ]);
    }
}
