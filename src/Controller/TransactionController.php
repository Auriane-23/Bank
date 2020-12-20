<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Form\TransactionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    public function index(Request $request)
    {
        $user = $this->getUser();

        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccountDebit = $this->getDoctrine()->getRepository(BankAccount::class)->findOneBy(['id' => $transaction->getDebitAccount()]);
            $bankAccountCredit = $this->getDoctrine()->getRepository(BankAccount::class)->findOneBy(['id' => $transaction->getCreditAccount()]);

            $result = $bankAccountDebit->getBankBalance() - $transaction->getAmount();

            if ($result < 0 ) {
                $this->addFlash('danger', 'Vous ne pouvez pas mettre ce montant ! Votre compte tomberait en dessou de 0€');
            } else {
                $bankAccountDebit->setBankBalance($result);
                $bankAccountCredit->setBankBalance($bankAccountCredit->getBankBalance() + $transaction->getAmount());
            
                $transaction->setTransactionsDate(new \DateTime("now"));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($transaction);
                $entityManager->flush();

                return $this->redirectToRoute('account', ['id' => $user->getId()]);
            } 
        }

        return $this->render('transaction/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
