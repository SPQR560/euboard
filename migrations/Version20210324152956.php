<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324152956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE child_messages ADD thread_id INT NULL');
        $this->addSql('ALTER TABLE child_messages ADD CONSTRAINT FK_51056332E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_51056332E2904019 ON child_messages (thread_id)');

        $this->addSql('UPDATE
                        child_messages
                    SET thread_id = t.id
                    FROM child_messages as cm
                    INNER JOIN message m on m.id = cm.parent_message_id
                    INNER JOIN thread t on m.thread_id = t.id
                    ');

        $this->addSql('ALTER TABLE child_messages ALTER COLUMN thread_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE child_messages DROP CONSTRAINT FK_51056332E2904019');
        $this->addSql('DROP INDEX IDX_51056332E2904019');
        $this->addSql('ALTER TABLE child_messages DROP thread_id');
    }
}
