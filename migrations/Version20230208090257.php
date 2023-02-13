<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208090257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, sigle VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, type_bac VARCHAR(255) NOT NULL, duree_formation INT NOT NULL, diplome VARCHAR(255) NOT NULL, mode_admis VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, seuil_sm INT NOT NULL, seuil_sp INT NOT NULL, seuil_svt INT NOT NULL, seuil_sagro INT NOT NULL, seuil_eco INT NOT NULL, seuil_stm INT NOT NULL, seuil_ste INT NOT NULL, is_public TINYINT(1) NOT NULL, phone VARCHAR(255) NOT NULL, is_favorite TINYINT(1) NOT NULL, INDEX IDX_20FD592CA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT false, CHANGE is_public is_public TINYINT(1) DEFAULT false, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA73F0036');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE ville');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT 0, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
