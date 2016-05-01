<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160501092641 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX createdBy_id (createdBy_id), INDEX updatedBy_id (updatedBy_id), INDEX deletedBy_id (deletedBy_id), UNIQUE INDEX uq_username (username), UNIQUE INDEX uq_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user_group (user_id INT NOT NULL, user_group_id INT NOT NULL, INDEX IDX_28657971A76ED395 (user_id), INDEX IDX_286579711ED93D47 (user_group_id), PRIMARY KEY(user_id, user_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, releaseDate DATE NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX author (author), INDEX createdBy_id (createdBy_id), INDEX updatedBy_id (updatedBy_id), INDEX deletedBy_id (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(20) NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX createdBy_id (createdBy_id), INDEX updatedBy_id (updatedBy_id), INDEX deletedBy_id (deletedBy_id), UNIQUE INDEX uq_role (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX createdBy_id (createdBy_id), INDEX updatedBy_id (updatedBy_id), INDEX deletedBy_id (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64965FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64963D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_28657971A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_286579711ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331BDAFD8C8 FOREIGN KEY (author) REFERENCES author (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3313174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33165FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33163D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D3174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C83174800F FOREIGN KEY (createdBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C865FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C863D8C20E FOREIGN KEY (deletedBy_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493174800F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64965FF1AEC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64963D8C20E');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_28657971A76ED395');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3313174800F');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33165FF1AEC');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33163D8C20E');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D3174800F');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D65FF1AEC');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D63D8C20E');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C83174800F');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C865FF1AEC');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C863D8C20E');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_286579711ED93D47');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331BDAFD8C8');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user_group');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE author');
    }
}
