<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120083752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_collection_attribute (user_collection_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_62738CE6BFC7FBAD (user_collection_id), INDEX IDX_62738CE6B6E62EFA (attribute_id), PRIMARY KEY(user_collection_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_collection_attribute ADD CONSTRAINT FK_62738CE6BFC7FBAD FOREIGN KEY (user_collection_id) REFERENCES user_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_collection_attribute ADD CONSTRAINT FK_62738CE6B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_collection_attribute');
    }
}
