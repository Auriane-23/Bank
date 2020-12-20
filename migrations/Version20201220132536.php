<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220132536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP iban');
        $this->addSql('ALTER TABLE beneficiary ADD iban_id INT DEFAULT NULL, DROP bic, DROP iban');
        $this->addSql('ALTER TABLE beneficiary ADD CONSTRAINT FK_7ABF446A20D5BAF6 FOREIGN KEY (iban_id) REFERENCES bank_account (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7ABF446A20D5BAF6 ON beneficiary (iban_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account ADD iban INT NOT NULL');
        $this->addSql('ALTER TABLE beneficiary DROP FOREIGN KEY FK_7ABF446A20D5BAF6');
        $this->addSql('DROP INDEX UNIQ_7ABF446A20D5BAF6 ON beneficiary');
        $this->addSql('ALTER TABLE beneficiary ADD bic VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD iban INT NOT NULL, DROP iban_id');
    }
}
