<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Exception;
use Platine\App\Helper\StatusList;
use Platine\App\Param\UserParam;
use Platine\App\Validator\UserValidator;
use Platine\Framework\Auth\AuthenticationInterface;
use Platine\Framework\Auth\Entity\User;
use Platine\Framework\Auth\Repository\UserRepository;
use Platine\Framework\Helper\Flash;
use Platine\Framework\Http\RequestData;
use Platine\Framework\Http\Response\RedirectResponse;
use Platine\Framework\Http\Response\TemplateResponse;
use Platine\Framework\Http\RouteHelper;
use Platine\Http\ResponseInterface;
use Platine\Http\ServerRequestInterface;
use Platine\Lang\Lang;
use Platine\Logger\LoggerInterface;
use Platine\Security\Hash\HashInterface;
use Platine\Template\Template;

/**
* @class UserProfileAction
* @package Platine\App\Http\Action\User
*/
class UserProfileAction
{
    /**
    * The Lang instance
    * @var Lang
    */
    protected Lang $lang;

    /**
    * The Template instance
    * @var Template
    */
    protected Template $template;

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
    * The LoggerInterface instance
    * @var LoggerInterface
    */
    protected LoggerInterface $logger;

    /**
    * The StatusList instance
    * @var StatusList
    */
    protected StatusList $statusList;

    /**
    * The HashInterface instance
    * @var HashInterface
    */
    protected HashInterface $hash;

    /**
    * The UserRepository instance
    * @var UserRepository
    */
    protected UserRepository $userRepository;

    /**
    * The AuthenticationInterface instance
    * @var AuthenticationInterface
    */
    protected AuthenticationInterface $authentication;

    /**
    * Create new instance
    * @param Lang $lang
    * @param AuthenticationInterface $authentication
    * @param Template $template
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param LoggerInterface $logger
    * @param StatusList $statusList
    * @param HashInterface $hash
    * @param UserRepository $userRepository
    */
    public function __construct(
        Lang $lang,
        AuthenticationInterface $authentication,
        Template $template,
        Flash $flash,
        RouteHelper $routeHelper,
        LoggerInterface $logger,
        StatusList $statusList,
        HashInterface $hash,
        UserRepository $userRepository
    ) {
        $this->lang = $lang;
        $this->authentication = $authentication;
        $this->template = $template;
        $this->flash = $flash;
        $this->routeHelper = $routeHelper;
        $this->logger = $logger;
        $this->statusList = $statusList;
        $this->hash = $hash;
        $this->userRepository = $userRepository;
    }

    /**
    * User profile
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function detail(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $id = $this->authentication->getUser()->getId();

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if ($user === null) {
            $this->authentication->logout();

            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_login')
            );
        }

        $context['user'] = $user;
        $context['user_status'] = $this->statusList->getUserStatus();

        return new TemplateResponse(
            $this->template,
            'user/profile',
            $context
        );
    }

    /**
    * Update user profile
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $param = new RequestData($request);

        $id = $this->authentication->getUser()->getId();

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if ($user === null) {
             $this->authentication->logout();

            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_login')
            );
        }
        $context['user'] = $user;
        $context['param'] = (new UserParam())->fromEntity($user);

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'user/update_profile',
                $context
            );
        }
        $formParam = new UserParam($param->posts());
        $formParam->setStatus($user->status);

        $context['param'] = $formParam;

        $validator = new UserValidator($formParam, $this->lang, true);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'user/update_profile',
                $context
            );
        }

        $usernameExist = $this->userRepository->findBy([
                                               'username' => $formParam->getUsername(),
                                           ]);

        if ($usernameExist !== null && $usernameExist->id !== $id) {
            $this->flash->setError($this->lang->tr('This username already exist'));

            return new TemplateResponse(
                $this->template,
                'user/update_profile',
                $context
            );
        }

        $emailExist = $this->userRepository->findBy([
                                               'email' => $formParam->getEmail(),
                                           ]);

        if ($emailExist !== null && $emailExist->id !== $id) {
            $this->flash->setError($this->lang->tr('This email already exist'));

            return new TemplateResponse(
                $this->template,
                'user/update_profile',
                $context
            );
        }

        $user->username = $formParam->getUsername();
        $user->lastname = $formParam->getLastname();
        $user->firstname = $formParam->getFirstname();
        $user->email = $formParam->getEmail();
        $user->status = $formParam->getStatus();

        $password = $formParam->getPassword();
        if (!empty($password)) {
            $passwordHash = $this->hash->hash($password);

            $user->password = $passwordHash;
        }

        try {
            $this->userRepository->save($user);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_profile')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'user/update_profile',
                $context
            );
        }
    }
}
