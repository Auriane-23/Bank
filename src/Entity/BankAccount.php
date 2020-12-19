<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=BankAccountRepository::class)
 */
class BankAccount
{
    const TYPE = ['CPT DEPOT', 'LIVRET A', 'LIVRET JEUNE', 'LEL', 'CEL', 'LEP'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bankAccounts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $accountNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="float")
     */
    private $bankBalance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="debitAccount")
     */
    private $transactions;

    /**
     * @ORM\Column(type="integer")
     */
    private $iban;

    /**
     * @ORM\Column(type="datetime")
     */
    private $transactionsDate;

    /**
     * @ORM\Column(type="float")
     */
    private $debit;

    /**
     * @ORM\Column(type="float")
     */
    private $credit;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBankBalance(): ?float
    {
        return $this->bankBalance;
    }

    public function setBankBalance(float $bankBalance): self
    {
        $this->bankBalance = $bankBalance;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setBankAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getBankAccount() === $this) {
                $transaction->setBankAccount(null);
            }
        }

        return $this;
    }

    public function getIban(): ?int
    {
        return $this->iban;
    }

    public function setIban(int $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getTransactionsDate(): ?\DateTimeInterface
    {
        return $this->transactionsDate;
    }

    public function setTransactionsDate(\DateTimeInterface $transactionsDate): self
    {
        $this->transactionsDate = $transactionsDate;

        return $this;
    }

    public function getDebit(): ?float
    {
        return $this->debit;
    }

    public function setDebit(float $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(float $credit): self
    {
        $this->credit = $credit;

        return $this;
    }
}
