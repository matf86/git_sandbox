<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180822093026 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE issues ADD CONSTRAINT FK_DA7D7F8316A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE issues ADD CONSTRAINT FK_DA7D7F83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DA7D7F8316A2B381 ON issues (book_id)');
        $this->addSql('CREATE INDEX IDX_DA7D7F83A76ED395 ON issues (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE issues DROP FOREIGN KEY FK_DA7D7F8316A2B381');
        $this->addSql('ALTER TABLE issues DROP FOREIGN KEY FK_DA7D7F83A76ED395');
        $this->addSql('DROP INDEX IDX_DA7D7F8316A2B381 ON issues');
        $this->addSql('DROP INDEX IDX_DA7D7F83A76ED395 ON issues');
    }
}
