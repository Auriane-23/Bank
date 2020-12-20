<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\BankAccount;
use App\Form\BankAccountType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BankAccountController extends AbstractController
{
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $bankAccount = new BankAccount();
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccount->setUser($this->getUser());
            $bankAccount->setAccountNumber(mt_rand());
            $bankAccount->setBankBalance(100);
            $bankAccount->setIban(mt_rand());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bankAccount);
            $entityManager->flush();

            return $this->redirectToRoute('account', ['id' => $user->getId()]);
        }

        return $this->render('bank_account/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    public function show(BankAccount $bankAccount): Response
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
}
