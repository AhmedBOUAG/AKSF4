<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210930122802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE actualite ADD created_at DATETIME DEFAULT NULL, ADD slug VARCHAR(128) NOT NULL, DROP date');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54928197989D9B62 ON actualite (slug)');
        $this->addSql('ALTER TABLE comment CHANGE thread_id thread_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE path path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE thread CHANGE last_comment_at last_comment_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE vote CHANGE comment_id comment_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_54928197989D9B62 ON actualite');
        $this->addSql('ALTER TABLE actualite ADD date DATETIME DEFAULT \'NULL\', DROP created_at, DROP slug');
        $this->addSql('ALTER TABLE comment CHANGE thread_id thread_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE document CHANGE path path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE thread CHANGE last_comment_at last_comment_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE vote CHANGE comment_id comment_id INT DEFAULT NULL');
    }
}
