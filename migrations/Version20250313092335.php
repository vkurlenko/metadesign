<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Создаёт базовые таблицы и связи, если их нет.
 * Совместима с существующей БД из дампа.
 */
final class Version20250313092335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create base tables and constraints if not exist (safe for existing DB)';
    }

    public function up(Schema $schema): void
    {
        // === 1. Создаём таблицы ТОЛЬКО если их нет ===

        if (!$schema->hasTable('users')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE users (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    chat_id BIGINT DEFAULT NULL,
                    first_name TEXT,
                    last_name TEXT,
                    phone_number VARCHAR(20) NOT NULL,
                    PRIMARY KEY(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
            SQL);
        }

        if (!$schema->hasTable('property_types')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE property_types (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    PRIMARY KEY(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
            SQL);
        }

        if (!$schema->hasTable('repair_classes')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE repair_classes (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    PRIMARY KEY(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
            SQL);
        }

        if (!$schema->hasTable('room_types')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE room_types (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    PRIMARY KEY(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
            SQL);
        }

        if (!$schema->hasTable('orders')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE orders (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    user_id BIGINT NOT NULL,
                    property_type_id BIGINT NOT NULL,
                    room_type_id BIGINT NOT NULL,
                    repair_class_id BIGINT NOT NULL,
                    square FLOAT NOT NULL,
                    cost BIGINT NOT NULL,
                    created_at DATE NOT NULL,
                    done_at DATE DEFAULT NULL,
                    INDEX ordermodel_user_id (user_id),
                    INDEX ordermodel_property_type_id (property_type_id),
                    INDEX ordermodel_room_type_id (room_type_id),
                    INDEX ordermodel_repair_class_id (repair_class_id),
                    PRIMARY KEY(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
            SQL);
        }

        // === 2. Добавляем уникальные индексы (если ещё нет) ===
        $this->addUniqueIndex('users', 'UNIQ_1483A5E96B01BC5B', 'phone_number');
        $this->addUniqueIndex('property_types', 'UNIQ_9F23483D5E237E06', 'name');
        $this->addUniqueIndex('repair_classes', 'UNIQ_9C562B565E237E06', 'name');
        $this->addUniqueIndex('room_types', 'UNIQ_138C289B5E237E06', 'name');

        // === 3. Добавляем внешние ключи (если ещё нет) ===
        $this->addForeignKey('orders', 'orders_ibfk_1', 'user_id', 'users', 'id');
        $this->addForeignKey('orders', 'orders_ibfk_2', 'property_type_id', 'property_types', 'id');
        $this->addForeignKey('orders', 'orders_ibfk_3', 'room_type_id', 'room_types', 'id');
        $this->addForeignKey('orders', 'orders_ibfk_4', 'repair_class_id', 'repair_classes', 'id');
    }

    public function down(Schema $schema): void
    {
        // Удаляем FK
        $this->dropForeignKey('orders', 'orders_ibfk_1');
        $this->dropForeignKey('orders', 'orders_ibfk_2');
        $this->dropForeignKey('orders', 'orders_ibfk_3');
        $this->dropForeignKey('orders', 'orders_ibfk_4');

        // Удаляем индексы
        $this->dropIndex('users', 'UNIQ_1483A5E96B01BC5B');
        $this->dropIndex('property_types', 'UNIQ_9F23483D5E237E06');
        $this->dropIndex('repair_classes', 'UNIQ_9C562B565E237E06');
        $this->dropIndex('room_types', 'UNIQ_138C289B5E237E06');

        // Удаляем таблицы, если существуют
        if ($schema->hasTable('orders')) {
            $this->addSql('DROP TABLE orders');
        }
        if ($schema->hasTable('users')) {
            $this->addSql('DROP TABLE users');
        }
        if ($schema->hasTable('property_types')) {
            $this->addSql('DROP TABLE property_types');
        }
        if ($schema->hasTable('repair_classes')) {
            $this->addSql('DROP TABLE repair_classes');
        }
        if ($schema->hasTable('room_types')) {
            $this->addSql('DROP TABLE room_types');
        }
    }

    // Вспомогательные методы

    private function addUniqueIndex(string $table, string $index, string $column): void
    {
        try {
            $this->addSql("CREATE UNIQUE INDEX $index ON $table ($column)");
        } catch (\Exception $e) {
            // Уже существует — игнорируем
        }
    }

    private function addForeignKey(
        string $table,
        string $fk,
        string $localColumn,
        string $foreignTable,
        string $foreignColumn
    ): void {
        try {
            $this->addSql("ALTER TABLE $table ADD CONSTRAINT $fk FOREIGN KEY ($localColumn) REFERENCES $foreignTable ($foreignColumn)");
        } catch (\Exception $e) {
            // Уже существует — игнорируем
        }
    }

    private function dropForeignKey(string $table, string $fk): void
    {
        try {
            $this->addSql("ALTER TABLE $table DROP FOREIGN KEY $fk");
        } catch (\Exception $e) {
        }
    }

    private function dropIndex(string $table, string $index): void
    {
        try {
            $this->addSql("DROP INDEX $index ON $table");
        } catch (\Exception $e) {
        }
    }
}