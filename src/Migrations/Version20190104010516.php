<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190104010516 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE lastname lastname VARCHAR(50) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE display_name display_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post CHANGE slug slug VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user CHANGE lastname lastname VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE display_name display_name VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
