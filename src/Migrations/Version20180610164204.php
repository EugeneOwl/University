<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180610164204 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sprint_task (sprint_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_69BC74098C24077B (sprint_id), INDEX IDX_69BC74098DB60186 (task_id), PRIMARY KEY(sprint_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC74098C24077B FOREIGN KEY (sprint_id) REFERENCES sprints (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC74098DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sprint_task');
    }
}
