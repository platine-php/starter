<?php

declare(strict_types=1);

namespace Platine\App\Param;

use Platine\Framework\Form\Param\BaseParam;
use Platine\Orm\Entity;

/**
* @class ProductCategoryParam
* @package Platine\App\Param
* @template TEntity as Entity
*/
class ProductCategoryParam extends BaseParam
{
    /**
    * The name field
    * @var string
    */
    protected string $name;

    /**
    * The description field
    * @var string|null
    */
    protected ?string $description = null;


    /**
    * @param TEntity $entity
    * @return $this
    */
    public function fromEntity(Entity $entity): self
    {
        $this->name = $entity->name;
        $this->description = $entity->description;

        return $this;
    }

    /**
    * Return the name value
    * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }

   /**
    * Return the description value
    * @return string|null
    */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
    * Set the name value
    * @param string $name
    * @return $this
    */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

   /**
    * Set the description value
    * @param string|null $description
    * @return $this
    */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
