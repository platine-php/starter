<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Platine\Framework\Auth\AuthenticationInterface;
use Platine\Framework\Helper\ActionHelper;
use Platine\Framework\Http\Action\BaseAction;
use Platine\Http\ResponseInterface;

/**
* @class LogoutAction
* @package Platine\App\Http\Action\User
* @template T
* @extends BaseAction<T> 
*/
class LogoutAction extends BaseAction
{
    /**
    * Create new instance
    * @param AuthenticationInterface $authentication
    * @param ActionHelper<T> $actionHelper
    */
    public function __construct(
        protected AuthenticationInterface $authentication,
        ActionHelper $actionHelper,
    ) {
        parent::__construct($actionHelper);
    }

    /**
    * {@inheritdoc}
    */
    public function respond(): ResponseInterface
    {
        $this->authentication->logout();

        $this->flash->setInfo($this->lang->tr('You are successfully logged out'));

        return $this->redirect('user_login');
    }
}
