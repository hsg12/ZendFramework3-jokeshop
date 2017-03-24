<?php

namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Authentication\Model\AppAuthStorage;
use Application\Model\Cart;

class LogoutController extends AbstractActionController
{
    private $ormAuthService;
    private $authStorage;

    public function __construct(
        $ormAuthService,
        AppAuthStorage $authStorage
    ) {
        $this->ormAuthService = $ormAuthService;
        $this->authStorage = $authStorage;
    }

    public function indexAction()
    {
        $this->authStorage->forgetMe();
        $this->ormAuthService->clearIdentity();

        /* In order to clean up user's products basket */
        Cart::clearProductsSession();
        /* End block */

        return $this->redirect()->toRoute('home');
    }
}
