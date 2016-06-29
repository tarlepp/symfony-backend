<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160629224807 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request_log DROP FOREIGN KEY FK_421529893174800F');
        $this->addSql('ALTER TABLE request_log DROP FOREIGN KEY FK_4215298963D8C20E');
        $this->addSql('ALTER TABLE request_log DROP FOREIGN KEY FK_4215298965FF1AEC');
        $this->addSql('DROP INDEX createdBy_id ON request_log');
        $this->addSql('DROP INDEX updatedBy_id ON request_log');
        $this->addSql('DROP INDEX deletedBy_id ON request_log');
        $this->addSql('ALTER TABLE request_log ADD user_id INT DEFAULT NULL AFTER id, ADD time DATETIME NOT NULL AFTER response_content_length, DROP createdAt, DROP updatedAt, DROP createdBy_id, DROP updatedBy_id, DROP deletedBy_id');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_42152989A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX user_id ON request_log (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request_log DROP FOREIGN KEY FK_42152989A76ED395');
        $this->addSql('DROP INDEX user_id ON request_log');
        $this->addSql('ALTER TABLE request_log ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD updatedBy_id INT DEFAULT NULL, ADD deletedBy_id INT DEFAULT NULL, DROP time, CHANGE user_id createdBy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_421529893174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_4215298963D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_4215298965FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX createdBy_id ON request_log (createdBy_id)');
        $this->addSql('CREATE INDEX updatedBy_id ON request_log (updatedBy_id)');
        $this->addSql('CREATE INDEX deletedBy_id ON request_log (deletedBy_id)');
    }
}
