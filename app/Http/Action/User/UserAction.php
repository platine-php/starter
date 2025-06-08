<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\User;

use Exception;
use Platine\App\Helper\StatusList;
use Platine\App\Param\UserParam;
use Platine\App\Validator\UserValidator;
use Platine\Framework\Auth\Entity\User;
use Platine\Framework\Auth\Repository\RoleRepository;
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
use Platine\Pagination\Pagination;
use Platine\Security\Hash\HashInterface;
use Platine\Stdlib\Helper\Arr;
use Platine\Stdlib\Helper\Str;
use Platine\Template\Template;

/**
* @class UserAction
* @package Platine\App\Http\Action\User
*/
class UserAction
{
    /**
    * Create new instance
    * @param Lang $lang
    * @param Pagination $pagination
    * @param Template $template
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param LoggerInterface $logger
    * @param RoleRepository $roleRepository
    * @param StatusList $statusList
    * @param HashInterface $hash
    * @param UserRepository $userRepository
    */
    public function __construct(
        protected Lang $lang,
        protected Pagination $pagination,
        protected Template $template,
        protected Flash $flash,
        protected RouteHelper $routeHelper,
        protected LoggerInterface $logger,
        protected RoleRepository $roleRepository,
        protected StatusList $statusList,
        protected HashInterface $hash,
        protected UserRepository $userRepository
    ) {
    }

    /**
    * List all entities
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $param = new RequestData($request);
        $totalItems = $this->userRepository->query()
                                               ->count('id');

        $currentPage = (int) $param->get('page', 1);

        $this->pagination->setTotalItems($totalItems)
                        ->setCurrentPage($currentPage);

        $limit = $this->pagination->getItemsPerPage();
        $offset = $this->pagination->getOffset();

        $results = $this->userRepository->query()
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->orderBy('lastname', 'ASC')
                        ->orderBy('firstname', 'ASC')
                                            ->all();

        $context['list'] = $results;
        $context['pagination'] = $this->pagination->render();
        $context['user_status'] = $this->statusList->getUserStatus();


        return new TemplateResponse(
            $this->template,
            'user/list',
            $context
        );
    }

    /**
    * List entity detail
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function detail(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $id = (int) $request->getAttribute('id');

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if ($user === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        }
        $context['user'] = $user;
        $context['user_status'] = $this->statusList->getUserStatus();

        return new TemplateResponse(
            $this->template,
            'user/detail',
            $context
        );
    }

    /**
    * Create new entity
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $param = new RequestData($request);

        $formParam = new UserParam($param->posts());
        $context['param'] = $formParam;
        $context['roles'] = $this->roleRepository->orderBy('name')
                                                 ->all();

        $context['user_status'] = $this->statusList->getUserStatus();


        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'user/create',
                $context
            );
        }

        $validator = new UserValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'user/create',
                $context
            );
        }

        $usernameExist = $this->userRepository->findBy([
                                               'username' => $formParam->getUsername(),
                                           ]);

        if ($usernameExist !== null) {
            $this->flash->setError($this->lang->tr('This username already exist'));

            return new TemplateResponse(
                $this->template,
                'user/create',
                $context
            );
        }

        $emailExist = $this->userRepository->findBy([
                                               'email' => $formParam->getEmail(),
                                           ]);

        if ($emailExist !== null) {
            $this->flash->setError($this->lang->tr('This email already exist'));

            return new TemplateResponse(
                $this->template,
                'user/create',
                $context
            );
        }

        $passwordHash = $this->hash->hash($formParam->getPassword());

        /** @var User $user */
        $user = $this->userRepository->create([
           'username' => $formParam->getUsername(),
        'lastname' => Str::upper($formParam->getLastname()),
        'firstname' => Str::ucfirst($formParam->getFirstname()),
        'email' => $formParam->getEmail(),
        'status' => $formParam->getStatus(),
        'role' => $formParam->getRole(),
        'password' => $passwordHash,
        ]);

        $rolesId = $formParam->getRoles();
        if (count($rolesId) > 0) {
            $selectedRoles = $this->roleRepository->findAll(...$rolesId);
            $user->setRoles($selectedRoles);
        }

        try {
            $this->userRepository->save($user);

            $this->flash->setSuccess($this->lang->tr('Data successfully created'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'user/create',
                $context
            );
        }
    }

    /**
    * Update existing entity
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $param = new RequestData($request);

        $id = (int) $request->getAttribute('id');

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if ($user === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        }
        $context['user'] = $user;
        $context['param'] = (new UserParam())->fromEntity($user);
        $currentRolesId = Arr::getColumn($user->roles, 'id');
        $context['param']->setRoles($currentRolesId);

        $context['roles'] = $this->roleRepository->orderBy('name')
                                                 ->all();

        $context['user_status'] = $this->statusList->getUserStatus();

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'user/update',
                $context
            );
        }
        $formParam = new UserParam($param->posts());
        $context['param'] = $formParam;

        $validator = new UserValidator($formParam, $this->lang, true);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'user/update',
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
                'user/update',
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
                'user/update',
                $context
            );
        }

        $user->username = $formParam->getUsername();
        $user->lastname = $formParam->getLastname();
        $user->firstname = $formParam->getFirstname();
        $user->email = $formParam->getEmail();
        $user->status = $formParam->getStatus();
        $user->role = $formParam->getRole();

        $password = $formParam->getPassword();
        if (!empty($password)) {
            $passwordHash = $this->hash->hash($password);

            $user->password = $passwordHash;
        }

        $rolesId = $formParam->getRoles();
        $rolesIdToDelete = array_diff($currentRolesId, $rolesId);
        if (count($rolesIdToDelete) > 0) {
            $deletedRoles = $this->roleRepository->findAll(...$rolesIdToDelete);
            $user->removeRoles($deletedRoles);
        }

        $newRolesId = array_diff($rolesId, $currentRolesId);
        if (count($newRolesId) > 0) {
            $newRoles = $this->roleRepository->findAll(...$newRolesId);
            $user->setRoles($newRoles);
        }

        try {
            $this->userRepository->save($user);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_detail', ['id' => $id])
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'user/update',
                $context
            );
        }
    }

    /**
    * Delete the entity
    * @param ServerRequestInterface $request
    * @return ResponseInterface
    */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if ($user === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        }

        try {
            $this->userRepository->delete($user);

            $this->flash->setSuccess($this->lang->tr('Data successfully deleted'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when delete the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('user_list')
            );
        }
    }
}
