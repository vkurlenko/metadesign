<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313092335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F23483D5E237E06 ON property_types (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C562B565E237E06 ON repair_classes (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_138C289B5E237E06 ON room_types (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_138C289B5E237E06 ON room_types');
        $this->addSql('DROP INDEX UNIQ_9C562B565E237E06 ON repair_classes');
        $this->addSql('DROP INDEX UNIQ_9F23483D5E237E06 ON property_types');
    }
}
