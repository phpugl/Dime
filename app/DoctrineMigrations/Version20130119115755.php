<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130119115755 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP INDEX unique_setting_key_user ON settings");
        $this->addSql("ALTER TABLE settings CHANGE `key` name VARCHAR(255) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX unique_setting_name_namespace_user ON settings (`name`, namespace, user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP INDEX unique_setting_name_namespace_user ON settings");
        $this->addSql("ALTER TABLE settings CHANGE name `key` VARCHAR(255) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX unique_setting_key_user ON settings (key, namespace, user_id)");
    }
}
