<?php

declare(strict_types=1);

namespace Platine\App\Validator;

use Platine\App\Enum\UserStatus;
use Platine\App\Param\UserParam;
use Platine\Framework\Form\Validator\AbstractValidator;
use Platine\Lang\Lang;
use Platine\Validator\Rule\AlphaNumericDash;
use Platine\Validator\Rule\Email;
use Platine\Validator\Rule\InList;
use Platine\Validator\Rule\Matches;
use Platine\Validator\Rule\MaxLength;
use Platine\Validator\Rule\MinLength;
use Platine\Validator\Rule\NotEmpty;

/**
* @class UserValidator
* @package Platine\App\Validator
* @template TEntity as \Platine\Orm\Entity
*/
class UserValidator extends AbstractValidator
{
    /**
    * The parameter instance
    * @var UserParam<TEntity>
    */
    protected UserParam $param;

    /**
     * Whether password field can be empty (on update page)
     * @var bool
     */
    protected bool $ignorePassword;

    /**
    * Create new instance
    * @param UserParam<TEntity> $param
    * @param Lang $lang
    * @param bool $ignorePassword
    */
    public function __construct(UserParam $param, Lang $lang, bool $ignorePassword = false)
    {
        parent::__construct($lang);
        $this->param = $param;
        $this->ignorePassword = $ignorePassword;
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationData(): void
    {
        $this->addData('username', $this->param->getUsername());
        $this->addData('lastname', $this->param->getLastname());
        $this->addData('firstname', $this->param->getFirstname());
        $this->addData('email', $this->param->getEmail());
        $this->addData('password', $this->param->getPassword());
        $this->addData('password_confirm', $this->param->getPasswordConfirm());
        $this->addData('status', $this->param->getStatus());
        $this->addData('role', $this->param->getRole());
    }

    /**
    * {@inheritdoc}
    */
    public function setValidationRules(): void
    {
        $this->addRules('username', [
            new NotEmpty(),
            new MinLength(3),
            new MaxLength(20),
            new AlphaNumericDash(),
        ]);

        $this->addRules('lastname', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(30),
        ]);

        $this->addRules('firstname', [
            new NotEmpty(),
            new MinLength(2),
            new MaxLength(30),
        ]);

        $this->addRules('email', [
            new NotEmpty(),
            new Email(),
        ]);

        $this->addRules('password', [
            new MinLength(5),
            new MaxLength(100),
        ]);

        $this->addRules('password_confirm', [
            new MinLength(5),
            new MaxLength(100),
            new Matches('password'),
        ]);

        if ($this->ignorePassword === false) {
            $this->addRule('password', new NotEmpty());
            $this->addRule('password_confirm', new NotEmpty());
        }

        $this->addRules('status', [
            new NotEmpty(),
            new InList([UserStatus::ACTIVE, UserStatus::LOCKED]),
        ]);

        $this->addRules('role', [
            new MinLength(2),
            new MaxLength(50),
        ]);
    }
}
