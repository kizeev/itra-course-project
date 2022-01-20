<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120094847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE attribute ADD CONSTRAINT FK_FA7AEFFBC54C8C93 FOREIGN KEY (type_id) REFERENCES attribute_type (id)');
        $this->addSql('CREATE INDEX IDX_FA7AEFFBC54C8C93 ON attribute (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute DROP FOREIGN KEY FK_FA7AEFFBC54C8C93');
        $this->addSql('DROP INDEX IDX_FA7AEFFBC54C8C93 ON attribute');
        $this->addSql('ALTER TABLE attribute DROP type_id');
    }
}
