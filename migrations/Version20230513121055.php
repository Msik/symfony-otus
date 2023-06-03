<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230513121055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_skill_proportion (id BIGSERIAL NOT NULL, task_id BIGINT NOT NULL, skill_id BIGINT DEFAULT NULL, proportion SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task_skill_proportion__task_id__ind ON task_skill_proportion (task_id)');
        $this->addSql('CREATE INDEX task_skill_proportion__skill_id__ind ON task_skill_proportion (skill_id)');
        $this->addSql('ALTER TABLE task_skill_proportion ADD CONSTRAINT FK_3AC122868DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_skill_proportion ADD CONSTRAINT FK_3AC122865585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_skill_proportion DROP CONSTRAINT FK_3AC122868DB60186');
        $this->addSql('ALTER TABLE task_skill_proportion DROP CONSTRAINT FK_3AC122865585C142');
        $this->addSql('DROP TABLE task_skill_proportion');
    }
}
