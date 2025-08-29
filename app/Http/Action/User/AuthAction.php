<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Platine\App\Param\AuthParam;
use Platine\App\Validator\AuthValidator;
use Platine\Framework\Auth\AuthenticationInterface;
use Platine\Framework\Auth\Exception\AuthenticationException;
use Platine\Framework\Helper\ActionHelper;
use Platine\Framework\Http\Action\BaseAction;
use Platine\Framework\Http\Response\RedirectResponse;
use Platine\Http\ResponseInterface;

/**
* @class AuthAction
* @package Platine\App\Http\Action\User
* @template T
* @extends BaseAction<T>
*/
class AuthAction extends BaseAction
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
        $this->setView('user/login');
        $request = $this->request;
        $param = $this->param;

        $formParam = new AuthParam($param->posts());
        $this->addContext('param', $formParam);

        if ($request->getMethod() === 'GET') {
            return $this->viewResponse();
        }

        $validator = new AuthValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $this->addContext('errors', $validator->getErrors());

            return $this->viewResponse();
        }

        $username = $formParam->getUsername();
        $password = $formParam->getPassword();

        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        try {
            $this->authentication->login($credentials);
        } catch (AuthenticationException $ex) {
            $this->logger->error('Authentication error: {error}', [
                'error' => $ex->getMessage()
            ]);

            $this->flash->setError('Authentication error. Please check your login and/or password.');

            return $this->viewResponse();
        }

        $returnUrl = $this->routeHelper->generateUrl('home');
        if ($param->get('next')) {
            $returnUrl = $param->get('next');
        }

        return new RedirectResponse($returnUrl);
    }
}
