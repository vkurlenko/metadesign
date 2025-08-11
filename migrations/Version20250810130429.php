<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250810130429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate data from property_types, room_types, repair_classes to new tables and rename columns in orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A76ED395');
        $this->addSql('ALTER TABLE feedback CHANGE user_id user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        // === 1. Удаляем ВСЕ внешние ключи, которые мешают изменениям ===
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1'); // Ключевой добавленный FK!
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_2');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_3');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_4');

        // === 2. Создаём новые таблицы с одинаковой COLLATION (utf8mb4_0900_ai_ci) ===
        $this->addSql('CREATE TABLE realty_status_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8C3F0A3C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realty_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2575301D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F9EF4D9C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');

        // === 3. Копируем данные из старых таблиц в новые ===
        $this->addSql('INSERT INTO realty_status_types (id, name) SELECT id, name FROM room_types');
        $this->addSql('INSERT INTO realty_types (id, name) SELECT id, name FROM property_types');
        $this->addSql('INSERT INTO repair_types (id, name) SELECT id, name FROM repair_classes');

        // === 4. Добавляем новые столбцы (без NOT NULL) ===
        $this->addSql('ALTER TABLE orders ADD realty_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD realty_status_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD repair_type_id INT DEFAULT NULL');

        // === 5. Удаляем старые индексы ===
        $this->addSql('DROP INDEX ordermodel_property_type_id ON orders');
        $this->addSql('DROP INDEX ordermodel_room_type_id ON orders');
        $this->addSql('DROP INDEX ordermodel_repair_class_id ON orders');

        // === 6. Переносим значения, сопоставляя по имени с COLLATE ===
        $this->addSql('UPDATE orders o
            JOIN property_types pt ON o.property_type_id = pt.id
            JOIN realty_types rt ON pt.name = rt.name COLLATE utf8mb4_0900_ai_ci
            SET o.realty_type_id = rt.id');

        $this->addSql('UPDATE orders o
            JOIN room_types rt ON o.room_type_id = rt.id
            JOIN realty_status_types rst ON rt.name = rst.name COLLATE utf8mb4_0900_ai_ci
            SET o.realty_status_type_id = rst.id');

        $this->addSql('UPDATE orders o
            JOIN repair_classes rc ON o.repair_class_id = rc.id
            JOIN repair_types rtp ON rc.name = rtp.name COLLATE utf8mb4_0900_ai_ci
            SET o.repair_type_id = rtp.id');

        // === 7. Удаляем старые столбцы и меняем типы ===
        $this->addSql('ALTER TABLE orders 
            DROP property_type_id, 
            DROP room_type_id, 
            DROP repair_class_id');

        // === 8. Устанавливаем NOT NULL для новых столбцов ===
        $this->addSql('ALTER TABLE orders 
            CHANGE realty_type_id realty_type_id INT NOT NULL,
            CHANGE realty_status_type_id realty_status_type_id INT NOT NULL,
            CHANGE repair_type_id repair_type_id INT NOT NULL');

        // === 9. Добавляем внешние ключи на новые таблицы ===
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE57DBA111 FOREIGN KEY (realty_type_id) REFERENCES realty_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE1D48C40A FOREIGN KEY (realty_status_type_id) REFERENCES realty_status_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5BF7D900 FOREIGN KEY (repair_type_id) REFERENCES repair_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5BF7D901 FOREIGN KEY (user_id) REFERENCES users (id)');

        // === 10. Добавляем индексы ===
        $this->addSql('CREATE INDEX IDX_E52FFDEE57DBA111 ON orders (realty_type_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE1D48C40A ON orders (realty_status_type_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5BF7D900 ON orders (repair_type_id)');
        $this->addSql('ALTER TABLE orders RENAME INDEX ordermodel_user_id TO IDX_E52FFDEEA76ED395');


        // === 12. Удаляем старые таблицы ===
        $this->addSql('DROP TABLE property_types');
        $this->addSql('DROP TABLE repair_classes');
        $this->addSql('DROP TABLE migratehistory');
        $this->addSql('DROP TABLE room_types');
    }

    public function down(Schema $schema): void
    {
        // === 1. Удаляем FK на новые таблицы ===
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE1D48C40A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE57DBA111');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE5BF7D900');

        // === 2. Создаём старые таблицы с правильной COLLATION ===
        $this->addSql('CREATE TABLE property_types (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX UNIQ_9F23483D5E237E06 (name), UNIQUE INDEX propertytypemodel_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair_classes (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX UNIQ_9C562B565E237E06 (name), UNIQUE INDEX repairclassmodel_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE migratehistory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, migrated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_types (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX UNIQ_138C289B5E237E06 (name), UNIQUE INDEX roomtypemodel_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');

        // === 3. Копируем данные обратно ===
        $this->addSql('INSERT INTO room_types (id, name) SELECT id, name FROM realty_status_types');
        $this->addSql('INSERT INTO property_types (id, name) SELECT id, name FROM realty_types');
        $this->addSql('INSERT INTO repair_classes (id, name) SELECT id, name FROM repair_types');

        // === 4. Удаляем новые таблицы ===
        $this->addSql('DROP TABLE realty_status_types');
        $this->addSql('DROP TABLE realty_types');
        $this->addSql('DROP TABLE repair_types');

        // === 5. Добавляем старые столбцы (без NOT NULL) ===
        $this->addSql('ALTER TABLE orders ADD property_type_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD room_type_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD repair_class_id BIGINT DEFAULT NULL');

        // === 6. Переносим значения обратно с COLLATE ===
        $this->addSql('UPDATE orders o
            JOIN realty_types rt ON o.realty_type_id = rt.id
            JOIN property_types pt ON rt.name = pt.name COLLATE utf8mb4_0900_ai_ci
            SET o.property_type_id = pt.id');

        $this->addSql('UPDATE orders o
            JOIN realty_status_types rst ON o.realty_status_type_id = rst.id
            JOIN room_types rt ON rst.name = rt.name COLLATE utf8mb4_0900_ai_ci
            SET o.room_type_id = rt.id');

        $this->addSql('UPDATE orders o
            JOIN repair_types rtp ON o.repair_type_id = rtp.id
            JOIN repair_classes rc ON rtp.name = rc.name COLLATE utf8mb4_0900_ai_ci
            SET o.repair_class_id = rc.id');

        // === 7. Удаляем новые столбцы и восстанавливаем типы ===
        $this->addSql('DROP INDEX IDX_E52FFDEE57DBA111 ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE1D48C40A ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE5BF7D900 ON orders');
        $this->addSql('ALTER TABLE orders 
            DROP realty_type_id, 
            DROP realty_status_type_id, 
            DROP repair_type_id, 
            CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, 
            CHANGE user_id user_id BIGINT NOT NULL, 
            CHANGE done_at done_at DATE DEFAULT NULL');

        // === 8. Устанавливаем NOT NULL и восстанавливаем FK ===
        $this->addSql('ALTER TABLE orders 
            CHANGE property_type_id property_type_id BIGINT NOT NULL,
            CHANGE room_type_id room_type_id BIGINT NOT NULL,
            CHANGE repair_class_id repair_class_id BIGINT NOT NULL');

        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_2 FOREIGN KEY (property_type_id) REFERENCES property_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_3 FOREIGN KEY (room_type_id) REFERENCES room_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_4 FOREIGN KEY (repair_class_id) REFERENCES repair_classes (id)');

        // === 9. Восстанавливаем индексы ===
        $this->addSql('CREATE INDEX ordermodel_property_type_id ON orders (property_type_id)');
        $this->addSql('CREATE INDEX ordermodel_room_type_id ON orders (room_type_id)');
        $this->addSql('CREATE INDEX ordermodel_repair_class_id ON orders (repair_class_id)');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdeea76ed395 TO ordermodel_user_id');

        // === 10. Восстанавливаем users и feedback ===
        $this->addSql('ALTER TABLE users CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE chat_id chat_id BIGINT DEFAULT NULL, CHANGE first_name first_name TEXT DEFAULT NULL, CHANGE last_name last_name TEXT DEFAULT NULL, CHANGE phone_number phone_number VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX usermodel_phone_number ON users (phone_number)');
        $this->addSql('ALTER TABLE feedback CHANGE user_id user_id BIGINT NOT NULL');
    }
}