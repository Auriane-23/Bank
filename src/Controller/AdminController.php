<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\BankAccount;
use App\Form\BankAccountType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    public function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() == 'ROLE_ADMIN') {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

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
        $bankAccounts = $this->getDoctrine()->getRepository(BankAccount::class)->findBy(['user' => $user]);
        
        return $this->render('admin/show.html.twig', [
            'user' => $user,
            'bankAccounts' => $bankAccounts
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

    public function newBankAccount(Request $request, User $user): Response
    {
        $bankAccount = new BankAccount();
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccount->setUser($user);
            $bankAccount->setAccountNumber(mt_rand());
            $bankAccount->setBankBalance(100);
            $bankAccount->setIban(mt_rand());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bankAccount);
            $entityManager->flush();

            return $this->redirectToRoute('admin-show', ['id' => $user->getId()]);
        }

        return $this->render('admin/newBankAccount.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    public function showBankAccount(BankAccount $bankAccount): Response
    {
        $user = $this->getUser();
        $accounts = $this->getDoctrine()->getRepository(BankAccount::class)->findBy(['id' => $bankAccount]);

        foreach ($accounts as $account) {
            $transactionsDebit = $account->getTransactionsDebit();
            $transactionsCredit = $account->getTransactionsCredit();
        }
        
        return $this->render('bank_account/show.html.twig', [
            'user' => $user,
            'transactionsDebit' => $transactionsDebit,
            'transactionsCredit' => $transactionsCredit
        ]);
    }
    
    public function editBankAccount(Request $request, BankAccount $bankAccount): Response
    {
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('admin/editBankAccount.html.twig', [
            'bankAccount' => $bankAccount,
            'form' => $form->createView(),
        ]);
    }

    
    public function deleteBankAccount($id, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $bankAccount = $manager->getRepository(BankAccount::class)->find($id);
        $manager->remove($bankAccount);
        $manager->flush();
        return $this->redirectToRoute('admin-show', ['id' => $user->getId()]);
    }
}
