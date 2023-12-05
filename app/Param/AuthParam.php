<?php

declare(strict_types=1);

namespace Platine\App\Param;

use Platine\Framework\Form\Param\BaseParam;

/**
* @class AuthParam
* @package Platine\App\Param
*
*/
class AuthParam extends BaseParam
{
    /**
    * The username field
    * @var string
    */
    protected string $username;

    /**
    * The password field
    * @var string
    */
    protected string $password;



    /**
    * Return the username value
    * @return string
    */
    public function getUsername(): string
    {
        return $this->username;
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
    * Set the password value
    * @param string $password
    * @return $this
    */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
