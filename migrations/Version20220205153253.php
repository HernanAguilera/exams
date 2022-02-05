<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205153253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE communication_channel (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, type_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, INDEX IDX_D728A71D217BBB47 (person_id), INDEX IDX_D728A71DC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_channel_type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE communication_channel ADD CONSTRAINT FK_D728A71D217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE communication_channel ADD CONSTRAINT FK_D728A71DC54C8C93 FOREIGN KEY (type_id) REFERENCES communication_channel_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE communication_channel DROP FOREIGN KEY FK_D728A71DC54C8C93');
        $this->addSql('DROP TABLE communication_channel');
        $this->addSql('DROP TABLE communication_channel_type');
    }
}
