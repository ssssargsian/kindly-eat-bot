<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805163112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE telegram_poll_answers (id UUID NOT NULL, user_id UUID DEFAULT NULL, poll_id TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5330B4FA76ED395 ON telegram_poll_answers (user_id)');
        $this->addSql('COMMENT ON COLUMN telegram_poll_answers.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN telegram_poll_answers.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE poll_answers_options (poll_answer_id UUID NOT NULL, poll_option_id UUID NOT NULL, PRIMARY KEY(poll_answer_id, poll_option_id))');
        $this->addSql('CREATE INDEX IDX_835985D361E461F3 ON poll_answers_options (poll_answer_id)');
        $this->addSql('CREATE INDEX IDX_835985D36C13349B ON poll_answers_options (poll_option_id)');
        $this->addSql('COMMENT ON COLUMN poll_answers_options.poll_answer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN poll_answers_options.poll_option_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE telegram_poll_options (id UUID NOT NULL, poll_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN telegram_poll_options.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE telegram_users (id UUID NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, bot BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN telegram_users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE telegram_poll_answers ADD CONSTRAINT FK_C5330B4FA76ED395 FOREIGN KEY (user_id) REFERENCES telegram_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_answers_options ADD CONSTRAINT FK_835985D361E461F3 FOREIGN KEY (poll_answer_id) REFERENCES telegram_poll_answers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_answers_options ADD CONSTRAINT FK_835985D36C13349B FOREIGN KEY (poll_option_id) REFERENCES telegram_poll_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE telegram_poll_answers DROP CONSTRAINT FK_C5330B4FA76ED395');
        $this->addSql('ALTER TABLE poll_answers_options DROP CONSTRAINT FK_835985D361E461F3');
        $this->addSql('ALTER TABLE poll_answers_options DROP CONSTRAINT FK_835985D36C13349B');
        $this->addSql('DROP TABLE telegram_poll_answers');
        $this->addSql('DROP TABLE poll_answers_options');
        $this->addSql('DROP TABLE telegram_poll_options');
        $this->addSql('DROP TABLE telegram_users');
    }
}
