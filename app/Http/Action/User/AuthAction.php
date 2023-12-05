<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Platine\App\Param\AuthParam;
use Platine\App\Validator\AuthValidator;
use Platine\Framework\Auth\AuthenticationInterface;
use Platine\Framework\Auth\Exception\AuthenticationException;
use Platine\Framework\Helper\Flash;
use Platine\Framework\Http\RequestData;
use Platine\Framework\Http\Response\RedirectResponse;
use Platine\Framework\Http\Response\TemplateResponse;
use Platine\Framework\Http\RouteHelper;
use Platine\Http\Handler\RequestHandlerInterface;
use Platine\Http\ResponseInterface;
use Platine\Http\ServerRequestInterface;
use Platine\Lang\Lang;
use Platine\Logger\LoggerInterface;
use Platine\Template\Template;

/**
* @class AuthAction
* @package Platine\App\Http\Action\User
*/
class AuthAction implements RequestHandlerInterface
{
    /**
    * The AuthenticationInterface instance
    * @var AuthenticationInterface
    */
    protected AuthenticationInterface $authentication;

    /**
    * The Flash instance
    * @var Flash
    */
    protected Flash $flash;

    /**
    * The RouteHelper instance
    * @var RouteHelper
    */
    protected RouteHelper $routeHelper;

    /**
    * The Lang instance
    * @var Lang
    */
    protected Lang $lang;

    /**
    * The LoggerInterface instance
    * @var LoggerInterface
    */
    protected LoggerInterface $logger;

    /**
    * The Template instance
    * @var Template
    */
    protected Template $template;



    /**
    * Create new instance
    * @param AuthenticationInterface $authentication
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param Lang $lang
    * @param LoggerInterface $logger
    * @param Template $template
    */
    public function __construct(
        AuthenticationInterface $authentication,
        Flash $flash,
        RouteHelper $routeHelper,
        Lang $lang,
        LoggerInterface $logger,
        Template $template
    ) {
        $this->authentication = $authentication;
        $this->flash = $flash;
        $this->routeHelper = $routeHelper;
        $this->lang = $lang;
        $this->logger = $logger;
        $this->template = $template;
    }

    /**
    * {@inheritdoc}
    */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $param = new RequestData($request);

        $formParam = new AuthParam($param->posts());
        $context['param'] = $formParam;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'user/login',
                $context
            );
        }

        $validator = new AuthValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'user/login',
                $context
            );
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

            return new TemplateResponse(
                $this->template,
                'user/login',
                $context
            );
        }

        $returnUrl = $this->routeHelper->generateUrl('home');
        if ($param->get('next')) {
            $returnUrl = $param->get('next');
        }

        return new RedirectResponse($returnUrl);
    }
}
