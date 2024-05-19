<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Controller\Api\v1;

use App\Crm\Constants;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @package App\Crm\Transport\Controller\Api\v1
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route(path: '/v1/about')]
final class AboutController extends AbstractController
{
    public function __construct(
        private readonly string $projectDirectory
    ) {
    }

    #[Route(path: '', name: 'about', methods: ['GET'])]
    public function license(): JsonResponse
    {
        $filename = $this->projectDirectory . '/LICENSE';

        try {
            $license = file_get_contents($filename);
        } catch (\Exception $ex) {
            $this->logException($ex);
            $license = false;
        }

        if ($license === false) {
            $license = 'Failed reading license file: ' . $filename . '. ' .
                'Check this instead: ' . Constants::GITHUB . 'blob/main/LICENSE';
        }

        return new JsonResponse($license);
    }
}
