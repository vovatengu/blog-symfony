<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260522093302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Выполнится сразу без транзакции
        $this->connection->executeStatement('ALTER TABLE post ADD slug VARCHAR(255) DEFAULT NULL');

        $slugger = new AsciiSlugger();
        foreach ($this->connection->fetchAllAssociative('SELECT id, name FROM post') as $row) {
            $this->connection->executeStatement(
                'UPDATE post SET slug = ? WHERE id = ?',
                [strtolower((string) $slugger->slug($row['name'])), $row['id']]
            );
        }

        $this->addSql('ALTER TABLE post MODIFY slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D989D9B62 ON post');
        $this->addSql('ALTER TABLE post DROP slug');
    }
}
