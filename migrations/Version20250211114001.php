<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211114001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ALTER sexe TYPE TEXT');
        $this->addSql('ALTER TABLE animal ALTER statut TYPE TEXT');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal ALTER sexe TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE animal ALTER statut TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE VARCHAR(255)');
    }
}
