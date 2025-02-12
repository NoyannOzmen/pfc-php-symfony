<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210170249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT fk_6aab231f3742b4d5');
        $this->addSql('DROP INDEX idx_6aab231f3742b4d5');
        $this->addSql('ALTER TABLE animal RENAME COLUMN accueillant_id TO famille_id');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6AAB231F97A77B84 ON animal (famille_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F97A77B84');
        $this->addSql('DROP INDEX IDX_6AAB231F97A77B84');
        $this->addSql('ALTER TABLE animal RENAME COLUMN famille_id TO accueillant_id');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT fk_6aab231f3742b4d5 FOREIGN KEY (accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6aab231f3742b4d5 ON animal (accueillant_id)');
    }
}
