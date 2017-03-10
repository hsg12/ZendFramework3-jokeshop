<?php

namespace Authentication\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CutString extends AbstractHelper
{
    public function __invoke($str, $size = 15)
    {
        if (strlen($str) <= $size) {
            return $str;
        } else {
            return mb_substr($str, 0, $size) . '....';
        }
    }
}
