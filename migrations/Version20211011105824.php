<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211011105824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE driver_race CHANGE bonus bonus VARCHAR(20) DEFAULT NULL, CHANGE penalty penalty VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE maker DROP FOREIGN KEY fk_country_maker');
        $this->addSql('DROP INDEX fk_country_maker ON maker');
        $this->addSql('ALTER TABLE maker DROP country');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE driver_race CHANGE bonus bonus INT DEFAULT NULL, CHANGE penalty penalty INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maker ADD country INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maker ADD CONSTRAINT fk_country_maker FOREIGN KEY (country) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX fk_country_maker ON maker (country)');
    }
}
