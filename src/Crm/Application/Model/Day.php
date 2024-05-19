<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Model;

use DateTimeImmutable;

/**
 * Class Day
 *
 * @package App\Crm\Application\Model
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class Day
{
    public function __construct(private DateTimeImmutable $day)
    {
    }

    public function getDay(): DateTimeImmutable
    {
        return $this->day;
    }
}
