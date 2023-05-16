<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516070202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        /*
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE read_books DROP FOREIGN KEY rb_book_key');
        $this->addSql('ALTER TABLE read_books DROP FOREIGN KEY rb_user_key');
        $this->addSql('ALTER TABLE liked_genre DROP FOREIGN KEY g_genre_key');
        $this->addSql('ALTER TABLE liked_genre DROP FOREIGN KEY liked_genre_ibfk_1');
        $this->addSql('ALTER TABLE liked_books DROP FOREIGN KEY liked_book_key');
        $this->addSql('ALTER TABLE liked_books DROP FOREIGN KEY liked_user_key');
        $this->addSql('DROP TABLE read_books');
        $this->addSql('DROP TABLE liked_genre');
        $this->addSql('DROP TABLE liked_books');
        $this->addSql('ALTER TABLE books RENAME INDEX genre_key_idx TO IDX_A1898504296D31F');
        $this->addSql('ALTER TABLE genres CHANGE id id INT AUTO_INCREMENT NOT NULL');
       */
        $this->addSql('ALTER TABLE users ADD is_verified TINYINT(1) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5480172BE7927C74 ON users (email)');
        $this->addSql('ALTER TABLE users RENAME INDEX avatar_key_idx TO IDX_5480172B86383B10');
        $this->addSql('ALTER TABLE users RENAME INDEX libraries_key_idx TO IDX_5480172BFE2541D7');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE read_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX rb_user_key (user_id), INDEX rb_book_key_idx (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liked_genre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, INDEX liked_genres_ibfk_1 (user_id), INDEX g_genre_key_idx (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liked_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX liked_user_key (user_id), INDEX liked_book_key (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE read_books ADD CONSTRAINT rb_book_key FOREIGN KEY (book_id) REFERENCES books (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE read_books ADD CONSTRAINT rb_user_key FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT g_genre_key FOREIGN KEY (genre_id) REFERENCES genres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT liked_genre_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT liked_book_key FOREIGN KEY (book_id) REFERENCES books (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT liked_user_key FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX UNIQ_5480172BE7927C74 ON a22web12.users');
        $this->addSql('ALTER TABLE a22web12.users DROP is_verified, CHANGE roles roles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE a22web12.users RENAME INDEX idx_5480172bfe2541d7 TO libraries_key_idx');
        $this->addSql('ALTER TABLE a22web12.users RENAME INDEX idx_5480172b86383b10 TO avatar_key_idx');
        $this->addSql('ALTER TABLE a22web12.genres CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE a22web12.books RENAME INDEX idx_a1898504296d31f TO genre_key_idx');
    }
}
