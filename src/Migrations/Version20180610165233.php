<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180610165233 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sprint_user (sprint_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B65179658C24077B (sprint_id), INDEX IDX_B6517965A76ED395 (user_id), PRIMARY KEY(sprint_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sprint_usergroup (sprint_id INT NOT NULL, usergroup_id INT NOT NULL, INDEX IDX_F5C41E578C24077B (sprint_id), INDEX IDX_F5C41E57D2112630 (usergroup_id), PRIMARY KEY(sprint_id, usergroup_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sprint_user ADD CONSTRAINT FK_B65179658C24077B FOREIGN KEY (sprint_id) REFERENCES sprints (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sprint_user ADD CONSTRAINT FK_B6517965A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sprint_usergroup ADD CONSTRAINT FK_F5C41E578C24077B FOREIGN KEY (sprint_id) REFERENCES sprints (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sprint_usergroup ADD CONSTRAINT FK_F5C41E57D2112630 FOREIGN KEY (usergroup_id) REFERENCES usergroups (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sprint_user');
        $this->addSql('DROP TABLE sprint_usergroup');
    }
}
