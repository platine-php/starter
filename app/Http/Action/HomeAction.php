<?php

/**
 * Platine Framework
 *
 * Platine Framework is a lightweight, high-performance, simple and elegant
 * PHP Web framework
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2020 Platine Framework
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *  @file WelcomeAction.php
 *
 *  The Platine Welcome action class
 *
 *  @package    Platine\App\Http\Action
 *  @author Platine Developers team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\App\Http\Action;

use Platine\App\Helper\StatusList;
use Platine\App\Model\Repository\ProductCategoryRepository;
use Platine\App\Model\Repository\ProductRepository;
use Platine\Database\Query\Expression;
use Platine\Framework\Auth\Repository\RoleRepository;
use Platine\Framework\Auth\Repository\UserRepository;
use Platine\Framework\Http\Response\TemplateResponse;
use Platine\Http\Handler\RequestHandlerInterface;
use Platine\Http\ResponseInterface;
use Platine\Http\ServerRequestInterface;
use Platine\Template\Template;

/**
 * @class HomeAction
 * @package Platine\App\Http\Action
 */
class HomeAction implements RequestHandlerInterface
{
    /**
     * Create new instance
     * @param Template $template
     * @param ProductCategoryRepository $productCategoryRepository
     * @param ProductRepository $productRepository
     * @param RoleRepository $roleRepository
     * @param UserRepository $userRepository
     * @param StatusList $statusList
     */
    public function __construct(
        protected Template $template,
        protected ProductCategoryRepository $productCategoryRepository,
        protected ProductRepository $productRepository,
        protected RoleRepository $roleRepository,
        protected UserRepository $userRepository,
        protected StatusList $statusList
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $context = [];
        $context['user_status'] = $this->statusList->getUserStatus();
        $context['total_user'] = $this->userRepository->query()
                                                      ->count('id');

        $context['total_role'] = $this->roleRepository->query()
                                                      ->count('id');

        $context['total_product_category'] = $this->productCategoryRepository->query()
                                                                            ->count('id');

        $context['total_product'] = $this->productRepository->query()
                                                            ->count('id');

        $context['users'] = $this->userRepository->query()
                                                ->offset(0)
                                                ->limit(5)
                                                ->orderBy('created_at', 'DESC')
                                                ->all();

        $context['products'] = $this->productRepository->query()
                                                        ->with('category')
                                                        ->offset(0)
                                                        ->limit(5)
                                                        ->orderBy('created_at', 'DESC')
                                                        ->all();

        $context['product_amounts'] = $this->productRepository->query()
                                                            ->sum(function (Expression $e) {
                                                                $e->column('price')
                                                                  ->op('*')
                                                                  ->column('quantity');
                                                            });

        return new TemplateResponse(
            $this->template,
            'home',
            $context
        );
    }
}
