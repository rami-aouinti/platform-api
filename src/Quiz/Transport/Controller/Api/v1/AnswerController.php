<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\Quiz\Domain\Entity\Answer;
use App\Quiz\Domain\Repository\AnswerRepository;
use App\Quiz\Transport\Form\AnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/", name="answer_index", methods="GET")
     */
    public function index(AnswerRepository $answerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        return $this->render('answer/index.html.twig', [
            'answers' => $answerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="answer_new", methods="GET|POST")
     */
    public function new(Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer, [
            'form_type' => 'teacher',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($answer);
            $em->flush();

            $this->addFlash('success', sprintf($translator->trans('Answer #%s is created.'), $answer->getId()));

            return $this->redirectToRoute('answer_index');
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="answer_edit", methods="GET|POST")
     */
    public function edit(Request $request, Answer $answer, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        $form = $this->createForm(AnswerType::class, $answer, [
            'form_type' => 'teacher',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', sprintf($translator->trans('Answer #%s is updated.'), $answer->getId()));

            return $this->redirectToRoute('answer_edit', [
                'id' => $answer->getId(),
            ]);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="answer_delete", methods="POST")
     */
    public function delete(Request $request, Answer $answer, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        if ($this->isCsrfTokenValid('delete' . $answer->getId(), $request->request->get('_token'))) {
            $em->remove($answer);
            $em->flush();

            $this->addFlash('success', sprintf($translator->trans('Answer #%s is deleted.'), $answer->getId()));
        }

        return $this->redirectToRoute('answer_index');
    }

    /**
     * @Route("/{id}", name="answer_show", methods="GET")
     */
    public function show(Answer $answer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Access not allowed');

        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }
}
