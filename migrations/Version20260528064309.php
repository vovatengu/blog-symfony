<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528064309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_32745d0a92fc23a8 TO UNIQ_6652B9F92FC23A8');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_32745d0aa0d96fbf TO UNIQ_6652B9FA0D96FBF');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_32745d0ac05fb297 TO UNIQ_6652B9FC05FB297');
        $this->addSql('ALTER TABLE post ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES app__user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9f92fc23a8 TO UNIQ_32745D0A92FC23A8');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9fa0d96fbf TO UNIQ_32745D0AA0D96FBF');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9fc05fb297 TO UNIQ_32745D0AC05FB297');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('DROP INDEX IDX_5A8A6C8DF675F31B ON post');
        $this->addSql('ALTER TABLE post DROP author_id');
    }
}
