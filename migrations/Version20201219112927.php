<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201219112927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, type VARCHAR(255) NOT NULL, account_number INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, bank_balance DOUBLE PRECISION NOT NULL, iban INT NOT NULL, transactions_date DATETIME NOT NULL, debit DOUBLE PRECISION NOT NULL, credit DOUBLE PRECISION NOT NULL, INDEX IDX_53A23E0A9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiary (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, bic VARCHAR(255) NOT NULL, iban INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, debit_account_id INT DEFAULT NULL, credit_account_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_723705D1204C4EAA (debit_account_id), INDEX IDX_723705D16813E404 (credit_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1204C4EAA FOREIGN KEY (debit_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16813E404 FOREIGN KEY (credit_account_id) REFERENCES bank_account (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1204C4EAA');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D16813E404');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A9D86650F');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE beneficiary');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE `user`');
    }
}
