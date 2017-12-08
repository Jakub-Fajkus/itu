<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208130116 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE `order` task_order INT NOT NULL');

        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due, task_order) VALUES (5, 2, 'Task #4', 0, '2017-12-07 21:14:22', '2018-12-07 21:14:00', 5);");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due, task_order) VALUES (6, 2, 'Task #5', 0, '2017-12-07 21:14:22', '2018-12-07 21:14:00', 1);");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due, task_order) VALUES (7, 2, 'Task #6', 0, '2017-12-07 21:14:22', '2018-12-07 21:14:00', 3);");
        $this->addSql("INSERT INTO symfony.task (id, project_id, name, priority, createdAt, due, task_order) VALUES (8, 2, 'Task #7', 0, '2017-12-07 21:14:22', '2018-12-07 21:14:00', 4);");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE task CHANGE task_order `order` INT NOT NULL');
    }
}
