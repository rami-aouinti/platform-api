<?php

declare(strict_types=1);

/*
 * This file is part of the Platform time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm;

/**
 * Some "very" global constants for Platform.
 */
class Constants
{
    /**
     * The current release version
     */
    public const VERSION = '2.16.1';
    /**
     * The current release: major * 10000 + minor * 100 + patch
     */
    public const VERSION_ID = 21601;
    /**
     * The software name
     */
    public const SOFTWARE = 'Platform';
    /**
     * Used in multiple views
     */
    public const GITHUB = 'https://github.com/rami-aouinti';
    /**
     * The GitHub repository name
     */
    public const GITHUB_REPO = 'platform-api';
    /**
     * Homepage, used in multiple views
     */
    public const HOMEPAGE = 'https://ramyworld.de/';
    /**
     * Default color for Customer, Project and Activity entities
     */
    public const DEFAULT_COLOR = '#d2d6de';
}
