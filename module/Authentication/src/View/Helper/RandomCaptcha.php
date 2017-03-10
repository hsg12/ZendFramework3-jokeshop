<?php

namespace Authentication\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RandomCaptcha extends AbstractHelper
{
    public function __invoke()
    {
        $valuesArray = [];
        $x = rand(0, 100);
        $y = rand(0, 10);

        $valuesArray['sum'] = $x + $y;
        $valuesArray['str'] = $x . ' + ' . $y . ' = ';

        return (object)$valuesArray;
    }
}
