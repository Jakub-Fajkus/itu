<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20171207221707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO symfony.project (id, name) VALUES (1, 'Bez projektu');");
        $this->addSql("INSERT INTO symfony.project (id, name) VALUES (2, 'Project #1');");
        $this->addSql("INSERT INTO symfony.project (id, name) VALUES (3, 'Project #2');");
        $this->addSql("INSERT INTO symfony.tag (id, name) VALUES (2, 'tag2');");
        $this->addSql("INSERT INTO symfony.tag (id, name) VALUES (3, 'tag3');");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due) VALUES (1, 3, 'Task #1', 0, '2017-12-07 21:07:22', '2017-01-01 00:00:00');");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due) VALUES (3, 1, 'Task #2', 1, '2017-12-07 21:14:06', '2017-12-09 21:13:00');");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due) VALUES (4, 2, 'Task #3', 0, '2017-12-07 21:14:22', '2018-12-07 21:14:00');");
        $this->addSql("INSERT INTO symfony.task_tag (task_id, tag_id) VALUES (1, 2);");
        $this->addSql("INSERT INTO symfony.task_tag (task_id, tag_id) VALUES (1, 3);");
        $this->addSql("INSERT INTO symfony.task_tag (task_id, tag_id) VALUES (3, 3);");
        $this->addSql("INSERT INTO symfony.task_tag (task_id, tag_id) VALUES (4, 2);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
