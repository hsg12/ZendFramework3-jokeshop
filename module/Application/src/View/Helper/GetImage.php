<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetImage extends AbstractHelper
{
    public function __invoke($obj, $image, $slider = false)
    {
        if ($slider) {
            return ($obj->escapeHtml($image)) ? $obj->escapeHtml($image) : $obj->basePath('img/no-image-slider.jpg');
        } else {
            return ($obj->escapeHtml($image)) ? $obj->escapeHtml($image) : $obj->basePath('img/no-image.jpg');
        }
    }
}
