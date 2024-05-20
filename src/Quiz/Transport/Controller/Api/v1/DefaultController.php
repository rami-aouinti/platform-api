<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\Quiz\Domain\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ConfigurationRepository $configurationRepository)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('quiz_index');
        }

        return $this->render('default/index.html.twig', [
            'MAIN_ALLOW_USER_ACCOUNT_CREATION' => intval($configurationRepository->getValue('MAIN_ALLOW_USER_ACCOUNT_CREATION')),
        ]);
    }
}
