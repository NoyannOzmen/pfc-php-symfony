<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210170146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT fk_6aab231fad3404c1');
        $this->addSql('DROP INDEX idx_6aab231fad3404c1');
        $this->addSql('ALTER TABLE animal RENAME COLUMN refuge_id TO association_id');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6AAB231FEFB9C8A5 ON animal (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FEFB9C8A5');
        $this->addSql('DROP INDEX IDX_6AAB231FEFB9C8A5');
        $this->addSql('ALTER TABLE animal RENAME COLUMN association_id TO refuge_id');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT fk_6aab231fad3404c1 FOREIGN KEY (refuge_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6aab231fad3404c1 ON animal (refuge_id)');
    }
}
