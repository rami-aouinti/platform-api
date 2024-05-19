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
use App\Crm\Application\Utils\PageSetup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class AboutController
 *
 * @package App\Crm\Transport\Controller\Api\v1
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route(path: '/about')]
final class AboutController extends AbstractController
{
    public function __construct(private readonly string $projectDirectory)
    {
    }

    #[Route(path: '', name: 'about', methods: ['GET'])]
    public function license(): Response
    {
        $filename = $this->projectDirectory . '/LICENSE';

        try {
            $license = file_get_contents($filename);
        } catch (\Exception $ex) {
            $this->logException($ex);
            $license = false;
        }

        if (false === $license) {
            $license = 'Failed reading license file: ' . $filename . '. ' .
                'Check this instead: ' . Constants::GITHUB . 'blob/main/LICENSE';
        }

        return $this->render('about/license.html.twig', [
            'page_setup' => new PageSetup('about.title'),
            'license' => $license
        ]);
    }
}
