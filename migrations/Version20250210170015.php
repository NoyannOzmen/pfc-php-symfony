<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210170015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT fk_9c07fc6d5eb747a3');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT fk_9c07fc6d5da88751');
        $this->addSql('DROP INDEX idx_9c07fc6d5da88751');
        $this->addSql('DROP INDEX idx_9c07fc6d5eb747a3');
        $this->addSql('ALTER TABLE animal_tag ADD animal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag ADD tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag DROP animal_id_id');
        $this->addSql('ALTER TABLE animal_tag DROP tag_id_id');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9C07FC6D8E962C16 ON animal_tag (animal_id)');
        $this->addSql('CREATE INDEX IDX_9C07FC6DBAD26311 ON animal_tag (tag_id)');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT fk_fd8521ccb981c689');
        $this->addSql('DROP INDEX uniq_fd8521ccb981c689');
        $this->addSql('ALTER TABLE association RENAME COLUMN utilisateur_id_id TO utilisateur_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCFB88E14F ON association (utilisateur_id)');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT fk_2473f213b981c689');
        $this->addSql('DROP INDEX uniq_2473f213b981c689');
        $this->addSql('ALTER TABLE famille RENAME COLUMN utilisateur_id_id TO utilisateur_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2473F213FB88E14F ON famille (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D8E962C16');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6DBAD26311');
        $this->addSql('DROP INDEX IDX_9C07FC6D8E962C16');
        $this->addSql('DROP INDEX IDX_9C07FC6DBAD26311');
        $this->addSql('ALTER TABLE animal_tag ADD animal_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag ADD tag_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag DROP animal_id');
        $this->addSql('ALTER TABLE animal_tag DROP tag_id');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT fk_9c07fc6d5eb747a3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT fk_9c07fc6d5da88751 FOREIGN KEY (tag_id_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9c07fc6d5da88751 ON animal_tag (tag_id_id)');
        $this->addSql('CREATE INDEX idx_9c07fc6d5eb747a3 ON animal_tag (animal_id_id)');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT FK_2473F213FB88E14F');
        $this->addSql('DROP INDEX UNIQ_2473F213FB88E14F');
        $this->addSql('ALTER TABLE famille RENAME COLUMN utilisateur_id TO utilisateur_id_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT fk_2473f213b981c689 FOREIGN KEY (utilisateur_id_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2473f213b981c689 ON famille (utilisateur_id_id)');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCFB88E14F');
        $this->addSql('DROP INDEX UNIQ_FD8521CCFB88E14F');
        $this->addSql('ALTER TABLE association RENAME COLUMN utilisateur_id TO utilisateur_id_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT fk_fd8521ccb981c689 FOREIGN KEY (utilisateur_id_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fd8521ccb981c689 ON association (utilisateur_id_id)');
    }
}
