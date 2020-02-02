<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201165528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients CHANGE maison maison VARCHAR(255) DEFAULT NULL, CHANGE postal postal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments CHANGE post_id post_id INT DEFAULT NULL, CHANGE rationg rationg INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE objet objet VARCHAR(255) DEFAULT NULL, CHANGE valide valide TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE posts_id posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE author_id author_id INT DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_image CHANGE produit_id produit_id INT DEFAULT NULL, CHANGE caption caption VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE safran CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE slug slug VARCHAR(255) DEFAULT NULL, CHANGE couverture couverture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vedio CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventes ADD mode_paiement VARCHAR(255) NOT NULL, ADD valider TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients CHANGE maison maison VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE postal postal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments CHANGE post_id post_id INT DEFAULT NULL, CHANGE rationg rationg INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE objet objet VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE valide valide TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image CHANGE posts_id posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE author_id author_id INT DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE produit_image CHANGE produit_id produit_id INT DEFAULT NULL, CHANGE caption caption VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE safran CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE slug slug VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE couverture couverture VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE vedio CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventes DROP mode_paiement, DROP valider');
    }
}
