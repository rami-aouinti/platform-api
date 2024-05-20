<?php

declare(strict_types=1);

namespace App\Shop\Transport\Controller\Api\v1;

use App\Shop\Domain\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Shop\Transport\Controller\Api\v1
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Products $product): Response
    {
        return $this->render('products/details.html.twig', compact('product'));
    }
}
