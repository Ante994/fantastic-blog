<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227210728 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favorite DROP INDEX UNIQ_68C58ED9A76ED395, ADD INDEX IDX_68C58ED9A76ED395 (user_id)');
        $this->addSql('ALTER TABLE favorite DROP INDEX UNIQ_68C58ED94B89032C, ADD INDEX IDX_68C58ED94B89032C (post_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favorite DROP INDEX IDX_68C58ED94B89032C, ADD UNIQUE INDEX UNIQ_68C58ED94B89032C (post_id)');
        $this->addSql('ALTER TABLE favorite DROP INDEX IDX_68C58ED9A76ED395, ADD UNIQUE INDEX UNIQ_68C58ED9A76ED395 (user_id)');
    }
}
