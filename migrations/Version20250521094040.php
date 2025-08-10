<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521094040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($schema->hasTable('feedback')) {
            // Меняем тип user_id с BIGINT на INT, чтобы совпадало с users.id
            $this->addSql('ALTER TABLE feedback MODIFY user_id INT NOT NULL');
        }else{
            $this->addSql(<<<'SQL'
            CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, service_type_id INT NOT NULL, phone_call TINYINT(1) NOT NULL, telegram TINYINT(1) NOT NULL, whatsapp TINYINT(1) NOT NULL, created_at DATE NOT NULL, done_at DATETIME DEFAULT NULL, INDEX IDX_D2294458A76ED395 (user_id), INDEX IDX_D2294458AC8DE0F (service_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        }

        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD CONSTRAINT FK_D2294458AC8DE0F FOREIGN KEY (service_type_id) REFERENCES service_type (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458AC8DE0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feedback
        SQL);
    }
}
