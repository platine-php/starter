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
use Platine\App\Model\Repository\ProductRepository;
use Platine\App\Model\Entity\Product;
use Platine\App\Param\ProductParam;
use Platine\App\Validator\ProductValidator;

/**
* @class ProductAction
* @package Platine\App\Http\Action\Product
*/
class ProductAction
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
    * The ProductRepository instance
    * @var ProductRepository
    */
    protected ProductRepository $productRepository;



    /**
    * Create new instance
    * @param Lang $lang
    * @param Pagination $pagination
    * @param Template $template
    * @param Flash $flash
    * @param RouteHelper $routeHelper
    * @param LoggerInterface $logger
    * @param ProductCategoryRepository $productCategoryRepository
    * @param ProductRepository $productRepository
    */
    public function __construct(
        Lang $lang,
        Pagination $pagination,
        Template $template,
        Flash $flash,
        RouteHelper $routeHelper,
        LoggerInterface $logger,
        ProductCategoryRepository $productCategoryRepository,
        ProductRepository $productRepository
    ) {
        $this->lang = $lang;
        $this->pagination = $pagination;
        $this->template = $template;
        $this->flash = $flash;
        $this->routeHelper = $routeHelper;
        $this->logger = $logger;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productRepository = $productRepository;
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

        $filters = [];
        $filtersParam = [
            'category',
        ];

        foreach ($filtersParam as $p) {
            $value = $param->get($p);
            if (strlen((string) $value) > 0) {
                $filters[$p] = $value;
            }
        }

        $totalItems = $this->productRepository->query()
                                              ->filter($filters)
                                               ->count('id');

        $currentPage = (int) $param->get('page', 1);

        $this->pagination->setTotalItems($totalItems)
                        ->setCurrentPage($currentPage);

        $limit = $this->pagination->getItemsPerPage();
        $offset = $this->pagination->getOffset();

        $results = $this->productRepository->query()
                                            ->with('category')
                                            ->filter($filters)
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->orderBy('name', 'ASC')
                                            ->all();

        $categories = $this->productCategoryRepository->orderBy('name')
                                                      ->all();

        $context['categories'] = $categories;

        $context['filters'] = $filters;
        $context['list'] = $results;
        $context['pagination'] = $this->pagination->render();


        return new TemplateResponse(
            $this->template,
            'product/list',
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

        /** @var Product|null $product */
        $product = $this->productRepository->find($id);

        if ($product === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        }
        $context['product'] = $product;

        return new TemplateResponse(
            $this->template,
            'product/detail',
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

        $formParam = new ProductParam($param->posts());
        $context['param'] = $formParam;

        $categories = $this->productCategoryRepository->orderBy('name')
                                                      ->all();

        $context['categories'] = $categories;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'product/create',
                $context
            );
        }

        $validator = new ProductValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'product/create',
                $context
            );
        }

        $entityExist = $this->productRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'product/create',
                $context
            );
        }

        /** @var Product $product */
        $product = $this->productRepository->create([
           'name' => $formParam->getName(),
        'description' => $formParam->getDescription(),
        'price' => $formParam->getPrice(),
        'quantity' => $formParam->getQuantity(),
        'product_category_id' => $formParam->getCategory(),
        ]);

        try {
            $this->productRepository->save($product);

            $this->flash->setSuccess($this->lang->tr('Data successfully created'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'product/create',
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

        /** @var Product|null $product */
        $product = $this->productRepository->find($id);

        if ($product === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        }
        $context['product'] = $product;
        $context['param'] = (new ProductParam())->fromEntity($product);

        $categories = $this->productCategoryRepository->orderBy('name')
                                                      ->all();

        $context['categories'] = $categories;

        if ($request->getMethod() === 'GET') {
            return new TemplateResponse(
                $this->template,
                'product/update',
                $context
            );
        }
        $formParam = new ProductParam($param->posts());
        $context['param'] = $formParam;

        $validator = new ProductValidator($formParam, $this->lang);
        if ($validator->validate() === false) {
            $context['errors'] = $validator->getErrors();

            return new TemplateResponse(
                $this->template,
                'product/update',
                $context
            );
        }

        $entityExist = $this->productRepository->findBy([
                                               'name' => $formParam->getName(),
                                           ]);

        if ($entityExist !== null && $entityExist->id !== $id) {
            $this->flash->setError($this->lang->tr('This record already exist'));

            return new TemplateResponse(
                $this->template,
                'product/update',
                $context
            );
        }

        $product->name = $formParam->getName();
        $product->description = $formParam->getDescription();
        $product->price = $formParam->getPrice();
        $product->quantity = $formParam->getQuantity();
        $product->product_category_id = $formParam->getCategory();

        try {
            $this->productRepository->save($product);

            $this->flash->setSuccess($this->lang->tr('Data successfully updated'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_detail', ['id' => $id])
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when saved the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new TemplateResponse(
                $this->template,
                'product/update',
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

        /** @var Product|null $product */
        $product = $this->productRepository->find($id);

        if ($product === null) {
            $this->flash->setError($this->lang->tr('This record doesn\'t exist'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        }

        try {
            $this->productRepository->delete($product);

            $this->flash->setSuccess($this->lang->tr('Data successfully deleted'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        } catch (Exception $ex) {
            $this->logger->error('Error when delete the data {error}', ['error' => $ex->getMessage()]);

            $this->flash->setError($this->lang->tr('Data processing error'));

            return new RedirectResponse(
                $this->routeHelper->generateUrl('product_list')
            );
        }
    }
}
