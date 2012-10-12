<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Add settings table
 */
class Version20121012091006 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, `key` VARCHAR(255) NOT NULL, namespace VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E545A0C5A76ED395 (user_id), UNIQUE INDEX unique_setting_key_user (`key`, namespace, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("DROP TABLE settings");
    }
}
