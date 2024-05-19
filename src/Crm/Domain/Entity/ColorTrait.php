<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Entity;

use App\Crm\Application\Export\Annotation as Exporter;
use App\Crm\Application\Validator\Constraints as Constraints;
use App\Crm\Constants;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait ColorTrait
{
    /**
     * The assigned color in HTML hex format, e.g. #dd1d00
     */
    #[ORM\Column(name: 'color', type: 'string', length: 7, nullable: true)]
    #[Serializer\Expose]
    #[Serializer\Groups(['Default'])]
    #[Exporter\Expose(label: 'color')]
    #[Constraints\HexColor]
    private ?string $color = null;

    public function getColor(): ?string
    {
        if ($this->color === Constants::DEFAULT_COLOR) {
            return null;
        }

        return $this->color;
    }

    public function hasColor(): bool
    {
        return $this->color !== null && $this->color !== Constants::DEFAULT_COLOR;
    }

    public function setColor(?string $color = null): void
    {
        $this->color = $color;
    }
}
