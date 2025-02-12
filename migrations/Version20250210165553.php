<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210165553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT fk_9c07fc6d52043c93');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT fk_9c07fc6d8d7b4fb4');
        $this->addSql('DROP INDEX idx_9c07fc6d8d7b4fb4');
        $this->addSql('DROP INDEX idx_9c07fc6d52043c93');
        $this->addSql('ALTER TABLE animal_tag ADD animal_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag ADD tag_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag DROP tags_id');
        $this->addSql('ALTER TABLE animal_tag DROP "animaux_taggés_id"');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D5EB747A3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D5DA88751 FOREIGN KEY (tag_id_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9C07FC6D5EB747A3 ON animal_tag (animal_id_id)');
        $this->addSql('CREATE INDEX IDX_9C07FC6D5DA88751 ON animal_tag (tag_id_id)');
        $this->addSql('ALTER TABLE association ADD utilisateur_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCB981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCB981C689 ON association (utilisateur_id_id)');
        $this->addSql('ALTER TABLE famille ADD utilisateur_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213B981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2473F213B981C689 ON famille (utilisateur_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D5EB747A3');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D5DA88751');
        $this->addSql('DROP INDEX IDX_9C07FC6D5EB747A3');
        $this->addSql('DROP INDEX IDX_9C07FC6D5DA88751');
        $this->addSql('ALTER TABLE animal_tag ADD tags_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag ADD "animaux_taggés_id" INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_tag DROP animal_id_id');
        $this->addSql('ALTER TABLE animal_tag DROP tag_id_id');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT fk_9c07fc6d52043c93 FOREIGN KEY ("animaux_taggés_id") REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT fk_9c07fc6d8d7b4fb4 FOREIGN KEY (tags_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9c07fc6d8d7b4fb4 ON animal_tag (tags_id)');
        $this->addSql('CREATE INDEX idx_9c07fc6d52043c93 ON animal_tag (animaux_taggés_id)');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCB981C689');
        $this->addSql('DROP INDEX UNIQ_FD8521CCB981C689');
        $this->addSql('ALTER TABLE association DROP utilisateur_id_id');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT FK_2473F213B981C689');
        $this->addSql('DROP INDEX UNIQ_2473F213B981C689');
        $this->addSql('ALTER TABLE famille DROP utilisateur_id_id');
    }
}
