<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903194044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_teachers (id UUID NOT NULL, user_id UUID NOT NULL, alias VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, rating DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A54055EFE16C6B94 ON lesson_teachers (alias)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A54055EFA76ED395 ON lesson_teachers (user_id)');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.id IS \'(DC2Type:lesson_teacher_id)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.alias IS \'(DC2Type:lesson_teacher_alias)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.description IS \'(DC2Type:lesson_teacher_description)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.price IS \'(DC2Type:lesson_teacher_price)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.rating IS \'(DC2Type:lesson_teacher_rating)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.status IS \'(DC2Type:lesson_teacher_status)\'');
        $this->addSql('COMMENT ON COLUMN lesson_teachers.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_teachers');
    }
}
