<?php

namespace App\Controller;

use App\Entity\BankAccount;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('account/index.html.twig', [
            'user' => $user
        ]);
    }

    public function show(): Response
    {
        $user = $this->getUser();

        $bankAccounts = $this->getDoctrine()->getRepository(BankAccount::class)->findAll();

        return $this->render('account/summary.html.twig', [
            'user' => $user,
            'bankAccounts' => $bankAccounts
        ]);
    }
}
