<?php

declare(strict_types=1);

namespace Platine\App\Param;

use Platine\Framework\Form\Param\BaseParam;
use Platine\Orm\Entity;

/**
* @class PermissionParam
* @package Platine\App\Param
* @template TEntity as Entity
*/
class PermissionParam extends BaseParam
{
    /**
    * The code field
    * @var string
    */
    protected string $code;

    /**
    * The description field
    * @var string
    */
    protected string $description;

    /**
    * @param TEntity $entity
    * @return $this
    */
    public function fromEntity(Entity $entity): self
    {
        $this->code = $entity->code;
        $this->description = $entity->description;

        return $this;
    }

    /**
    * Return the code value
    * @return string
    */
    public function getCode(): string
    {
        return $this->code;
    }

   /**
    * Return the description value
    * @return string
    */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
    * Set the code value
    * @param string $code
    * @return $this
    */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

   /**
    * Set the description value
    * @param string $description
    * @return $this
    */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
