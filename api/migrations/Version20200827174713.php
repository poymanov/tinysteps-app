<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827174713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_goals (id UUID NOT NULL, alias VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, sort INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DC2CAA8E16C6B94 ON lesson_goals (alias)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DC2CAA85E237E06 ON lesson_goals (name)');
        $this->addSql('COMMENT ON COLUMN lesson_goals.id IS \'(DC2Type:lesson_goal_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_goals.status IS \'(DC2Type:lesson_goal_status)\'');
        $this->addSql('COMMENT ON COLUMN lesson_goals.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_goals');
    }
}
