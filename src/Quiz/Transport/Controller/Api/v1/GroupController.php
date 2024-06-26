<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\Quiz\Domain\Entity\Group;
use App\Quiz\Domain\Repository\GroupRepository;
use App\Quiz\Transport\Form\GroupType;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function index(GroupRepository $groupRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET|POST"})
     * @throws NotSupported
     */
    public function new(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($group);
            ////////////////////////////////////////////////
            // To save "Inverse Side" of ManyToMany relation
            $usersInGroup = $group->getUsers();
            $allUsers = $userRepository->findAll($this->isGranted('ROLE_TEACHER'), $this->isGranted('ROLE_ADMIN'));
            foreach ($allUsers as $user) {
                if (!$usersInGroup->contains($user)) {
                    $user->removeGroup($group);
                    $em->persist($user);
                }
            }
            foreach ($usersInGroup as $user) {
                $user->addGroup($group);
                $em->persist($user);
            }
            ////////////////////////////////////////////////
            $em->flush();

            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     *
     * @throws NotSupported
     */
    public function edit(Request $request, Group $group, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($group);
            ////////////////////////////////////////////////
            // To save "Inverse Side" of ManyToMany relation
            $usersInGroup = $group->getUsers();
            $allUsers = $userRepository->findAll($this->isGranted('ROLE_TEACHER'), $this->isGranted('ROLE_ADMIN'));
            foreach ($allUsers as $user) {
                if (!$usersInGroup->contains($user)) {
                    $user->removeGroup($group);
                    $em->persist($user);
                }
            }
            foreach ($usersInGroup as $user) {
                $user->addGroup($group);
                $em->persist($user);
            }
            ////////////////////////////////////////////////
            $em->flush();

            return $this->redirectToRoute('user_index', [
                'group' => $group->getId(),
            ]);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_delete", methods={"POST"})
     */
    public function delete(Request $request, Group $group, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute('group_index');
    }

    /**
     * @Route("remove_user/{id}", name="group_remove_user", methods={"GET"})
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function remove_user(Request $request, Group $group, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access not allowed');

        $groupId = $group->getId();
        $userId = $request->query->get('user');
        if ($userId > 0) {
            $user = $userRepository->find($userId);
            $group->removeUser($user);
        }
        $em->flush();

        return $this->redirectToRoute('user_index', [
            'group' => $groupId,
        ]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     */
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }
}
