<?php

declare(strict_types=1);

namespace Platine\App\Http\Action\Product;

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
use Platine\App\Model\Repository\ProductCategoryRepository;
use Platine\App\Model\Entity\ProductCategory;
use Platine\App\Param\ProductCategoryParam;
use Platine\App\Validator\ProductCategoryValidator;

/**
* @class CategoryAction
* @package Platine\App\Http\Action\Product
*/
class CategoryAction
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
    * The ProductCategoryRepository instance
    * @var ProductCategoryRepository
    */
    protected ProductCategoryRepository $productCategoryRepository;



    /**
    * Create new instance
    * @param Lang $lang
    * @param Pagination $pagination
    * @param Template $template
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param LoggerInterface $logger
    * @param ProductCategoryRepository $productCategoryRepository
    */
    public function __construct(
        Lang $lang,
        Pagination $pagination,
        Template $template,
        Flash $flash,
        RouteHelper $routeHelper,
        LoggerInterface $logger,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $this->lang = $lang;
        $this->pagination = $pagination;
        $this->template = $template;
        $this->flash = $flash;
        $this->routeHelper = $routeHelper;
        $this->logger = $logger;
        $this->productCategoryRepository = $productCategoryRepository;
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
        $totalItems = $this->productCategoryRepository->query()
                                               ->count('id');

        $currentPage = (int) $param->get('page', 1);

        $this->pagination->setTotalItems($totalItems)
                        ->setCurrentPage($currentPage);

        $limit = $this->pagination->getItemsPerPage();
        $offset = $this->pagination->getOffset();

        $results = $this->productCategoryRepository->query()
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->orderBy('name', 'ASC')
                                            ->all();

        $context['list'] = $results;
        $context['pagination'] = $this->pagination->render();


        return new TemplateResponse(
            $this->template,
            'product/category/list',
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

        /** @var ProductCategory|null $category */
        $category = $this->productCategoryRepository->find($id);

        if ($category === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        }
        $context['category'] = $category;

        return new TemplateResponse(
            $this->template,
            'product/category/detail',
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

        $formParam = new ProductCategoryParam($param->posts());
        $context['param'] = $formParam;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'product/category/create',
                $context
            );
        }

        $validator = new ProductCategoryValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'product/category/create',
                $context
            );
        }

        $entityExist = $this->productCategoryRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'product/category/create',
                $context
            );
        }

        /** @var ProductCategory $category */
        $category = $this->productCategoryRepository->create([
           'name' => $formParam->getName(),
        'description' => $formParam->getDescription(),
        ]);

        try {
            $this->productCategoryRepository->save($category);

            $this->flash->setSuccess($this->lang->tr('Data successfully created'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'product/category/create',
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

        /** @var ProductCategory|null $category */
        $category = $this->productCategoryRepository->find($id);

        if ($category === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        }
        $context['category'] = $category;
        $context['param'] = (new ProductCategoryParam())->fromEntity($category);
        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'product/category/update',
                $context
            );
        }
        $formParam = new ProductCategoryParam($param->posts());
        $context['param'] = $formParam;

        $validator = new ProductCategoryValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'product/category/update',
                $context
            );
        }

        $entityExist = $this->productCategoryRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null && $entityExist->id !== $id) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'product/category/update',
                $context
            );
        }

        $category->name = $formParam->getName();
        $category->description = $formParam->getDescription();

        try {
            $this->productCategoryRepository->save($category);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_detail', ['id' => $id])
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'product/category/update',
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

        /** @var ProductCategory|null $category */
        $category = $this->productCategoryRepository->find($id);

        if ($category === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        }

        try {
            $this->productCategoryRepository->delete($category);

            $this->flash->setSuccess($this->lang->tr('Data successfully deleted'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when delete the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_category_list')
            );
        }
    }
}
