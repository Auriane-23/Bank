<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\BankAccount;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    public function newUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function showUser(User $user): Response
    {
        //$repository = $this->getDoctrine()->getRepository(BankAccount::class);
        //$bankAccounts = $repository->findBy(['user' => $user]);

        //dd($user->getbankAccounts());

        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }
    
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('admin/edit.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function deleteUser(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin-index');
    }

}
