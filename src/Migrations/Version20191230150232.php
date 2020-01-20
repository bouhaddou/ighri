<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191230150232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amis (id INT AUTO_INCREMENT NOT NULL, friend_envoi_id INT NOT NULL, relation VARCHAR(255) NOT NULL, INDEX IDX_9FE2E761A8718AAF (friend_envoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, author_id INT NOT NULL, comment LONGTEXT NOT NULL, rationg INT NOT NULL, datepub DATETIME NOT NULL, INDEX IDX_5F9E962A4B89032C (post_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compteur (id INT AUTO_INCREMENT NOT NULL, cpt INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nomcomplet VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, objet VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, valide TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, posts_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_C53D045FD5E258C5 (posts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, avatar VARCHAR(255) DEFAULT NULL, datepub DATETIME NOT NULL, INDEX IDX_885DBAFAF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles_user (roles_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_57048B3038C751C4 (roles_id), INDEX IDX_57048B30A76ED395 (user_id), PRIMARY KEY(roles_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE safran (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, datepub DATETIME NOT NULL, INDEX IDX_7F28269F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password_user VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, couverture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vedio (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, lien VARCHAR(255) NOT NULL, datepub DATETIME NOT NULL, INDEX IDX_9AA42A98F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761A8718AAF FOREIGN KEY (friend_envoi_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE roles_user ADD CONSTRAINT FK_57048B3038C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roles_user ADD CONSTRAINT FK_57048B30A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE safran ADD CONSTRAINT FK_7F28269F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vedio ADD CONSTRAINT FK_9AA42A98F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD5E258C5');
        $this->addSql('ALTER TABLE roles_user DROP FOREIGN KEY FK_57048B3038C751C4');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E761A8718AAF');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAF675F31B');
        $this->addSql('ALTER TABLE roles_user DROP FOREIGN KEY FK_57048B30A76ED395');
        $this->addSql('ALTER TABLE safran DROP FOREIGN KEY FK_7F28269F675F31B');
        $this->addSql('ALTER TABLE vedio DROP FOREIGN KEY FK_9AA42A98F675F31B');
        $this->addSql('DROP TABLE amis');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE compteur');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE roles_user');
        $this->addSql('DROP TABLE safran');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vedio');
    }
}
