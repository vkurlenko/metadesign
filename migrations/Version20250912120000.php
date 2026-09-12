<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250912120000 extends AbstractMigration
{
    /**
     * Справочник => список значений (константы приложения).
     */
    private const REFERENCE_DATA = [
        'realty_types' => ['flat', 'house', 'commerce'],
        'realty_status_types' => ['new', 'secondary'],
        'repair_types' => ['business', 'comfort', 'premium'],
        'service_type' => [
            'design_project',
            'repair',
            'works',
            'equipment',
            'supervision',
            'consulting',
        ],
    ];

    public function getDescription(): string
    {
        return 'Squashed schema: create all tables from scratch and seed reference data';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('users')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE users (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    chat_id BIGINT DEFAULT NULL,
                    first_name TEXT DEFAULT NULL,
                    last_name TEXT DEFAULT NULL,
                    phone_number VARCHAR(20) NOT NULL,
                    UNIQUE INDEX UNIQ_1483A5E96B01BC5B (phone_number),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('realty_types')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE realty_types (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_2575301D5E237E06 (name),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('realty_status_types')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE realty_status_types (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_8C3F0A3C5E237E06 (name),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('repair_types')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE repair_types (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_F9EF4D9C5E237E06 (name),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('service_type')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE service_type (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_429DE3C55E237E06 (name),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        }

        if (!$schema->hasTable('orders')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE orders (
                    id BIGINT AUTO_INCREMENT NOT NULL,
                    user_id BIGINT NOT NULL,
                    square FLOAT NOT NULL,
                    cost BIGINT NOT NULL,
                    created_at DATE NOT NULL,
                    done_at DATE DEFAULT NULL,
                    realty_type_id INT NOT NULL,
                    realty_status_type_id INT NOT NULL,
                    repair_type_id INT NOT NULL,
                    INDEX IDX_E52FFDEEA76ED395 (user_id),
                    INDEX IDX_E52FFDEE57DBA111 (realty_type_id),
                    INDEX IDX_E52FFDEE1D48C40A (realty_status_type_id),
                    INDEX IDX_E52FFDEE5BF7D900 (repair_type_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB
            SQL);
            $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5BF7D901 FOREIGN KEY (user_id) REFERENCES users (id)');
            $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE57DBA111 FOREIGN KEY (realty_type_id) REFERENCES realty_types (id)');
            $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE1D48C40A FOREIGN KEY (realty_status_type_id) REFERENCES realty_status_types (id)');
            $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5BF7D900 FOREIGN KEY (repair_type_id) REFERENCES repair_types (id)');
        }

        if (!$schema->hasTable('feedback')) {
            $this->addSql(<<<'SQL'
                CREATE TABLE feedback (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id BIGINT NOT NULL,
                    service_type_id INT NOT NULL,
                    phone_call TINYINT(1) NOT NULL,
                    telegram TINYINT(1) NOT NULL,
                    whatsapp TINYINT(1) NOT NULL,
                    created_at DATE NOT NULL,
                    done_at DATETIME DEFAULT NULL,
                    INDEX IDX_D2294458A76ED395 (user_id),
                    INDEX IDX_D2294458AC8DE0F (service_type_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
            $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
            $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458AC8DE0F FOREIGN KEY (service_type_id) REFERENCES service_type (id)');
        }

        // === Справочники: значения констант приложения ===
        // id не фиксируем намеренно — на уже развёрнутой БД они могут отличаться,
        // а уникальный индекс по name не даст создать дубликаты.
        foreach (self::REFERENCE_DATA as $table => $names) {
            $values = implode(', ', array_map(
                static fn (string $name): string => "('" . $name . "')",
                $names
            ));

            $this->addSql("INSERT IGNORE INTO $table (name) VALUES $values");
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS feedback');
        $this->addSql('DROP TABLE IF EXISTS orders');
        $this->addSql('DROP TABLE IF EXISTS service_type');
        $this->addSql('DROP TABLE IF EXISTS repair_types');
        $this->addSql('DROP TABLE IF EXISTS realty_status_types');
        $this->addSql('DROP TABLE IF EXISTS realty_types');
        $this->addSql('DROP TABLE IF EXISTS users');
    }
}
