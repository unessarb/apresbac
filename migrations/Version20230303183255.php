<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303183255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement_secteur (etablissement_id INT NOT NULL, secteur_id INT NOT NULL, INDEX IDX_84543ADCFF631228 (etablissement_id), INDEX IDX_84543ADC9F7E4405 (secteur_id), PRIMARY KEY(etablissement_id, secteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement_secteur ADD CONSTRAINT FK_84543ADCFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_secteur ADD CONSTRAINT FK_84543ADC9F7E4405 FOREIGN KEY (secteur_id) REFERENCES secteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C9F7E4405');
        $this->addSql('DROP INDEX IDX_20FD592C9F7E4405 ON etablissement');
        $this->addSql('ALTER TABLE etablissement DROP secteur_id, CHANGE is_public is_public TINYINT(1) DEFAULT true NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement_secteur DROP FOREIGN KEY FK_84543ADCFF631228');
        $this->addSql('ALTER TABLE etablissement_secteur DROP FOREIGN KEY FK_84543ADC9F7E4405');
        $this->addSql('DROP TABLE etablissement_secteur');
        $this->addSql('ALTER TABLE etablissement ADD secteur_id INT DEFAULT NULL, CHANGE is_public is_public TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C9F7E4405 FOREIGN KEY (secteur_id) REFERENCES secteur (id)');
        $this->addSql('CREATE INDEX IDX_20FD592C9F7E4405 ON etablissement (secteur_id)');
        $this->addSql('ALTER TABLE news CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
