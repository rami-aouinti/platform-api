<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * This event can be used, to dynamically change the displayed permissions in the permission screen.
 */
final class PermissionsEvent extends Event
{
    /**
     * @var array<string, array<string>>
     */
    private array $sections = [];

    /**
     * @param string[] $permissions
     */
    public function addPermissions(string $section, array $permissions): self
    {
        $this->sections[$section] = $permissions;

        return $this;
    }

    public function removePermission(string $section, string $permission): self
    {
        if (\array_key_exists($section, $this->sections)) {
            if (($key = array_search($permission, $this->sections[$section])) !== false) {
                unset($this->sections[$section][$key]);
            }
        }

        return $this;
    }

    public function hasSection(string $section): bool
    {
        return \array_key_exists($section, $this->sections);
    }

    public function removeSection(string $section): self
    {
        if (\array_key_exists($section, $this->sections)) {
            unset($this->sections[$section]);
        }

        return $this;
    }

    public function getSection(string $section): ?array
    {
        if (\array_key_exists($section, $this->sections)) {
            return $this->sections[$section];
        }

        return null;
    }

    public function getPermissions(): array
    {
        return $this->sections;
    }
}
