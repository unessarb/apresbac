<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208114936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement ADD secteur_id INT DEFAULT NULL, CHANGE is_public is_public TINYINT(1) DEFAULT true NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C9F7E4405 FOREIGN KEY (secteur_id) REFERENCES secteur (id)');
        $this->addSql('CREATE INDEX IDX_20FD592C9F7E4405 ON etablissement (secteur_id)');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT false, CHANGE is_public is_public TINYINT(1) DEFAULT false, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C9F7E4405');
        $this->addSql('DROP INDEX IDX_20FD592C9F7E4405 ON etablissement');
        $this->addSql('ALTER TABLE etablissement DROP secteur_id, CHANGE is_public is_public TINYINT(1) NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT 0, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
