<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909180358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_teachers_goals (id UUID NOT NULL, teacher_id UUID NOT NULL, goal_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1E5ADCE941807E1D667D1AFE ON lesson_teachers_goals (teacher_id, goal_id)');
        $this->addSql('COMMENT ON COLUMN lesson_teachers_goals.id IS \'(DC2Type:lesson_teacher_goal_id)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_teachers_goals');
    }
}
