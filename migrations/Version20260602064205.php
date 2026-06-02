<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260602064205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category (name) VALUES ('Sport'), ('Politics'), ('News'), ('Humor'), ('Technology')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM category');
    }
}
