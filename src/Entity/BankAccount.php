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
    private $user;

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
    private $transactionsDebit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="creditAccount")
     */
    private $transactionsCredit;

    /**
     * @ORM\Column(type="integer")
     */
    private $iban;

    public function __construct()
    {
        $this->transactionsDebit = new ArrayCollection();
        $this->transactionsCredit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
    public function getTransactionsDebit(): Collection
    {
        return $this->transactionsDebit;
    }

    public function addTransactionDebit(Transaction $transactionDebit): self
    {
        if (!$this->transactionsDebit->contains($transactionDebit)) {
            $this->transactionsDebit[] = $transactionDebit;
            $transactionDebit->setBankAccount($this);
        }

        return $this;
    }

    public function removeTransactionDebit(Transaction $transactionDebit): self
    {
        if ($this->transactions->removeElement($transactionDebit)) {
            // set the owning side to null (unless already changed)
            if ($transactionDebit->getBankAccount() === $this) {
                $transactionDebit->setBankAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionsCredit(): Collection
    {
        return $this->transactionsCredit;
    }

    public function addTransactionCredit(Transaction $transactionCredit): self
    {
        if (!$this->transactionsCredit->contains($transactionCredit)) {
            $this->transactionsCredit[] = $transactionCredit;
            $transactionCredit->setBankAccount($this);
        }

        return $this;
    }

    public function removeTransactionCredit(Transaction $transactionCredit): self
    {
        if ($this->transactionsCredit->removeElement($transactionCredit)) {
            // set the owning side to null (unless already changed)
            if ($transactionCredit->getBankAccount() === $this) {
                $transactionCredit->setBankAccount(null);
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
}
