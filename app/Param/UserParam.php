<?php

declare(strict_types=1);

namespace Platine\App\Param;

use Platine\Framework\Form\Param\BaseParam;
use Platine\Orm\Entity;

/**
* @class UserParam
* @package Platine\App\Param
* @template TEntity as Entity
*/
class UserParam extends BaseParam
{
    /**
    * The username field
    * @var string
    */
    protected string $username;

    /**
    * The lastname field
    * @var string
    */
    protected string $lastname;

    /**
    * The firstname field
    * @var string
    */
    protected string $firstname;

    /**
    * The email field
    * @var string
    */
    protected string $email;

    /**
    * The password field
    * @var string
    */
    protected string $password;

    /**
    * The password confirm field
    * @var string
    */
    protected string $passwordConfirm;

    /**
    * The status field
    * @var string
    */
    protected string $status;

    /**
    * The role field
    * @var string|null
    */
    protected ?string $role = null;

    /**
    * The roles field
    * @var array<int>
    */
    protected array $roles = [];


    /**
    * @param TEntity $entity
    * @return $this
    */
    public function fromEntity(Entity $entity): self
    {
        $this->username = $entity->username;
        $this->lastname = $entity->lastname;
        $this->firstname = $entity->firstname;
        $this->email = $entity->email;
        $this->password = $entity->password;
        $this->status = $entity->status;
        $this->role = $entity->role;

        return $this;
    }

    /**
    * Return the username value
    * @return string
    */
    public function getUsername(): string
    {
        return $this->username;
    }

   /**
    * Return the lastname value
    * @return string
    */
    public function getLastname(): string
    {
        return $this->lastname;
    }

   /**
    * Return the firstname value
    * @return string
    */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

   /**
    * Return the email value
    * @return string
    */
    public function getEmail(): string
    {
        return $this->email;
    }

   /**
    * Return the password value
    * @return string
    */
    public function getPassword(): string
    {
        return $this->password;
    }

   /**
    * Return the password confirm value
    * @return string
    */
    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }

   /**
    * Return the status value
    * @return string
    */
    public function getStatus(): string
    {
        return $this->status;
    }

   /**
    * Return the role value
    * @return string|null
    */
    public function getRole(): ?string
    {
        return $this->role;
    }

   /**
    * Return the roles
    * @return array<int>
    */
    public function getRoles(): array
    {
        return $this->roles;
    }


    /**
    * Set the username value
    * @param string $username
    * @return $this
    */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

   /**
    * Set the lastname value
    * @param string $lastname
    * @return $this
    */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

   /**
    * Set the firstname value
    * @param string $firstname
    * @return $this
    */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

   /**
    * Set the email value
    * @param string $email
    * @return $this
    */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

   /**
    * Set the password value
    * @param string $password
    * @return $this
    */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

   /**
    * Set the password confirm value
    * @param string $passwordConfirm
    * @return $this
    */
    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

   /**
    * Set the status value
    * @param string $status
    * @return $this
    */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

   /**
    * Set the role value
    * @param string|null $role
    * @return $this
    */
    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

   /**
    * Set the roles
    * @param array<int> $roles
    * @return $this
    */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
}
