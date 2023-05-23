<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521112157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE disliked_books (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_A1616FFFA76ED395 (user_id), INDEX IDX_A1616FFF16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disliked_books ADD CONSTRAINT FK_A1616FFFA76ED395 FOREIGN KEY (user_id) REFERENCES local_bookable.users (id)');
        $this->addSql('ALTER TABLE disliked_books ADD CONSTRAINT FK_A1616FFF16A2B381 FOREIGN KEY (book_id) REFERENCES local_bookable.books (id)');
       /*
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_A86EA5EFF675F31B FOREIGN KEY (author_id) REFERENCES local_bookable.authors (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_A86EA5EF4296D31F FOREIGN KEY (genre_id) REFERENCES local_bookable.genres (id)');
        $this->addSql('CREATE INDEX IDX_A86EA5EFF675F31B ON books (author_id)');
        $this->addSql('CREATE INDEX IDX_A86EA5EF4296D31F ON books (genre_id)');
        $this->addSql('ALTER TABLE followed_books ADD CONSTRAINT FK_B7A731D1A76ED395 FOREIGN KEY (user_id) REFERENCES local_bookable.users (id)');
        $this->addSql('ALTER TABLE followed_books ADD CONSTRAINT FK_B7A731D116A2B381 FOREIGN KEY (book_id) REFERENCES local_bookable.books (id)');
        $this->addSql('CREATE INDEX IDX_B7A731D1A76ED395 ON followed_books (user_id)');
        $this->addSql('CREATE INDEX IDX_B7A731D116A2B381 ON followed_books (book_id)');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT FK_26134968A76ED395 FOREIGN KEY (user_id) REFERENCES local_bookable.users (id)');
        $this->addSql('ALTER TABLE liked_books ADD CONSTRAINT FK_2613496816A2B381 FOREIGN KEY (book_id) REFERENCES local_bookable.books (id)');
        $this->addSql('CREATE INDEX IDX_26134968A76ED395 ON liked_books (user_id)');
        $this->addSql('CREATE INDEX IDX_2613496816A2B381 ON liked_books (book_id)');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT FK_EF5850024296D31F FOREIGN KEY (genre_id) REFERENCES local_bookable.genres (id)');
        $this->addSql('ALTER TABLE liked_genre ADD CONSTRAINT FK_EF585002A76ED395 FOREIGN KEY (user_id) REFERENCES local_bookable.users (id)');
        $this->addSql('CREATE INDEX IDX_EF5850024296D31F ON liked_genre (genre_id)');
        $this->addSql('CREATE INDEX IDX_EF585002A76ED395 ON liked_genre (user_id)');
        $this->addSql('ALTER TABLE read_books CHANGE user_id user_id INT NOT NULL, CHANGE book_id book_id INT NOT NULL');
        $this->addSql('ALTER TABLE read_books ADD CONSTRAINT FK_9F91EF80A76ED395 FOREIGN KEY (user_id) REFERENCES local_bookable.users (id)');
        $this->addSql('ALTER TABLE read_books ADD CONSTRAINT FK_9F91EF8016A2B381 FOREIGN KEY (book_id) REFERENCES local_bookable.books (id)');
        $this->addSql('CREATE INDEX IDX_9F91EF80A76ED395 ON read_books (user_id)');
        $this->addSql('CREATE INDEX IDX_9F91EF8016A2B381 ON read_books (book_id)');
        $this->addSql('ALTER TABLE users CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_F6F62A9486383B10 FOREIGN KEY (avatar_id) REFERENCES local_bookable.avatars (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_F6F62A94FE2541D7 FOREIGN KEY (library_id) REFERENCES local_bookable.libraries (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6F62A94E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_F6F62A9486383B10 ON users (avatar_id)');
        $this->addSql('CREATE INDEX IDX_F6F62A94FE2541D7 ON users (library_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE body body LONGTEXT NOT NULL, CHANGE headers headers LONGTEXT NOT NULL, CHANGE queue_name queue_name VARCHAR(190) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
       */
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disliked_books DROP FOREIGN KEY FK_A1616FFFA76ED395');
        $this->addSql('ALTER TABLE disliked_books DROP FOREIGN KEY FK_A1616FFF16A2B381');
        $this->addSql('DROP TABLE disliked_books');
        $this->addSql('ALTER TABLE local_bookable.followed_books DROP FOREIGN KEY FK_B7A731D1A76ED395');
        $this->addSql('ALTER TABLE local_bookable.followed_books DROP FOREIGN KEY FK_B7A731D116A2B381');
        $this->addSql('DROP INDEX IDX_B7A731D1A76ED395 ON local_bookable.followed_books');
        $this->addSql('DROP INDEX IDX_B7A731D116A2B381 ON local_bookable.followed_books');
        $this->addSql('ALTER TABLE local_bookable.liked_books DROP FOREIGN KEY FK_26134968A76ED395');
        $this->addSql('ALTER TABLE local_bookable.liked_books DROP FOREIGN KEY FK_2613496816A2B381');
        $this->addSql('DROP INDEX IDX_26134968A76ED395 ON local_bookable.liked_books');
        $this->addSql('DROP INDEX IDX_2613496816A2B381 ON local_bookable.liked_books');
        $this->addSql('ALTER TABLE local_bookable.liked_genre DROP FOREIGN KEY FK_EF5850024296D31F');
        $this->addSql('ALTER TABLE local_bookable.liked_genre DROP FOREIGN KEY FK_EF585002A76ED395');
        $this->addSql('DROP INDEX IDX_EF5850024296D31F ON local_bookable.liked_genre');
        $this->addSql('DROP INDEX IDX_EF585002A76ED395 ON local_bookable.liked_genre');
        $this->addSql('ALTER TABLE messenger_messages MODIFY id BIGINT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT DEFAULT NULL, CHANGE body body LONGTEXT DEFAULT NULL, CHANGE headers headers LONGTEXT DEFAULT NULL, CHANGE queue_name queue_name VARCHAR(190) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE available_at available_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE local_bookable.read_books DROP FOREIGN KEY FK_9F91EF80A76ED395');
        $this->addSql('ALTER TABLE local_bookable.read_books DROP FOREIGN KEY FK_9F91EF8016A2B381');
        $this->addSql('DROP INDEX IDX_9F91EF80A76ED395 ON local_bookable.read_books');
        $this->addSql('DROP INDEX IDX_9F91EF8016A2B381 ON local_bookable.read_books');
        $this->addSql('ALTER TABLE local_bookable.read_books CHANGE user_id user_id INT DEFAULT NULL, CHANGE book_id book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE local_bookable.users DROP FOREIGN KEY FK_F6F62A9486383B10');
        $this->addSql('ALTER TABLE local_bookable.users DROP FOREIGN KEY FK_F6F62A94FE2541D7');
        $this->addSql('DROP INDEX UNIQ_F6F62A94E7927C74 ON local_bookable.users');
        $this->addSql('DROP INDEX IDX_F6F62A9486383B10 ON local_bookable.users');
        $this->addSql('DROP INDEX IDX_F6F62A94FE2541D7 ON local_bookable.users');
        $this->addSql('ALTER TABLE local_bookable.users CHANGE email email VARCHAR(180) DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE local_bookable.books DROP FOREIGN KEY FK_A86EA5EFF675F31B');
        $this->addSql('ALTER TABLE local_bookable.books DROP FOREIGN KEY FK_A86EA5EF4296D31F');
        $this->addSql('DROP INDEX IDX_A86EA5EFF675F31B ON local_bookable.books');
        $this->addSql('DROP INDEX IDX_A86EA5EF4296D31F ON local_bookable.books');
    }
}
