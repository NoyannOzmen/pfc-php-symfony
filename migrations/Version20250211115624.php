<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211115624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP CONSTRAINT fk_fd8521ccd96efec1');
        $this->addSql('DROP INDEX uniq_fd8521ccd96efec1');
        $this->addSql('ALTER TABLE association RENAME COLUMN identifiant_association_id TO refuge_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCAD3404C1 FOREIGN KEY (refuge_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCAD3404C1 ON association (refuge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCAD3404C1');
        $this->addSql('DROP INDEX UNIQ_FD8521CCAD3404C1');
        $this->addSql('ALTER TABLE association RENAME COLUMN refuge_id TO identifiant_association_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT fk_fd8521ccd96efec1 FOREIGN KEY (identifiant_association_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fd8521ccd96efec1 ON association (identifiant_association_id)');
    }
}
