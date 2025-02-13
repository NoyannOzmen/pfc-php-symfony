<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212200132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE animal_id_seq');
        $this->addSql('SELECT setval(\'animal_id_seq\', (SELECT MAX(id) FROM animal))');
        $this->addSql('ALTER TABLE animal ALTER id SET DEFAULT nextval(\'animal_id_seq\')');
        $this->addSql('CREATE SEQUENCE animal_tag_id_seq');
        $this->addSql('SELECT setval(\'animal_tag_id_seq\', (SELECT MAX(id) FROM animal_tag))');
        $this->addSql('ALTER TABLE animal_tag ALTER id SET DEFAULT nextval(\'animal_tag_id_seq\')');
        $this->addSql('ALTER TABLE animal_tag ALTER animal_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal_tag ALTER tag_id DROP NOT NULL');
        $this->addSql('CREATE SEQUENCE association_id_seq');
        $this->addSql('SELECT setval(\'association_id_seq\', (SELECT MAX(id) FROM association))');
        $this->addSql('ALTER TABLE association ALTER id SET DEFAULT nextval(\'association_id_seq\')');
        $this->addSql('ALTER TABLE association ALTER utilisateur_id DROP NOT NULL');
        $this->addSql('ALTER INDEX association_utilisateur_id_key RENAME TO UNIQ_FD8521CCFB88E14F');
        $this->addSql('CREATE SEQUENCE demande_id_seq');
        $this->addSql('SELECT setval(\'demande_id_seq\', (SELECT MAX(id) FROM demande))');
        $this->addSql('ALTER TABLE demande ALTER id SET DEFAULT nextval(\'demande_id_seq\')');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE TEXT');
        $this->addSql('ALTER TABLE demande ALTER date_fin TYPE TEXT');
        $this->addSql('CREATE SEQUENCE espece_id_seq');
        $this->addSql('SELECT setval(\'espece_id_seq\', (SELECT MAX(id) FROM espece))');
        $this->addSql('ALTER TABLE espece ALTER id SET DEFAULT nextval(\'espece_id_seq\')');
        $this->addSql('CREATE SEQUENCE famille_id_seq');
        $this->addSql('SELECT setval(\'famille_id_seq\', (SELECT MAX(id) FROM famille))');
        $this->addSql('ALTER TABLE famille ALTER id SET DEFAULT nextval(\'famille_id_seq\')');
        $this->addSql('ALTER TABLE famille ALTER utilisateur_id DROP NOT NULL');
        $this->addSql('ALTER INDEX famille_utilisateur_id_key RENAME TO UNIQ_2473F213FB88E14F');
        $this->addSql('CREATE SEQUENCE media_id_seq');
        $this->addSql('SELECT setval(\'media_id_seq\', (SELECT MAX(id) FROM media))');
        $this->addSql('ALTER TABLE media ALTER id SET DEFAULT nextval(\'media_id_seq\')');
        $this->addSql('ALTER TABLE media ALTER ordre SET NOT NULL');
        $this->addSql('CREATE SEQUENCE tag_id_seq');
        $this->addSql('SELECT setval(\'tag_id_seq\', (SELECT MAX(id) FROM tag))');
        $this->addSql('ALTER TABLE tag ALTER id SET DEFAULT nextval(\'tag_id_seq\')');
        $this->addSql('DROP INDEX utilisateur_email_key');
        $this->addSql('CREATE SEQUENCE utilisateur_id_seq');
        $this->addSql('SELECT setval(\'utilisateur_id_seq\', (SELECT MAX(id) FROM utilisateur))');
        $this->addSql('ALTER TABLE utilisateur ALTER id SET DEFAULT nextval(\'utilisateur_id_seq\')');
        $this->addSql('ALTER TABLE utilisateur ALTER roles TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE demande ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE demande ALTER date_debut TYPE DATE');
        $this->addSql('ALTER TABLE demande ALTER date_fin TYPE DATE');
        $this->addSql('ALTER TABLE tag ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE famille ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE famille ALTER utilisateur_id SET NOT NULL');
        $this->addSql('ALTER INDEX uniq_2473f213fb88e14f RENAME TO famille_utilisateur_id_key');
        $this->addSql('ALTER TABLE espece ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE animal_tag ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE animal_tag ALTER animal_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal_tag ALTER tag_id SET NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE utilisateur ALTER roles TYPE TEXT');
        $this->addSql('CREATE UNIQUE INDEX utilisateur_email_key ON utilisateur (email)');
        $this->addSql('ALTER TABLE association ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE association ALTER utilisateur_id SET NOT NULL');
        $this->addSql('ALTER INDEX uniq_fd8521ccfb88e14f RENAME TO association_utilisateur_id_key');
        $this->addSql('ALTER TABLE animal ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE media ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE media ALTER ordre DROP NOT NULL');
    }
}
