<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260701120756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, code_fifa VARCHAR(3) NOT NULL, groupe VARCHAR(1) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pronostic (id INT AUTO_INCREMENT NOT NULL, score_domicile_pronostique INT NOT NULL, score_exterieur_pronostique INT NOT NULL, points INT DEFAULT NULL, utilisateur_id INT NOT NULL, rencontre_id INT NOT NULL, INDEX IDX_E64BDCDEFB88E14F (utilisateur_id), INDEX IDX_E64BDCDE6CFC0818 (rencontre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rencontre (id INT AUTO_INCREMENT NOT NULL, date_heure DATETIME NOT NULL, score_domicile INT DEFAULT NULL, score_exterieur INT DEFAULT NULL, statut VARCHAR(20) NOT NULL, phase VARCHAR(20) NOT NULL, equipe_domicile_id INT NOT NULL, equipe_exterieur_id INT NOT NULL, INDEX IDX_460C35ED5FE1AEAD (equipe_domicile_id), INDEX IDX_460C35ED21ECD755 (equipe_exterieur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(50) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pronostic ADD CONSTRAINT FK_E64BDCDEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE pronostic ADD CONSTRAINT FK_E64BDCDE6CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED5FE1AEAD FOREIGN KEY (equipe_domicile_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED21ECD755 FOREIGN KEY (equipe_exterieur_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pronostic DROP FOREIGN KEY FK_E64BDCDEFB88E14F');
        $this->addSql('ALTER TABLE pronostic DROP FOREIGN KEY FK_E64BDCDE6CFC0818');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED5FE1AEAD');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED21ECD755');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE pronostic');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE utilisateur');
    }
}
