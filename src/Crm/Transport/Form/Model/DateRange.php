<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\Model;

use App\Crm\Application\Utils\EquatableInterface;
use DateTime;

/**
 * @package App\Crm\Transport\Form\Model
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class DateRange implements EquatableInterface
{
    private ?DateTime $begin = null;
    private ?DateTime $end = null;

    public function __construct(
        private bool $resetTimes = true
    ) {
    }

    public function getBegin(): ?DateTime
    {
        return $this->begin;
    }

    public function setBegin(\DateTimeInterface $begin): self
    {
        $this->begin = DateTime::createFromInterface($begin);
        if ($this->resetTimes) {
            $this->begin->setTime(0, 0, 0);
        }

        return $this;
    }

    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = DateTime::createFromInterface($end);
        if ($this->resetTimes) {
            $this->end->setTime(23, 59, 59);
        }

        return $this;
    }

    public function isEqualTo(object $compare): bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        if (($this->getBegin() === null && $compare->getBegin() !== null) || ($this->getBegin() !== null && $compare->getBegin() === null)) {
            return false;
        }

        if (($this->getEnd() === null && $compare->getEnd() !== null) || ($this->getEnd() !== null && $compare->getEnd() === null)) {
            return false;
        }

        if ($this->getBegin() !== null && $compare->getBegin() !== null && $this->getBegin()->getTimestamp() !== $compare->getBegin()->getTimestamp()) {
            return false;
        }

        if ($this->getEnd() !== null && $compare->getEnd() !== null && $this->getEnd()->getTimestamp() !== $compare->getEnd()->getTimestamp()) {
            return false;
        }

        return true;
    }
}
