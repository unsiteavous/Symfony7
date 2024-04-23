<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419071510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB ROW_FORMAT=DYNAMIC' );
        $this->addSql('CREATE TABLE classification (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, avertissement VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_456BD231376925A6 (intitule), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB ROW_FORMAT=DYNAMIC');
        $this->addSql('ALTER TABLE film CHANGE nom titre VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE22FF7747B4 ON film (titre)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE classification');
        $this->addSql('DROP INDEX UNIQ_8244BE22FF7747B4 ON film');
        $this->addSql('ALTER TABLE film CHANGE titre nom VARCHAR(255) NOT NULL');
    }
}
