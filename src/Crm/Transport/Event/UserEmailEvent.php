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
use Symfony\Component\Mime\Email;

class UserEmailEvent extends EmailEvent
{
    public function __construct(
        private User $user,
        Email $email
    ) {
        parent::__construct($email);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
