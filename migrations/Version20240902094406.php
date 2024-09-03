<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902094406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD is_main TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tricks ADD main_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1E4873418 FOREIGN KEY (main_image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_E1D902C1E4873418 ON tricks (main_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP is_main');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C1E4873418');
        $this->addSql('DROP INDEX IDX_E1D902C1E4873418 ON tricks');
        $this->addSql('ALTER TABLE tricks DROP main_image_id');
    }
}
