<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206153927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT false, CHANGE is_public is_public TINYINT(1) DEFAULT false, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE secteur ADD slug VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT 0, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE secteur DROP slug, DROP description');
    }
}
