<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311071412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, property_type_id INT NOT NULL, room_type_id INT NOT NULL, repair_class_id INT NOT NULL, square DOUBLE PRECISION NOT NULL, cost BIGINT NOT NULL, created_at DATE NOT NULL, done_at DATETIME DEFAULT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), INDEX IDX_E52FFDEE9C81C6EB (property_type_id), INDEX IDX_E52FFDEE296E3073 (room_type_id), INDEX IDX_E52FFDEE839872BB (repair_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair_classes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, chat_id INT DEFAULT NULL, first_name LONGTEXT DEFAULT NULL, last_name LONGTEXT DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9C81C6EB FOREIGN KEY (property_type_id) REFERENCES property_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE296E3073 FOREIGN KEY (room_type_id) REFERENCES room_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE839872BB FOREIGN KEY (repair_class_id) REFERENCES repair_classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9C81C6EB');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE296E3073');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE839872BB');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE property_types');
        $this->addSql('DROP TABLE repair_classes');
        $this->addSql('DROP TABLE room_types');
        $this->addSql('DROP TABLE users');
    }
}
