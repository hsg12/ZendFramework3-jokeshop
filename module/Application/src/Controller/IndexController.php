<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class IndexController extends AbstractActionController
{
    const URL_FOR_HOME_PAGE     = './data/items-count-per-page/products-count-per-home-page.txt';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $productsQueryBuilder = $this->entityManager
                                     ->getRepository(Product::class)
                                     ->getProductsQueryBuilderForHomePage($this->entityManager);

        $adapter = new DoctrinePaginator(new ORMPaginator($productsQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        if (is_file(self::URL_FOR_HOME_PAGE)) {
            $itemCountPerHomePage = file_get_contents(self::URL_FOR_HOME_PAGE);
        }

        $itemCountPerPage = $itemCountPerHomePage ? $itemCountPerHomePage : 8;
        $paginator->setItemCountPerPage($itemCountPerPage);

        return new ViewModel([
            'products' => $paginator,
        ]);
    }

    public function searchAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $product = false;

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        if (! empty($request->getPost('searchProduct'))) {
            $search = $request->getPost('searchProduct');

            $stripTags = new StripTags();
            $search = $stripTags->filter($search);

            $stringTrim = new StringTrim();
            $search = $stringTrim->filter($search);

            $product = $this->entityManager->getRepository(Product::class)->search($this->entityManager, $search);
        }

        $response->setContent(\Zend\Json\Json::encode($product));
        return $response;
    }
}
