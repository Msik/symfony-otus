<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713161551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievement (id BIGSERIAL NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_achievement (achievement_id BIGINT NOT NULL, user_id BIGINT NOT NULL, PRIMARY KEY(achievement_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3F68B664B3EC99FE ON user_achievement (achievement_id)');
        $this->addSql('CREATE INDEX IDX_3F68B664A76ED395 ON user_achievement (user_id)');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT FK_3F68B664B3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT FK_3F68B664A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_achievement DROP CONSTRAINT FK_3F68B664B3EC99FE');
        $this->addSql('ALTER TABLE user_achievement DROP CONSTRAINT FK_3F68B664A76ED395');
        $this->addSql('DROP TABLE achievement');
        $this->addSql('DROP TABLE user_achievement');
    }
}
