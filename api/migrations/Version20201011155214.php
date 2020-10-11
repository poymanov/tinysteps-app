<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201011155214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_schedules (id UUID NOT NULL, teacher_id UUID DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5518330441807E1D ON lesson_schedules (teacher_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5518330441807E1DAA9E377A ON lesson_schedules (teacher_id, date)');
        $this->addSql('COMMENT ON COLUMN lesson_schedules.id IS \'(DC2Type:lesson_schedule_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_schedules.teacher_id IS \'(DC2Type:lesson_teacher_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_schedules.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lesson_schedules.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lesson_schedules ADD CONSTRAINT FK_5518330441807E1D FOREIGN KEY (teacher_id) REFERENCES lesson_teachers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE lesson_schedules');
    }
}
