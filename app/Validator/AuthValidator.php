<?php

declare(strict_types=1);

namespace Platine\App\Validator;

use Platine\App\Param\AuthParam;
use Platine\Framework\Form\Validator\AbstractValidator;
use Platine\Lang\Lang;
use Platine\Validator\Rule\AlphaNumericDash;
use Platine\Validator\Rule\MaxLength;
use Platine\Validator\Rule\MinLength;
use Platine\Validator\Rule\NotEmpty;

/**
* @class AuthValidator
* @package Platine\App\Validator
* @template TEntity as \Platine\Orm\Entity
*/
class AuthValidator extends AbstractValidator
{
    /**
    * The parameter instance
    * @var AuthParam<TEntity>
    */
    protected AuthParam $param;

    /**
    * Create new instance
    * @param AuthParam<TEntity> $param
    * @param Lang $lang
    */
    public function __construct(AuthParam $param, Lang $lang)
    {
        parent::__construct($lang);
        $this->param = $param;
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationData(): void
    {
        $this->addData('username', $this->param->getUsername());
        $this->addData('password', $this->param->getPassword());
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationRules(): void
    {
        $this->addRules('username', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(20),
            new AlphaNumericDash(),
        ]);

        $this->addRules('password', [
            new NotEmpty(),
            new MinLength(5),
            new MaxLength(100),
        ]);
    }
}
