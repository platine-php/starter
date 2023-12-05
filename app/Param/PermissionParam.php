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
    * The depend field
    * @var string|null
    */
    protected ?string $depend = null;

    
    /**
    * @param TEntity $entity
    * @return $this
    */
   public function fromEntity(Entity $entity): self
   {
        $this->code = $entity->code;
        $this->description = $entity->description;
        $this->depend = $entity->depend;
        
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
    * Return the depend value 
    * @return string|null
    */
   public function getDepend(): ?string
   {
       return $this->depend;
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
   
   /**
    * Set the depend value 
    * @param string|null $depend 
    * @return $this
    */
   public function setDepend(?string $depend): self
   {
       $this->depend = $depend;
        
       return $this;
   }
   
   
}
