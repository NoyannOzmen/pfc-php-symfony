<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212193954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id SERIAL NOT NULL, association_id INT NOT NULL, espece_id INT NOT NULL, famille_id INT DEFAULT NULL, nom TEXT NOT NULL, race TEXT DEFAULT NULL, couleur TEXT NOT NULL, age INT NOT NULL, sexe TEXT NOT NULL, description TEXT NOT NULL, statut TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AAB231FEFB9C8A5 ON animal (association_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231F2D191E7A ON animal (espece_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231F97A77B84 ON animal (famille_id)');
        $this->addSql('CREATE TABLE animal_tag (id SERIAL NOT NULL, animal_id INT DEFAULT NULL, tag_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C07FC6D8E962C16 ON animal_tag (animal_id)');
        $this->addSql('CREATE INDEX IDX_9C07FC6DBAD26311 ON animal_tag (tag_id)');
        $this->addSql('CREATE TABLE association (id SERIAL NOT NULL, refuge_id INT NOT NULL, nom TEXT NOT NULL, responsable TEXT NOT NULL, rue TEXT NOT NULL, commune TEXT NOT NULL, code_postal TEXT NOT NULL, pays TEXT NOT NULL, siret TEXT NOT NULL, telephone TEXT NOT NULL, site TEXT DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCAD3404C1 ON association (refuge_id)');
        $this->addSql('CREATE TABLE demande (id SERIAL NOT NULL, famille_id INT NOT NULL, animal_id INT NOT NULL, statut_demande TEXT NOT NULL, date_debut TEXT NOT NULL, date_fin TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2694D7A597A77B84 ON demande (famille_id)');
        $this->addSql('CREATE INDEX IDX_2694D7A58E962C16 ON demande (animal_id)');
        $this->addSql('CREATE TABLE espece (id SERIAL NOT NULL, nom TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE famille (id SERIAL NOT NULL, accueillant_id INT NOT NULL, prenom TEXT DEFAULT NULL, nom TEXT NOT NULL, telephone TEXT NOT NULL, rue TEXT NOT NULL, commune TEXT NOT NULL, code_postal TEXT NOT NULL, pays TEXT NOT NULL, hebergement TEXT NOT NULL, terrain TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2473F2133742B4D5 ON famille (accueillant_id)');
        $this->addSql('CREATE TABLE media (id SERIAL NOT NULL, animal_id INT DEFAULT NULL, association_id INT DEFAULT NULL, url TEXT NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10C8E962C16 ON media (animal_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CEFB9C8A5 ON media (association_id)');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, nom TEXT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE utilisateur (id SERIAL NOT NULL, refuge_id INT DEFAULT NULL, accueillant_id INT DEFAULT NULL, email TEXT NOT NULL, roles JSON NOT NULL, password TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3AD3404C1 ON utilisateur (refuge_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B33742B4D5 ON utilisateur (accueillant_id)');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F2D191E7A FOREIGN KEY (espece_id) REFERENCES espece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCAD3404C1 FOREIGN KEY (refuge_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A597A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A58E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F2133742B4D5 FOREIGN KEY (accueillant_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3AD3404C1 FOREIGN KEY (refuge_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B33742B4D5 FOREIGN KEY (accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FEFB9C8A5');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F2D191E7A');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F97A77B84');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D8E962C16');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6DBAD26311');
        $this->addSql('ALTER TABLE association DROP CONSTRAINT FK_FD8521CCAD3404C1');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A597A77B84');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A58E962C16');
        $this->addSql('ALTER TABLE famille DROP CONSTRAINT FK_2473F2133742B4D5');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C8E962C16');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CEFB9C8A5');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT FK_1D1C63B3AD3404C1');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT FK_1D1C63B33742B4D5');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE animal_tag');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE espece');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE utilisateur');
    }
}
