<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use Symfony\Component\Mime\Email;
use Symfony\Contracts\EventDispatcher\Event;

class EmailEvent extends Event
{
    public function __construct(
        private Email $email
    ) {
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
