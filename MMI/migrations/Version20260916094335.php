<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260916094335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chaussure_taille (chaussure_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_B7A985EFF8458E35 (chaussure_id), INDEX IDX_B7A985EFFF25611A (taille_id), PRIMARY KEY (chaussure_id, taille_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(15) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE chaussure_taille ADD CONSTRAINT FK_B7A985EFF8458E35 FOREIGN KEY (chaussure_id) REFERENCES chaussure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chaussure_taille ADD CONSTRAINT FK_B7A985EFFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chaussure DROP taille');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chaussure_taille DROP FOREIGN KEY FK_B7A985EFF8458E35');
        $this->addSql('ALTER TABLE chaussure_taille DROP FOREIGN KEY FK_B7A985EFFF25611A');
        $this->addSql('DROP TABLE chaussure_taille');
        $this->addSql('DROP TABLE taille');
        $this->addSql('ALTER TABLE chaussure ADD taille VARCHAR(15) NOT NULL');
    }
}
