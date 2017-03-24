<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class CutStr extends AbstractHelper
{
    public function __invoke($str, $cnt = 10)
    {
        if (strlen($str) <= $cnt) {
            $output = $str;
        } else {
            $output = "
                <span title='" . $str . "' data-toggle='tooltip' data-placement='right'>"
                    . substr($str, 0, $cnt) . '....' .
                "</span>
            ";
        }

        return $output;
    }
}
