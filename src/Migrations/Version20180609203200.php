<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180609203200 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, tasktype_id INT NOT NULL, description LONGTEXT NOT NULL, period INT NOT NULL, INDEX IDX_505865977D6EFC3 (tasktype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasktypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(4096) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_tasks (user_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_B72FC1DEA76ED395 (user_id), INDEX IDX_B72FC1DE8DB60186 (task_id), PRIMARY KEY(user_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usergroups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usergroup_user (usergroup_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E9B5A1C0D2112630 (usergroup_id), INDEX IDX_E9B5A1C0A76ED395 (user_id), PRIMARY KEY(usergroup_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865977D6EFC3 FOREIGN KEY (tasktype_id) REFERENCES tasktypes (id)');
        $this->addSql('ALTER TABLE users_tasks ADD CONSTRAINT FK_B72FC1DEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_tasks ADD CONSTRAINT FK_B72FC1DE8DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usergroup_user ADD CONSTRAINT FK_E9B5A1C0D2112630 FOREIGN KEY (usergroup_id) REFERENCES usergroups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usergroup_user ADD CONSTRAINT FK_E9B5A1C0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_tasks DROP FOREIGN KEY FK_B72FC1DE8DB60186');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865977D6EFC3');
        $this->addSql('ALTER TABLE users_tasks DROP FOREIGN KEY FK_B72FC1DEA76ED395');
        $this->addSql('ALTER TABLE usergroup_user DROP FOREIGN KEY FK_E9B5A1C0A76ED395');
        $this->addSql('ALTER TABLE usergroup_user DROP FOREIGN KEY FK_E9B5A1C0D2112630');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE tasktypes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_tasks');
        $this->addSql('DROP TABLE usergroups');
        $this->addSql('DROP TABLE usergroup_user');
    }
}
