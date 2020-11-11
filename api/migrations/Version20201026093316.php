<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201026093316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_lessons (id UUID NOT NULL, user_id UUID NOT NULL, schedule_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CA3E4A7EA40BC2D5 ON lesson_lessons (schedule_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA3E4A7EA76ED395A40BC2D5 ON lesson_lessons (user_id, schedule_id)');
        $this->addSql('COMMENT ON COLUMN lesson_lessons.id IS \'(DC2Type:lesson_lesson_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_lessons.schedule_id IS \'(DC2Type:lesson_schedule_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_lessons.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lesson_lessons ADD CONSTRAINT FK_CA3E4A7EA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES lesson_schedules (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_lessons');
    }
}
