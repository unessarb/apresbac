<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214150640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement CHANGE is_active is_active TINYINT(1) DEFAULT true NOT NULL, CHANGE is_public is_public TINYINT(1) DEFAULT true NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE ville ADD is_active TINYINT(1) NOT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement CHANGE is_active is_active TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_public is_public TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE ville DROP is_active, DROP created_at, DROP updated_at');
    }
}
