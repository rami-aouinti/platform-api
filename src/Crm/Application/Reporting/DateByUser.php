<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Reporting;

use App\User\Domain\Entity\User;

/**
 * @package App\Crm\Application\Reporting
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class DateByUser extends AbstractUserList
{
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
