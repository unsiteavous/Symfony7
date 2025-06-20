<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620072010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE classification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE film ADD classification_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE film ADD CONSTRAINT FK_8244BE222A86559F FOREIGN KEY (classification_id) REFERENCES classification (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8244BE222A86559F ON film (classification_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE film DROP FOREIGN KEY FK_8244BE222A86559F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE classification
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8244BE222A86559F ON film
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE film DROP classification_id
        SQL);
    }
}
