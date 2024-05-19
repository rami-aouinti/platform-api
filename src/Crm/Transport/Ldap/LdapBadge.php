<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Ldap;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

/**
 * Class LdapBadge
 *
 * @package App\Crm\Transport\Ldap
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class LdapBadge implements BadgeInterface
{
    private bool $resolved = false;

    public function markResolved(): void
    {
        $this->resolved = true;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
