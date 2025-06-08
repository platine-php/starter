<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Platine\Framework\Auth\AuthenticationInterface;
use Platine\Framework\Helper\Flash;
use Platine\Framework\Http\Response\RedirectResponse;
use Platine\Framework\Http\RouteHelper;
use Platine\Http\Handler\RequestHandlerInterface;
use Platine\Http\ResponseInterface;
use Platine\Http\ServerRequestInterface;
use Platine\Lang\Lang;

/**
* @class LogoutAction
* @package Platine\App\Http\Action\User
*/
class LogoutAction implements RequestHandlerInterface
{
    /**
    * Create new instance
    * @param AuthenticationInterface $authentication
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param Lang $lang
    */
    public function __construct(
        protected AuthenticationInterface $authentication,
        protected Flash $flash,
        protected RouteHelper $routeHelper,
        protected Lang $lang
    ) {
    }

    /**
    * {@inheritdoc}
    */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->authentication->logout();

        $this->flash->setInfo($this->lang->tr('You are successfully logged out'));

        return new RedirectResponse(
            $this->routeHelper->generateUrl('user_login')
        );
    }
}
