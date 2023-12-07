<?php

declare(strict_types=1);

namespace Platine\App\Validator;

use Platine\App\Param\ProductCategoryParam;
use Platine\Framework\Form\Validator\AbstractValidator;
use Platine\Lang\Lang;
use Platine\Validator\Rule\MaxLength;
use Platine\Validator\Rule\MinLength;
use Platine\Validator\Rule\NotEmpty;

/**
* @class ProductCategoryValidator
* @package Platine\App\Validator
* @template TEntity as \Platine\Orm\Entity
*/
class ProductCategoryValidator extends AbstractValidator
{
    /**
    * The parameter instance
    * @var ProductCategoryParam<TEntity>
    */
    protected ProductCategoryParam $param;

    /**
    * Create new instance
    * @param ProductCategoryParam<TEntity> $param
    * @param Lang $lang
    */
    public function __construct(ProductCategoryParam $param, Lang $lang)
    {
        parent::__construct($lang);
        $this->param = $param;
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationData(): void
    {
        $this->addData('name', $this->param->getName());
        $this->addData('description', $this->param->getDescription());
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationRules(): void
    {
        $this->addRules('name', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(50),
        ]);

        $this->addRules('description', [
            new MinLength(2),
            new MaxLength(150),
        ]);
    }
}
