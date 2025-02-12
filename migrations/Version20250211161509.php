<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211161509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT fk_2473f2131dd39c46');
        $this->addSql('DROP INDEX uniq_2473f2131dd39c46');
        $this->addSql('ALTER TABLE famille RENAME COLUMN identifiant_famille_id TO accueillant_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F2133742B4D5 FOREIGN KEY (accueillant_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2473F2133742B4D5 ON famille (accueillant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT FK_2473F2133742B4D5');
        $this->addSql('DROP INDEX UNIQ_2473F2133742B4D5');
        $this->addSql('ALTER TABLE famille RENAME COLUMN accueillant_id TO identifiant_famille_id');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT fk_2473f2131dd39c46 FOREIGN KEY (identifiant_famille_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2473f2131dd39c46 ON famille (identifiant_famille_id)');
    }
}
