<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130226113834 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE timeslices ADD user_id INT NOT NULL DEFAULT 1");
        $this->addSql("ALTER TABLE timeslices ADD CONSTRAINT FK_72C53BF4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        $this->addSql("CREATE INDEX IDX_72C53BF4A76ED395 ON timeslices (user_id)");
        $this->addSql("ALTER TABLE tags CHANGE system system TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE users CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE tags CHANGE system system TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("ALTER TABLE timeslices DROP FOREIGN KEY FK_72C53BF4A76ED395");
        $this->addSql("DROP INDEX IDX_72C53BF4A76ED395 ON timeslices");
        $this->addSql("ALTER TABLE timeslices DROP user_id");
        $this->addSql("ALTER TABLE users CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL");
    }
}
