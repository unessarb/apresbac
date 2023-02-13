<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210100758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement CHANGE is_public is_public TINYINT(1) DEFAULT true NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT false NOT NULL');
        $this->addSql('DROP INDEX unique_slug_for_published_at ON news');
        $this->addSql('ALTER TABLE news ADD etablissement_id INT DEFAULT NULL, ADD link VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, DROP is_french, CHANGE is_public is_public TINYINT(1) NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) NOT NULL, CHANGE published_at published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DD39950989D9B62 ON news (slug)');
        $this->addSql('CREATE INDEX IDX_1DD39950FF631228 ON news (etablissement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement CHANGE is_public is_public TINYINT(1) DEFAULT 1 NOT NULL, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD39950FF631228');
        $this->addSql('DROP INDEX UNIQ_1DD39950989D9B62 ON news');
        $this->addSql('DROP INDEX IDX_1DD39950FF631228 ON news');
        $this->addSql('ALTER TABLE news ADD is_french TINYINT(1) DEFAULT 0, DROP etablissement_id, DROP link, DROP is_active, CHANGE is_favorite is_favorite TINYINT(1) DEFAULT 0 NOT NULL, CHANGE is_public is_public TINYINT(1) DEFAULT 0, CHANGE published_at published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX unique_slug_for_published_at ON news (slug, published_at)');
    }
}
