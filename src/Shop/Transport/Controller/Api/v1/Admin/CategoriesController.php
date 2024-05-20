<?php

declare(strict_types=1);

namespace App\Shop\Transport\Controller\Api\v1\Admin;

use App\Shop\Domain\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoriesController
 *
 * @package App\Shop\Transport\Controller\Api\v1\Admin
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }
}
