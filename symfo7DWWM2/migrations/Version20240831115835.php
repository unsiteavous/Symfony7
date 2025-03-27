<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831115835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test_categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CBE041FE6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_classification (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, avertissement VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3A272A3C376925A6 (intitule), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_film (id INT AUTO_INCREMENT NOT NULL, classification_id INT NOT NULL, titre VARCHAR(255) NOT NULL, url_affiche VARCHAR(255) NOT NULL, lien_trailer VARCHAR(255) DEFAULT NULL, duree DATETIME NOT NULL, date_sortie DATETIME NOT NULL, UNIQUE INDEX UNIQ_873D94EDFF7747B4 (titre), INDEX IDX_873D94ED2A86559F (classification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_film_categorie (film_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_2FD700A567F5183 (film_id), INDEX IDX_2FD700ABCF5E72D (categorie_id), PRIMARY KEY(film_id, categorie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, places INT NOT NULL, accessibilite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, annee_arrivee DATE NOT NULL, rgpd DATE NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_88EAFC86450FF010 (telephone), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE test_film ADD CONSTRAINT FK_873D94ED2A86559F FOREIGN KEY (classification_id) REFERENCES test_classification (id)');
        $this->addSql('ALTER TABLE test_film_categorie ADD CONSTRAINT FK_2FD700A567F5183 FOREIGN KEY (film_id) REFERENCES test_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_film_categorie ADD CONSTRAINT FK_2FD700ABCF5E72D FOREIGN KEY (categorie_id) REFERENCES test_categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE test_film DROP FOREIGN KEY FK_873D94ED2A86559F');
        $this->addSql('ALTER TABLE test_film_categorie DROP FOREIGN KEY FK_2FD700A567F5183');
        $this->addSql('ALTER TABLE test_film_categorie DROP FOREIGN KEY FK_2FD700ABCF5E72D');
        $this->addSql('DROP TABLE test_categorie');
        $this->addSql('DROP TABLE test_classification');
        $this->addSql('DROP TABLE test_film');
        $this->addSql('DROP TABLE test_film_categorie');
        $this->addSql('DROP TABLE test_salle');
        $this->addSql('DROP TABLE test_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
