<?php

declare(strict_types=1);

namespace Platine\App\Validator; 

use Platine\App\Param\PermissionParam;
use Platine\Framework\Form\Validator\AbstractValidator;
use Platine\Lang\Lang;
use Platine\Validator\Rule\AlphaNumericDash;
use Platine\Validator\Rule\MaxLength;
use Platine\Validator\Rule\MinLength;
use Platine\Validator\Rule\NotEmpty;


/**
* @class PermissionValidator
* @package Platine\App\Validator
* @template TEntity as \Platine\Orm\Entity
*/
class PermissionValidator extends AbstractValidator
{
    /**
    * The parameter instance
    * @var PermissionParam<TEntity>
    */
    protected PermissionParam $param;

    /**
    * Create new instance
    * @param PermissionParam<TEntity> $param
    * @param Lang $lang
    */
   public function __construct(PermissionParam $param, Lang $lang)
   {
       parent::__construct($lang);
       $this->param = $param;
   }

    /**
    * {@inheritdoc}
    */
    public function setValidationData(): void
    {
        $this->addData('code', $this->param->getCode());
        $this->addData('description', $this->param->getDescription());
        $this->addData('depend', $this->param->getDepend());
        
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationRules(): void
    {
        $this->addRules('code', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(20),
            new AlphaNumericDash(),
        ]);
      
        $this->addRules('description', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(100),
        ]);
      
        $this->addRules('depend', [
            new MinLength(2),
            new MaxLength(20),
        ]);
      
        
    }
}