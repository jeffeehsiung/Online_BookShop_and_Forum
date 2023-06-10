<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610153014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Drop tables if already present
        /*
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE liked_books');
        $this->addSql('DROP TABLE liked_genre');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE followed_books');
        $this->addSql('DROP TABLE disliked_books');
        $this->addSql('DROP TABLE avatars');
        $this->addSql('DROP TABLE books');
        */

        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avatars (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(250) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, genre VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, genre_id INT DEFAULT NULL,author_id INT DEFAULT NULL, title VARCHAR(1024) DEFAULT NULL, original_publication_year VARCHAR(7) DEFAULT NULL, likes BIGINT DEFAULT NULL, image_url VARCHAR(512) DEFAULT NULL, small_image_url VARCHAR(512) DEFAULT NULL, dislikes BIGINT DEFAULT NULL, INDEX IDX_CBE5A331F675F31B (author_id), INDEX IDX_CBE5A3314296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, first_name VARCHAR(45) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, username VARCHAR(32) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, bio VARCHAR(512) DEFAULT NULL, INDEX IDX_8D93D64986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disliked_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_BF96F1F4A76ED395 (user_id), INDEX IDX_BF96F1F416A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE followed_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_55F78CBCA76ED395 (user_id), INDEX IDX_55F78CBC16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liked_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_4F190DA3A76ED395 (user_id), INDEX IDX_4F190DA316A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liked_genre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, INDEX IDX_9DD53D3EA76ED395 (user_id), INDEX IDX_9DD53D3E4296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_CBE5A3314296D31F FOREIGN KEY (genre_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE disliked_books ADD CONSTRAINT FK_BF96F1F4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE disliked_books ADD CONSTRAINT FK_BF96F1F416A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE followed_books ADD CONSTRAINT FK_55F78CBCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE followed_books ADD CONSTRAINT FK_55F78CBC16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT FK_4F190DA3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT FK_4F190DA316A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT FK_9DD53D3E4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT FK_9DD53D3EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES avatars (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your need
    }
}
