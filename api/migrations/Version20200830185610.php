<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200830185610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_goals ALTER alias TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lesson_goals ALTER alias DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN lesson_goals.alias IS \'(DC2Type:lesson_goal_alias)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_goals ALTER alias TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lesson_goals ALTER alias DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN lesson_goals.alias IS NULL');
    }
}
