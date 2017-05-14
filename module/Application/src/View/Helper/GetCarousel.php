<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Slider;

class GetCarousel extends AbstractHelper
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($obj)
    {
        $output = '';
        $slider = $this->entityManager->getRepository(Slider::class)->getSlider();

        if (is_array($slider) && ! empty($slider)) {
            foreach ($slider as $key => $item) {
                $image = $obj->getImage($obj, $item->getImage(), true);
                $title = $obj->cutString(trim(strip_tags($item->getTitle())), 12);
                $text  = $obj->cutString(trim(strip_tags($item->getText())), 15);

                /* class 'active' necessary pass to bootstrap carousel in order it work */
                if ($key == 0) {
                    $output .= '<div class="item active">';
                } else {
                    $output .= '<div class="item">';
                }

                $output .= '<img src="' . $image . '" alt="image">';
                $output .= '<div class="carousel-caption">';
                $output .= '<h5>' . $title . '</h5>';
                $output .= '<div>' . $text . '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
        } else {
            $output = 'Slider is empty';
        }

        return $output;
    }
}
