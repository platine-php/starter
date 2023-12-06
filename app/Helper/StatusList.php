<?php

declare(strict_types=1);

namespace Platine\App\Helper;

use Platine\App\Enum\UserStatus;
use Platine\Lang\Lang;

/**
* @class AuthParam
* @package Platine\App\Param
*/
class StatusList
{
    /**
    * The Language instance
    * @var Lang
    */
    protected Lang $lang;

    /**
     * Create new instance
     * @param Lang $lang
     */
    public function __construct(Lang $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Return the user status
     * @return array<string, string>
     */
    public function getUserStatus(): array
    {
        return [
            UserStatus::ACTIVE => $this->lang->tr('Active'),
            UserStatus::LOCKED => $this->lang->tr('Locked'),
        ];
    }
}
