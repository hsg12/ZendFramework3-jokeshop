<?php

namespace Application\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class TopNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'top_navigation';
    }
}
