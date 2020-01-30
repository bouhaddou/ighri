<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130120103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, nom_complet VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tele VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_image (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_F5A163CBF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, categories_id INT NOT NULL, reference VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, poids DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, informations LONGTEXT DEFAULT NULL, INDEX IDX_BE2DDF8CA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventes (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, produit_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, poids DOUBLE PRECISION NOT NULL, INDEX IDX_64EC489A19EB6921 (client_id), INDEX IDX_64EC489AF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_image ADD CONSTRAINT FK_F5A163CBF347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CA21214B7 FOREIGN KEY (categories_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489AF347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE comments CHANGE post_id post_id INT DEFAULT NULL, CHANGE rationg rationg INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE objet objet VARCHAR(255) DEFAULT NULL, CHANGE valide valide TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE posts_id posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE author_id author_id INT DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE roles_user ADD PRIMARY KEY (roles_id, user_id)');
        $this->addSql('ALTER TABLE safran CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE slug slug VARCHAR(255) DEFAULT NULL, CHANGE couverture couverture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vedio CHANGE author_id author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CA21214B7');
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A19EB6921');
        $this->addSql('ALTER TABLE produit_image DROP FOREIGN KEY FK_F5A163CBF347EFB');
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489AF347EFB');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE produit_image');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE ventes');
        $this->addSql('ALTER TABLE comments CHANGE post_id post_id INT DEFAULT NULL, CHANGE rationg rationg INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE objet objet VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE valide valide TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image CHANGE posts_id posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE author_id author_id INT DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE roles_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE safran CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE slug slug VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE couverture couverture VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE vedio CHANGE author_id author_id INT DEFAULT NULL');
    }
}
