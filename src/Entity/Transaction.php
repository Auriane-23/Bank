<?php

namespace App\Entity;

use DateTimeInterface;
use App\Entity\BankAccount;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="transactionsDebit")
     */
    private $debitAccount;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="transactionsCredit")
     */
    private $creditAccount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $transactionDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDebitAccount(): ?BankAccount
    {
        return $this->debitAccount;
    }

    public function setDebitAccount(BankAccount $debitAccount): self
    {
        $this->debitAccount = $debitAccount;

        return $this;
    }

    public function getCreditAccount(): ?BankAccount
    {
        return $this->creditAccount;
    }

    public function setCreditAccount(BankAccount $creditAccount): self
    {
        $this->creditAccount = $creditAccount;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionsDate(\DateTimeInterface $transactionDate): self
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }
}
