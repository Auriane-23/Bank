<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Beneficiary;
use App\Form\BeneficiaryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BeneficiaryController extends AbstractController
{
    public function new(Request $request)
    {
        $user = $this->getUser();

        $beneficiary = new Beneficiary();
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAccount = $this->getDoctrine()->getRepository(BankAccount::class)->findOneBy(['iban' => $beneficiary->getIban()]);
            
            $beneficiary->setIban($bankAccount->getIban());
            $beneficiary->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beneficiary);
            $entityManager->flush();

            return $this->redirectToRoute('transaction');
        }

        return $this->render('beneficiary/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
