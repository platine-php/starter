<?php

declare(strict_types=1);

namespace Platine\App\Param;

use Platine\Framework\Form\Param\BaseParam;
use Platine\Orm\Entity;

/**
* @class ProductParam
* @package Platine\App\Param
* @template TEntity as Entity
*/
class ProductParam extends BaseParam
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
    * The price field
    * @var float
    */
    protected float $price = 0;

    /**
    * The quantity field
    * @var float
    */
    protected float $quantity = 0;

    /**
    * The category field
    * @var int
    */
    protected int $category;


    /**
    * @param TEntity $entity
    * @return $this
    */
    public function fromEntity(Entity $entity): self
    {
        $this->name = $entity->name;
        $this->description = $entity->description;
        $this->price = $entity->price;
        $this->quantity = $entity->quantity;
        $this->category = $entity->product_category_id;

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
    * Return the price value
    * @return float
    */
    public function getPrice(): float
    {
        return $this->price;
    }

   /**
    * Return the quantity value
    * @return float
    */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

   /**
    * Return the category value
    * @return int
    */
    public function getCategory(): int
    {
        return $this->category;
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

   /**
    * Set the price value
    * @param float $price
    * @return $this
    */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

   /**
    * Set the quantity value
    * @param float $quantity
    * @return $this
    */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

   /**
    * Set the category value
    * @param int $category
    * @return $this
    */
    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }
}
