<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160526160135 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_login (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ip VARCHAR(255) NOT NULL, host VARCHAR(255) NOT NULL, agent LONGTEXT NOT NULL, client_type VARCHAR(255) DEFAULT NULL, client_name VARCHAR(255) DEFAULT NULL, client_short_name VARCHAR(255) DEFAULT NULL, client_version VARCHAR(255) DEFAULT NULL, client_engine VARCHAR(255) DEFAULT NULL, os_name VARCHAR(255) DEFAULT NULL, os_short_name VARCHAR(255) DEFAULT NULL, os_version VARCHAR(255) DEFAULT NULL, os_platform VARCHAR(255) DEFAULT NULL, device_name VARCHAR(255) DEFAULT NULL, brand_name VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, INDEX user (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_login ADD CONSTRAINT FK_48CA3048A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_login');
    }
}
