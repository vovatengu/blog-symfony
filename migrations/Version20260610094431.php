<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610094431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Выполнится сразу без транзакции
        $this->connection->executeStatement('ALTER TABLE category ADD slug VARCHAR(255) DEFAULT NULL');

        $slugger = new AsciiSlugger();
        foreach ($this->connection->fetchAllAssociative('SELECT id, name FROM category') as $row) {
            $this->connection->executeStatement(
                'UPDATE category SET slug = ? WHERE id = ?',
                [strtolower((string) $slugger->slug($row['name'])), $row['id']]
            );
        }

        $this->addSql('ALTER TABLE category MODIFY slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_64C19C1989D9B62 ON category');
        $this->addSql('ALTER TABLE category DROP slug');
    }
}
