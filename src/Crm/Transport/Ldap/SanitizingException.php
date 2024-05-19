<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Ldap;

/**
 * Class SanitizingException
 *
 * @package App\Crm\Transport\Ldap
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class SanitizingException extends \Exception
{
    public function __construct(private readonly \Exception $actualException, private string $secret)
    {
        parent::__construct(
            $this->stripSecret($actualException->getMessage(), $secret),
            $actualException->getCode()
        );
    }

    protected function stripSecret(string $message, string $secret): string
    {
        return str_replace($secret, '****', $message);
    }

    public function __toString(): string
    {
        return $this->stripSecret($this->actualException->__toString(), $this->secret);
    }
}
