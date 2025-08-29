<?php

declare(strict_types=1);

namespace Platine\App\Validator;

use Platine\App\Param\ProductParam;
use Platine\Framework\Form\Validator\AbstractValidator;
use Platine\Lang\Lang;
use Platine\Validator\Rule\Integer;
use Platine\Validator\Rule\MaxLength;
use Platine\Validator\Rule\Min;
use Platine\Validator\Rule\MinLength;
use Platine\Validator\Rule\NotEmpty;
use Platine\Validator\Rule\Number;

/**
* @class ProductValidator
* @package Platine\App\Validator
* @template TEntity as \Platine\Orm\Entity
*/
class ProductValidator extends AbstractValidator
{
    /**
    * Create new instance
    * @param ProductParam<TEntity> $param
    * @param Lang $lang
    */
    public function __construct(protected ProductParam $param, Lang $lang)
    {
        parent::__construct($lang);
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationData(): void
    {
        $this->addData('name', $this->param->getName());
        $this->addData('description', $this->param->getDescription());
        $this->addData('price', $this->param->getPrice());
        $this->addData('quantity', $this->param->getQuantity());
        $this->addData('category', $this->param->getCategory());
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

        $this->addRules('price', [
            new NotEmpty(),
            new Number(),
            new Min(0),
        ]);

        $this->addRules('quantity', [
            new NotEmpty(),
            new Number(),
            new Min(0),
        ]);

        $this->addRules('category', [
            new NotEmpty(),
            new Integer(),
        ]);
    }
}
