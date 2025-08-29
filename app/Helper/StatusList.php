<?php

declare(strict_types=1);

namespace Platine\App\Helper;

use Platine\Framework\Auth\Enum\UserStatus;
use Platine\Lang\Lang;

/**
* @class AuthParam
* @package Platine\App\Param
*/
class StatusList
{
    /**
     * Create new instance
     * @param Lang $lang
     */
    public function __construct(protected Lang $lang)
    {
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
