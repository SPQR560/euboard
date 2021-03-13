<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313182745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread DROP CONSTRAINT fk_31204c837a4426dd');
        $this->addSql('DROP INDEX idx_31204c837a4426dd');
        $this->addSql('ALTER TABLE thread RENAME COLUMN boards_id TO board_id');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_31204C83E7EC5785 ON thread (board_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE thread DROP CONSTRAINT FK_31204C83E7EC5785');
        $this->addSql('DROP INDEX IDX_31204C83E7EC5785');
        $this->addSql('ALTER TABLE thread RENAME COLUMN board_id TO boards_id');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT fk_31204c837a4426dd FOREIGN KEY (boards_id) REFERENCES board (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_31204c837a4426dd ON thread (boards_id)');
    }
}
