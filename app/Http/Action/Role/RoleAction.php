<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\Role;

use Exception;
use Platine\App\Helper\StatusList;
use Platine\App\Param\RoleParam;
use Platine\App\Validator\RoleValidator;
use Platine\Framework\Auth\Entity\Role;
use Platine\Framework\Auth\Repository\PermissionRepository;
use Platine\Framework\Auth\Repository\RoleRepository;
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
use Platine\Stdlib\Helper\Arr;
use Platine\Template\Template;

/**
* @class RoleAction
* @package Platine\App\Http\Action\Role
*/
class RoleAction
{
    /**
    * Create new instance
    * @param Lang $lang
    * @param Pagination $pagination
    * @param Template $template
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param LoggerInterface $logger
    * @param PermissionRepository $permissionRepository
    * @param RoleRepository $roleRepository
    * @param StatusList $statusList
    */
    public function __construct(
        protected Lang $lang,
        protected Pagination $pagination,
        protected Template $template,
        protected Flash $flash,
        protected RouteHelper $routeHelper,
        protected LoggerInterface $logger,
        protected PermissionRepository $permissionRepository,
        protected RoleRepository $roleRepository,
        protected StatusList $statusList
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
        $totalItems = $this->roleRepository->query()
                                               ->count('id');

        $currentPage = (int) $param->get('page', 1);

        $this->pagination->setTotalItems($totalItems)
                        ->setCurrentPage($currentPage);

        $limit = $this->pagination->getItemsPerPage();
        $offset = $this->pagination->getOffset();

        $results = $this->roleRepository->query()
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->orderBy('name', 'ASC')
                                            ->all();

        $context['list'] = $results;
        $context['pagination'] = $this->pagination->render();


        return new TemplateResponse(
            $this->template,
            'role/list',
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

        /** @var Role|null $role */
        $role = $this->roleRepository->find($id);

        if ($role === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        }
        $context['role'] = $role;
        $context['user_status'] = $this->statusList->getUserStatus();

        return new TemplateResponse(
            $this->template,
            'role/detail',
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

        $formParam = new RoleParam($param->posts());
        $context['param'] = $formParam;

        $permissions = $this->permissionRepository->orderBy('code')
                                                  ->all();

        $context['permissions'] = $permissions;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'role/create',
                $context
            );
        }

        $validator = new RoleValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'role/create',
                $context
            );
        }

        $entityExist = $this->roleRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'role/create',
                $context
            );
        }

        /** @var Role $role */
        $role = $this->roleRepository->create([
           'name' => $formParam->getName(),
        'description' => $formParam->getDescription(),
        ]);

        $permissionsId = $formParam->getPermissions();
        if (count($permissionsId) > 0) {
            $selectedPermissions = $this->permissionRepository->findAll(...$permissionsId);
            $role->setPermissions($selectedPermissions);
        }

        try {
            $this->roleRepository->save($role);

            $this->flash->setSuccess($this->lang->tr('Data successfully created'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'role/create',
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

        /** @var Role|null $role */
        $role = $this->roleRepository->find($id);

        if ($role === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        }
        $context['role'] = $role;
        $context['param'] = (new RoleParam())->fromEntity($role);

        $currentPermissionsId = Arr::getColumn($role->permissions, 'id');
        $context['param']->setPermissions($currentPermissionsId);

        $permissions = $this->permissionRepository->orderBy('code')
                                                  ->all();

        $context['permissions'] = $permissions;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'role/update',
                $context
            );
        }
        $formParam = new RoleParam($param->posts());
        $context['param'] = $formParam;

        $validator = new RoleValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'role/update',
                $context
            );
        }

        $entityExist = $this->roleRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null && $entityExist->id !== $id) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'role/update',
                $context
            );
        }

        $role->name = $formParam->getName();
        $role->description = $formParam->getDescription();

        $permissionsId = $formParam->getPermissions();

        //Delete Handle
        $permissionsIdToDelete = array_diff($currentPermissionsId, $permissionsId);
        if (count($permissionsIdToDelete) > 0) {
            $deletedPermissions = $this->permissionRepository->findAll(...$permissionsIdToDelete);
            $role->removePermissions($deletedPermissions);
        }

        //New Handle
        $permissionsIdToAdd = array_diff($permissionsId, $currentPermissionsId);
        if (count($permissionsIdToAdd) > 0) {
            $addedPermissions = $this->permissionRepository->findAll(...$permissionsIdToAdd);
            $role->setPermissions($addedPermissions);
        }

        try {
            $this->roleRepository->save($role);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_detail', ['id' => $id])
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'role/update',
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

        /** @var Role|null $role */
        $role = $this->roleRepository->find($id);

        if ($role === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        }

        try {
            $this->roleRepository->delete($role);

            $this->flash->setSuccess($this->lang->tr('Data successfully deleted'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when delete the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('role_list')
            );
        }
    }
}
