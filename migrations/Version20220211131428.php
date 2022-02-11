<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211131428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE director (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, tmdb_id VARCHAR(255) NOT NULL, biography LONGTEXT NOT NULL, date_of_birth DATE NOT NULL, INDEX IDX_1E90D3F08F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, tmdb_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, length VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, overview LONGTEXT NOT NULL, poster_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D5EF26F55BCC5E5 (tmdb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tmdb (id INT AUTO_INCREMENT NOT NULL, unique_id VARCHAR(255) NOT NULL, vote_average DOUBLE PRECISION DEFAULT NULL, vote_count INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F08F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F55BCC5E5 FOREIGN KEY (tmdb_id) REFERENCES tmdb (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE director DROP FOREIGN KEY FK_1E90D3F08F93B6FC');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F55BCC5E5');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE tmdb');
    }
}
