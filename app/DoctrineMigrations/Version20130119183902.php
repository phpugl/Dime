<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130119183316 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE project_tags (project_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_562D5C3E166D1F9C (project_id), INDEX IDX_562D5C3EBAD26311 (tag_id), PRIMARY KEY(project_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE customer_tags (customer_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3B2D30519395C3F3 (customer_id), INDEX IDX_3B2D3051BAD26311 (tag_id), PRIMARY KEY(customer_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE service_tags (service_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_A1FF20CAED5CA9E6 (service_id), INDEX IDX_A1FF20CABAD26311 (tag_id), PRIMARY KEY(service_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE project_tags ADD CONSTRAINT FK_562D5C3E166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE project_tags ADD CONSTRAINT FK_562D5C3EBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE customer_tags ADD CONSTRAINT FK_3B2D30519395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE customer_tags ADD CONSTRAINT FK_3B2D3051BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE service_tags ADD CONSTRAINT FK_A1FF20CAED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE service_tags ADD CONSTRAINT FK_A1FF20CABAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE tags ADD system TINYINT(1) NOT NULL DEFAULT 0");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("DROP TABLE project_tags");
        $this->addSql("DROP TABLE customer_tags");
        $this->addSql("DROP TABLE service_tags");
        $this->addSql("ALTER TABLE tags DROP system");
    }
}
