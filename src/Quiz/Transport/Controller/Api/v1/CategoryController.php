<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Repository\CategoryRepository;
use App\Quiz\Transport\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods="GET")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access not allowed');

        $categories = $categoryRepository->findAll($this->isGranted('ROLE_TEACHER'), $this->isGranted('ROLE_ADMIN'));

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods="GET|POST")
     */
    public function new(Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        $category = $em->getRepository(Category::class)->create();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedBy($this->getUser());
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', sprintf($translator->trans('Category "%s" is created.'), $category->getShortname()));

            // return $this->redirectToRoute('category_index');
            return $this->redirectToRoute('question_new', [
                'category' => $category->getId(),
            ]);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods="GET|POST")
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', sprintf($translator->trans('Category "%s" is updated.'), $category->getShortname()));

            return $this->redirectToRoute('category_edit', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods="POST")
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', sprintf($translator->trans('Category "%s" is deleted.'), $category->getShortname()));
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * @Route("/{id}", name="category_show", methods="GET")
     */
    public function show(Category $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access not allowed');

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
