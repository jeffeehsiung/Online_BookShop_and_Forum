<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509102043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        /*
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reading_list DROP FOREIGN KEY rl_book_key');
        $this->addSql('ALTER TABLE reading_list DROP FOREIGN KEY rl_user_key');
        $this->addSql('ALTER TABLE liked_authors DROP FOREIGN KEY la_author_key');
        $this->addSql('ALTER TABLE liked_authors DROP FOREIGN KEY la_user_key');
        $this->addSql('DROP TABLE reading_list');
        $this->addSql('DROP TABLE liked_authors');
        $this->addSql('DROP INDEX isbn_idx ON books');
        $this->addSql('ALTER TABLE books RENAME INDEX author_key_idx TO IDX_A189850F675F31B');
        $this->addSql('ALTER TABLE books RENAME INDEX genre_key_idx TO IDX_A1898504296D31F');
        $this->addSql('ALTER TABLE followed_books RENAME INDEX lb_user_idx TO IDX_E5DE2DC5A76ED395');
        $this->addSql('ALTER TABLE followed_books RENAME INDEX lb_book_key_idx TO IDX_E5DE2DC516A2B381');
        $this->addSql('ALTER TABLE genres CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5480172BE7927C74 ON users (email)');
        $this->addSql('ALTER TABLE users RENAME INDEX avatar_key_idx TO IDX_5480172B86383B10');
        $this->addSql('ALTER TABLE users RENAME INDEX libraries_key_idx TO IDX_5480172BFE2541D7');
        */
        $this->addSql('ALTER TABLE users ADD roles JSON NOT NULL, DROP visibility_posts, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reading_list (id INT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX rl_user_key_idx (user_id), INDEX rl_book_key_idx (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liked_authors (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, author_id INT DEFAULT NULL, INDEX la_user_key_idx (user_id), INDEX la_author_key_idx (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reading_list ADD CONSTRAINT rl_book_key FOREIGN KEY (book_id) REFERENCES books (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reading_list ADD CONSTRAINT rl_user_key FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_authors ADD CONSTRAINT la_author_key FOREIGN KEY (author_id) REFERENCES authors (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE liked_authors ADD CONSTRAINT la_user_key FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE INDEX isbn_idx ON a22web12.books (isbn)');
        $this->addSql('ALTER TABLE a22web12.books RENAME INDEX idx_a1898504296d31f TO genre_key_idx');
        $this->addSql('ALTER TABLE a22web12.books RENAME INDEX idx_a189850f675f31b TO author_key_idx');
        $this->addSql('DROP INDEX UNIQ_5480172BE7927C74 ON a22web12.users');
        $this->addSql('ALTER TABLE a22web12.users ADD visibility_posts INT DEFAULT NULL, DROP roles, CHANGE email email VARCHAR(64) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE a22web12.users RENAME INDEX idx_5480172bfe2541d7 TO libraries_key_idx');
        $this->addSql('ALTER TABLE a22web12.users RENAME INDEX idx_5480172b86383b10 TO avatar_key_idx');
        $this->addSql('ALTER TABLE a22web12.genres CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE a22web12.followed_books RENAME INDEX idx_e5de2dc5a76ed395 TO lb_user_idx');
        $this->addSql('ALTER TABLE a22web12.followed_books RENAME INDEX idx_e5de2dc516a2b381 TO lb_book_key_idx');
    }
}
