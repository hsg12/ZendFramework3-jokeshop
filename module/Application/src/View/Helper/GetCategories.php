<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class GetCategories extends AbstractHelper
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $result = $this->entityManager->getRepository(Category::class)->getCategoriesArray($this->entityManager);

        if (! $result) {
            return false;
        }

        $categories = [];
        foreach ($result as $value) {
            $categories[$value['parentId']][] = (object)$value;
        }

        return $this->buildTree($categories, 0);

    }

    private function buildTree($cat, $num)
    {
        $output = '';

        if (is_array($cat) && isset($cat[$num])) {
            $output .= '<ul class="list-group table-of-contents list-unstyled menu_vert">';
            foreach ($cat[$num] as $key => $value) {
                $output .= '<li><a href="/category/' . $value->id . '">' . $value->name . '</a>';
                $output .= $this->buildTree($cat, $value->id);
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }
}
