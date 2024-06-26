<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\User\Domain\Entity\User;

class UserDeleteEvent extends AbstractUserEvent
{
    public function __construct(
        User $user,
        private ?User $replacement = null
    ) {
        parent::__construct($user);
    }

    public function getReplacementUser(): ?User
    {
        return $this->replacement;
    }
}
