<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327155355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id BIGSERIAL NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_course (course_id BIGINT NOT NULL, user_id BIGINT NOT NULL, PRIMARY KEY(course_id, user_id))');
        $this->addSql('CREATE INDEX IDX_73CC7484591CC992 ON user_course (course_id)');
        $this->addSql('CREATE INDEX IDX_73CC7484A76ED395 ON user_course (user_id)');
        $this->addSql('CREATE TABLE lesson (id BIGSERIAL NOT NULL, course_id BIGINT NOT NULL, module_id BIGINT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX lesson__course_id__ind ON lesson (course_id)');
        $this->addSql('CREATE INDEX lesson__module_id__ind ON lesson (module_id)');
        $this->addSql('CREATE TABLE module (id BIGSERIAL NOT NULL, course_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX module__course_id__ind ON module (course_id)');
        $this->addSql('CREATE TABLE skill (id BIGSERIAL NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE task (id BIGSERIAL NOT NULL, lesson_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task__lesson_id__ind ON task (lesson_id)');
        $this->addSql('CREATE TABLE "user" (id BIGSERIAL NOT NULL, phone VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_point (id BIGSERIAL NOT NULL, user_id BIGINT NOT NULL, task_id BIGINT NOT NULL, skill_id BIGINT DEFAULT NULL, points SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_point__user_id__ind ON user_point (user_id)');
        $this->addSql('CREATE INDEX user_point__task_id__ind ON user_point (task_id)');
        $this->addSql('CREATE INDEX user_point__skill_id__ind ON user_point (skill_id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT user_course__course_id__fk FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT user_course__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT lesson__course_id__fk FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT lesson__module_id__fk FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT module__course_id__fk FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT task__lesson_id__fk FOREIGN KEY (lesson_id) REFERENCES lesson (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_point ADD CONSTRAINT user_point__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_point ADD CONSTRAINT user_point__task_id__fk FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_point ADD CONSTRAINT user_point__skill_id__fk FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT user_course__course_id__fk');
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT user_course__user_id__fk');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT lesson__course_id__fk');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT lesson__module_id__fk');
        $this->addSql('ALTER TABLE module DROP CONSTRAINT module__course_id__fk');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT task__lesson_id__fk');
        $this->addSql('ALTER TABLE user_point DROP CONSTRAINT user_point__user_id__fk');
        $this->addSql('ALTER TABLE user_point DROP CONSTRAINT user_point__task_id__fk');
        $this->addSql('ALTER TABLE user_point DROP CONSTRAINT user_point__skill_id__fk');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE user_course');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_point');
    }
}
