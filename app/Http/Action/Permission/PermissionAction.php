<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\Permission;

use Exception;
use Platine\Http\ResponseInterface;
use Platine\Http\ServerRequestInterface;
use Platine\Framework\Http\RequestData;
use Platine\Framework\Http\Response\TemplateResponse;
use Platine\Framework\Http\Response\RedirectResponse;
use Platine\Lang\Lang; 
use Platine\Pagination\Pagination; 
use Platine\Template\Template; 
use Platine\Framework\Helper\Flash; 
use Platine\Framework\Http\RouteHelper; 
use Platine\Logger\LoggerInterface; 
use Platine\Framework\Auth\Repository\PermissionRepository; 
use Platine\Framework\Auth\Entity\Permission; 
use Platine\App\Param\PermissionParam; 
use Platine\App\Validator\PermissionValidator; 


/**
* @class PermissionAction
* @package Platine\App\Http\Action\Permission
*/
class PermissionAction
{
    
    /**
    * The Lang instance
    * @var Lang
    */
    protected Lang $lang;

    /**
    * The Pagination instance
    * @var Pagination
    */
    protected Pagination $pagination;

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
    * The PermissionRepository instance
    * @var PermissionRepository
    */
    protected PermissionRepository $permissionRepository;

    

    /**
    * Create new instance
    * @param Lang $lang 
    * @param Pagination $pagination 
    * @param Template $template 
    * @param Flash $flash 
    * @param RouteHelper $routeHelper 
    * @param LoggerInterface $logger 
    * @param PermissionRepository $permissionRepository 
    */
    public function __construct(
       Lang $lang,
       Pagination $pagination,
       Template $template,
       Flash $flash,
       RouteHelper $routeHelper,
       LoggerInterface $logger,
       PermissionRepository $permissionRepository
    ){
        $this->lang = $lang;
        $this->pagination = $pagination;
        $this->template = $template;
        $this->flash = $flash;
        $this->routeHelper = $routeHelper;
        $this->logger = $logger;
        $this->permissionRepository = $permissionRepository;
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
        $totalItems = $this->permissionRepository->query()
                                               ->count('id');

        $currentPage = (int) $param->get('page', 1);

        $this->pagination->setTotalItems($totalItems)
                        ->setCurrentPage($currentPage);

        $limit = $this->pagination->getItemsPerPage();
        $offset = $this->pagination->getOffset();

        $results = $this->permissionRepository->query()
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->orderBy('code', 'ASC')
                                            ->all();
        
        $context['list'] = $results;
        $context['pagination'] = $this->pagination->render();


        return new TemplateResponse(
            $this->template,
            'permission/list',
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

        /** @var Permission|null $permission */
        $permission = $this->permissionRepository->find($id);

        if ($permission === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        }
        $context['permission'] = $permission;
                
        return new TemplateResponse(
            $this->template,
            'permission/detail',
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
        
        $formParam = new PermissionParam($param->posts());
        $context['param'] = $formParam;
        
        $permissions = $this->permissionRepository->orderBy('code')
                                                  ->all();
        
        $context['permissions'] = $permissions;
        
        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'permission/create',
                $context
            );
        }
        
        $validator = new PermissionValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'permission/create',
                $context
            );
        }
        
        $entityExist = $this->permissionRepository->findBy([
                                               'code' => $formParam->getCode(),
                                           ]);
        
        if($entityExist !== null){
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'permission/create',
                $context
            );
        }

        /** @var Permission $permission */
        $permission = $this->permissionRepository->create([
           'code' => $formParam->getCode(),
	   'description' => $formParam->getDescription(),
	   'depend' => $formParam->getDepend(),
        ]);
        
        try {
            $this->permissionRepository->save($permission);

            $this->flash->setSuccess($this->lang->tr('Data successfully created'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'permission/create',
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

        /** @var Permission|null $permission */
        $permission = $this->permissionRepository->find($id);

        if ($permission === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        }
        $context['permission'] = $permission;
        $context['param'] = (new PermissionParam())->fromEntity($permission);
        
        $permissions = $this->permissionRepository->orderBy('code')
                                                  ->all();
        
        $context['permissions'] = $permissions;
        
        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'permission/update',
                $context
            );
        }
        $formParam = new PermissionParam($param->posts());
        $context['param'] = $formParam;
        
        $validator = new PermissionValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'permission/update',
                $context
            );
        }
        
        $entityExist = $this->permissionRepository->findBy([
                                               'code' => $formParam->getCode(),
                                           ]);
        
        if($entityExist !== null && $entityExist->id !== $id){
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'permission/update',
                $context
            );
        }

        $permission->code = $formParam->getCode();
	   $permission->description = $formParam->getDescription();
	   $permission->depend = $formParam->getDepend();
        
        try {
            $this->permissionRepository->save($permission);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_detail', ['id' => $id])
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'permission/update',
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

        /** @var Permission|null $permission */
        $permission = $this->permissionRepository->find($id);

        if ($permission === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        }

        try {
            $this->permissionRepository->delete($permission);

            $this->flash->setSuccess($this->lang->tr('Data successfully deleted'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when delete the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('permission_list')
            );
        }
    }
}
