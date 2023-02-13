<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208105705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement ADD adresse VARCHAR(255) DEFAULT NULL, ADD phone_wp VARCHAR(255) DEFAULT NULL, ADD website VARCHAR(255) DEFAULT NULL, ADD tags VARCHAR(255) DEFAULT NULL, CHANGE seuil_sm seuil_sm NUMERIC(10, 0) NOT NULL, CHANGE seuil_sp seuil_sp NUMERIC(10, 0) NOT NULL, CHANGE seuil_svt seuil_svt NUMERIC(10, 0) NOT NULL, CHANGE seuil_sagro seuil_sagro NUMERIC(10, 0) NOT NULL, CHANGE seuil_eco seuil_eco NUMERIC(10, 0) NOT NULL, CHANGE seuil_stm seuil_stm NUMERIC(10, 0) NOT NULL, CHANGE seuil_ste seuil_ste NUMERIC(10, 0) NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT false, CHANGE is_public is_public TINYINT(1) DEFAULT false, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP adresse, DROP phone_wp, DROP website, DROP tags, CHANGE seuil_sm seuil_sm INT NOT NULL, CHANGE seuil_sp seuil_sp INT NOT NULL, CHANGE seuil_svt seuil_svt INT NOT NULL, CHANGE seuil_sagro seuil_sagro INT NOT NULL, CHANGE seuil_eco seuil_eco INT NOT NULL, CHANGE seuil_stm seuil_stm INT NOT NULL, CHANGE seuil_ste seuil_ste INT NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_french is_french TINYINT(1) DEFAULT 0, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
