<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\Model;

use App\User\Domain\Entity\User;

final class TotpActivation
{
    private ?string $code = null;

    public function __construct(
        private User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
}
