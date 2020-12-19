<?php

namespace App\Controller;

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
        $bankAccount = new BankAccount();
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccount->setUser($this->getUSer());
            $bankAccount->setAccountNumber(mt_rand());
            $bankAccount->setBankBalance(0);
            $bankAccount->setIban(mt_rand());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bankAccount);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('bank_account/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
