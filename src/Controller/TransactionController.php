<?php

namespace App\Controller;

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
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beneficiary);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('transaction/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
