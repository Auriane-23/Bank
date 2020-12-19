<?php

namespace App\Controller;

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
        $beneficiary = new Beneficiary();
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
