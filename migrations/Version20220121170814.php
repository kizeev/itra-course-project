<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121170814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD user_collection_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EBFC7FBAD FOREIGN KEY (user_collection_id) REFERENCES user_collection (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EBFC7FBAD ON item (user_collection_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EBFC7FBAD');
        $this->addSql('DROP INDEX IDX_1F1B251EBFC7FBAD ON item');
        $this->addSql('ALTER TABLE item DROP user_collection_id');
    }
}
