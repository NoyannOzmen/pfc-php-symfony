<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210164224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id SERIAL NOT NULL, refuge_id INT NOT NULL, espece_id INT NOT NULL, accueillant_id INT DEFAULT NULL, nom TEXT NOT NULL, race TEXT DEFAULT NULL, couleur TEXT NOT NULL, age INT NOT NULL, sexe TEXT NOT NULL, description TEXT NOT NULL, statut TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231F2D191E7A ON animal (espece_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231F3742B4D5 ON animal (accueillant_id)');
        $this->addSql('CREATE TABLE animal_tag (id SERIAL NOT NULL, animaux_taggés_id INT DEFAULT NULL, tags_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C07FC6D52043C93 ON animal_tag (animaux_taggés_id)');
        $this->addSql('CREATE INDEX IDX_9C07FC6D8D7B4FB4 ON animal_tag (tags_id)');
        $this->addSql('CREATE TABLE association (id SERIAL NOT NULL, nom TEXT NOT NULL, responsable TEXT NOT NULL, rue TEXT NOT NULL, commune TEXT NOT NULL, code_postal TEXT NOT NULL, pays TEXT NOT NULL, siret TEXT NOT NULL, telephone TEXT NOT NULL, site TEXT DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE demande (id SERIAL NOT NULL, potentiel_accueillant_id INT NOT NULL, animal_accueillable_id INT NOT NULL, statut_demande TEXT NOT NULL, date_debut TEXT NOT NULL, date_fin TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2694D7A5B15030D8 ON demande (potentiel_accueillant_id)');
        $this->addSql('CREATE INDEX IDX_2694D7A5FAF237B1 ON demande (animal_accueillable_id)');
        $this->addSql('CREATE TABLE espece (id SERIAL NOT NULL, nom TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE famille (id SERIAL NOT NULL, prenom TEXT DEFAULT NULL, nom TEXT NOT NULL, telephone TEXT NOT NULL, rue TEXT NOT NULL, commune TEXT NOT NULL, code_postal TEXT NOT NULL, pays TEXT NOT NULL, hebergement TEXT NOT NULL, terrain TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media (id SERIAL NOT NULL, animal_id INT DEFAULT NULL, association_id INT DEFAULT NULL, url TEXT NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10C8E962C16 ON media (animal_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CEFB9C8A5 ON media (association_id)');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, nom TEXT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE utilisateur (id SERIAL NOT NULL, refuge_id INT DEFAULT NULL, accueillant_id INT DEFAULT NULL, email TEXT NOT NULL, mot_de_passe TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3AD3404C1 ON utilisateur (refuge_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B33742B4D5 ON utilisateur (accueillant_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FAD3404C1 FOREIGN KEY (refuge_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F2D191E7A FOREIGN KEY (espece_id) REFERENCES espece (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F3742B4D5 FOREIGN KEY (accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D52043C93 FOREIGN KEY (animaux_taggés_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_tag ADD CONSTRAINT FK_9C07FC6D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5B15030D8 FOREIGN KEY (potentiel_accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5FAF237B1 FOREIGN KEY (animal_accueillable_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3AD3404C1 FOREIGN KEY (refuge_id) REFERENCES association (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B33742B4D5 FOREIGN KEY (accueillant_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FAD3404C1');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F2D191E7A');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F3742B4D5');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D52043C93');
        $this->addSql('ALTER TABLE animal_tag DROP CONSTRAINT FK_9C07FC6D8D7B4FB4');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A5B15030D8');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A5FAF237B1');
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
        $this->addSql('DROP TABLE messenger_messages');
    }
}
