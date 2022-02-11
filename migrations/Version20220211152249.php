<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211152249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, unique_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_genre (movie_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_FD1229648F93B6FC (movie_id), INDEX IDX_FD1229644296D31F (genre_id), PRIMARY KEY(movie_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_genre DROP FOREIGN KEY FK_FD1229644296D31F');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('ALTER TABLE director CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tmdb_id tmdb_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE biography biography LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE movie CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE length length VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE overview overview LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE poster_url poster_url VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tmdb CHANGE unique_id unique_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
