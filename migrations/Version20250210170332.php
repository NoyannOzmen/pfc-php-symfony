<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210170332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT fk_2694d7a5b15030d8');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT fk_2694d7a5faf237b1');
        $this->addSql('DROP INDEX idx_2694d7a5faf237b1');
        $this->addSql('DROP INDEX idx_2694d7a5b15030d8');
        $this->addSql('ALTER TABLE demande ADD famille_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande ADD animal_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande DROP potentiel_accueillant_id');
        $this->addSql('ALTER TABLE demande DROP animal_accueillable_id');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A597A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A58E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2694D7A597A77B84 ON demande (famille_id)');
        $this->addSql('CREATE INDEX IDX_2694D7A58E962C16 ON demande (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A597A77B84');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A58E962C16');
        $this->addSql('DROP INDEX IDX_2694D7A597A77B84');
        $this->addSql('DROP INDEX IDX_2694D7A58E962C16');
        $this->addSql('ALTER TABLE demande ADD potentiel_accueillant_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande ADD animal_accueillable_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande DROP famille_id');
        $this->addSql('ALTER TABLE demande DROP animal_id');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT fk_2694d7a5b15030d8 FOREIGN KEY (potentiel_accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT fk_2694d7a5faf237b1 FOREIGN KEY (animal_accueillable_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2694d7a5faf237b1 ON demande (animal_accueillable_id)');
        $this->addSql('CREATE INDEX idx_2694d7a5b15030d8 ON demande (potentiel_accueillant_id)');
    }
}
