<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208145632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement CHANGE is_public is_public TINYINT(1) DEFAULT true NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20FD592C5E237E06 ON etablissement (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20FD592C989D9B62 ON etablissement (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20FD592C8776B952 ON etablissement (sigle)');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT false, CHANGE is_public is_public TINYINT(1) DEFAULT false, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_20FD592C5E237E06 ON etablissement');
        $this->addSql('DROP INDEX UNIQ_20FD592C989D9B62 ON etablissement');
        $this->addSql('DROP INDEX UNIQ_20FD592C8776B952 ON etablissement');
        $this->addSql('ALTER TABLE etablissement CHANGE is_public is_public TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT 0, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
