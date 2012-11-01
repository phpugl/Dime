<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Add Tags with relation to Activitiy and Timeslices
 */
class Version20121031191344 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6FBC9426A76ED395 (user_id), UNIQUE INDEX unique_tag_name_user (name, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE tags ADD CONSTRAINT FK_6FBC9426A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");

        $this->addSql("CREATE TABLE activity_tags (activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71B0290181C06096 (activity_id), INDEX IDX_71B02901BAD26311 (tag_id), PRIMARY KEY(activity_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE activity_tags ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activities (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE activity_tags ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");

        $this->addSql("CREATE TABLE timeslice_tags (timeslice_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_8C1CA57C4FB5678C (timeslice_id), INDEX IDX_8C1CA57CBAD26311 (tag_id), PRIMARY KEY(timeslice_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE timeslice_tags ADD CONSTRAINT FK_8C1CA57C4FB5678C FOREIGN KEY (timeslice_id) REFERENCES timeslices (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE timeslice_tags ADD CONSTRAINT FK_8C1CA57CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");

    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B02901BAD26311");
        $this->addSql("ALTER TABLE timeslice_tag DROP FOREIGN KEY FK_8C1CA57CBAD26311");

        $this->addSql("DROP TABLE activity_tag");
        $this->addSql("DROP TABLE timeslice_tag");
        $this->addSql("DROP TABLE tags");
    }
}
