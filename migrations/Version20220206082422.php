<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206082422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, answer VARCHAR(128) DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, commercial_name VARCHAR(255) NOT NULL, legal_name VARCHAR(255) DEFAULT NULL, tax_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam_company (exam_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_42195027578D5E91 (exam_id), INDEX IDX_42195027979B1AD6 (company_id), PRIMARY KEY(exam_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, exam_id INT NOT NULL, question VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_field (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_option (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, question_option VARCHAR(255) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_5DDB2FB81E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_question_field (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, question_field_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, INDEX IDX_833EC1A51E27F6BF (question_id), INDEX IDX_833EC1A5273FB7BC (question_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, exam_id INT NOT NULL, user_id INT NOT NULL, answer_id INT NOT NULL, status VARCHAR(128) NOT NULL, date DATE DEFAULT NULL, attended TINYINT(1) DEFAULT NULL, INDEX IDX_D87F7E0C578D5E91 (exam_id), INDEX IDX_D87F7E0CA76ED395 (user_id), INDEX IDX_D87F7E0CAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE exam_company ADD CONSTRAINT FK_42195027578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exam_company ADD CONSTRAINT FK_42195027979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE question_option ADD CONSTRAINT FK_5DDB2FB81E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_question_field ADD CONSTRAINT FK_833EC1A51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_question_field ADD CONSTRAINT FK_833EC1A5273FB7BC FOREIGN KEY (question_field_id) REFERENCES question_field (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CAA334807');
        $this->addSql('ALTER TABLE exam_company DROP FOREIGN KEY FK_42195027979B1AD6');
        $this->addSql('ALTER TABLE exam_company DROP FOREIGN KEY FK_42195027578D5E91');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E578D5E91');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C578D5E91');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question_option DROP FOREIGN KEY FK_5DDB2FB81E27F6BF');
        $this->addSql('ALTER TABLE question_question_field DROP FOREIGN KEY FK_833EC1A51E27F6BF');
        $this->addSql('ALTER TABLE question_question_field DROP FOREIGN KEY FK_833EC1A5273FB7BC');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE exam_company');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_field');
        $this->addSql('DROP TABLE question_option');
        $this->addSql('DROP TABLE question_question_field');
        $this->addSql('DROP TABLE test');
    }
}
