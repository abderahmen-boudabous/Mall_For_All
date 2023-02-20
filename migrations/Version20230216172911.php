<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216172911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_f (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_369ECA32BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA32BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_f (id)');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY fk_rec_rect');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY fk_rec_rect');
        $this->addSql('ALTER TABLE rec CHANGE reponse reponse VARCHAR(255) NOT NULL, CHANGE date date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT FK_6405CA2C1BA724C1 FOREIGN KEY (rec_t_id) REFERENCES rec_t (id)');
        $this->addSql('DROP INDEX fk_rec_rect ON rec');
        $this->addSql('CREATE INDEX IDX_6405CA2C1BA724C1 ON rec (rec_t_id)');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT fk_rec_rect FOREIGN KEY (rec_t_id) REFERENCES rec_t (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA32BCF5E72D');
        $this->addSql('DROP TABLE categorie_f');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY FK_6405CA2C1BA724C1');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY FK_6405CA2C1BA724C1');
        $this->addSql('ALTER TABLE rec CHANGE reponse reponse VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT fk_rec_rect FOREIGN KEY (rec_t_id) REFERENCES rec_t (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_6405ca2c1ba724c1 ON rec');
        $this->addSql('CREATE INDEX fk_rec_rect ON rec (rec_t_id)');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT FK_6405CA2C1BA724C1 FOREIGN KEY (rec_t_id) REFERENCES rec_t (id)');
    }
}
