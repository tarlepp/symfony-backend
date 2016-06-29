<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160629213156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE request_log (id INT AUTO_INCREMENT NOT NULL, client_ip VARCHAR(255) NOT NULL, uri LONGTEXT NOT NULL, method VARCHAR(255) NOT NULL, query_string LONGTEXT DEFAULT NULL, headers LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', parameters LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', status_code INT NOT NULL, response_content_length INT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX createdBy_id (createdBy_id), INDEX updatedBy_id (updatedBy_id), INDEX deletedBy_id (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_421529893174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_4215298965FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_4215298963D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE request_log');
    }
}
