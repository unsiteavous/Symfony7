<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831125537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE film_categorie DROP FOREIGN KEY FK_7DB18807567F5183');
        $this->addSql('ALTER TABLE film_categorie DROP FOREIGN KEY FK_7DB18807BCF5E72D');
        $this->addSql('DROP TABLE film');
        $this->addSql('DROP TABLE film_categorie');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE classification');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');



        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prefix_categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2D1E8DC66C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_classification (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(100) NOT NULL, avertissement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_film (id INT AUTO_INCREMENT NOT NULL, classification_id INT NOT NULL, nom VARCHAR(100) NOT NULL, url_affiche VARCHAR(255) NOT NULL, lien_trailer VARCHAR(255) DEFAULT NULL, resume LONGTEXT NOT NULL, duree DATETIME NOT NULL, date_sortie DATETIME NOT NULL, INDEX IDX_877564D32A86559F (classification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_film_categorie (film_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_1849D843567F5183 (film_id), INDEX IDX_1849D843BCF5E72D (categorie_id), PRIMARY KEY(film_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, places INT NOT NULL, accessibilite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, date_arrivee DATE NOT NULL, rgpd DATE NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prefix_film ADD CONSTRAINT FK_877564D32A86559F FOREIGN KEY (classification_id) REFERENCES prefix_classification (id)');
        $this->addSql('ALTER TABLE prefix_film_categorie ADD CONSTRAINT FK_1849D843567F5183 FOREIGN KEY (film_id) REFERENCES prefix_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prefix_film_categorie ADD CONSTRAINT FK_1849D843BCF5E72D FOREIGN KEY (categorie_id) REFERENCES prefix_categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prefix_film DROP FOREIGN KEY FK_877564D32A86559F');
        $this->addSql('ALTER TABLE prefix_film_categorie DROP FOREIGN KEY FK_1849D843567F5183');
        $this->addSql('ALTER TABLE prefix_film_categorie DROP FOREIGN KEY FK_1849D843BCF5E72D');
        $this->addSql('DROP TABLE prefix_categorie');
        $this->addSql('DROP TABLE prefix_classification');
        $this->addSql('DROP TABLE prefix_film');
        $this->addSql('DROP TABLE prefix_film_categorie');
        $this->addSql('DROP TABLE prefix_salle');
        $this->addSql('DROP TABLE prefix_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
