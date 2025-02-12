<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211111344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ALTER sexe TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE animal ALTER statut TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT fk_fd8521ccfb88e14f');
        $this->addSql('DROP INDEX uniq_fd8521ccfb88e14f');
        $this->addSql('ALTER TABLE association RENAME COLUMN utilisateur_id TO identifiant_association_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCD96EFEC1 FOREIGN KEY (identifiant_association_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCD96EFEC1 ON association (identifiant_association_id)');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT fk_2473f213fb88e14f');
        $this->addSql('DROP INDEX uniq_2473f213fb88e14f');
        $this->addSql('ALTER TABLE famille RENAME COLUMN utilisateur_id TO identifiant_famille_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F2131DD39C46 FOREIGN KEY (identifiant_famille_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2473F2131DD39C46 ON famille (identifiant_famille_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCD96EFEC1');
        $this->addSql('DROP INDEX UNIQ_FD8521CCD96EFEC1');
        $this->addSql('ALTER TABLE association RENAME COLUMN identifiant_association_id TO utilisateur_id');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT fk_fd8521ccfb88e14f FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fd8521ccfb88e14f ON association (utilisateur_id)');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE TEXT');
        $this->addSql('ALTER TABLE animal ALTER sexe TYPE TEXT');
        $this->addSql('ALTER TABLE animal ALTER statut TYPE TEXT');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT FK_2473F2131DD39C46');
        $this->addSql('DROP INDEX UNIQ_2473F2131DD39C46');
        $this->addSql('ALTER TABLE famille RENAME COLUMN identifiant_famille_id TO utilisateur_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT fk_2473f213fb88e14f FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2473f213fb88e14f ON famille (utilisateur_id)');
    }
}
