<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222134658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, recm_id INT DEFAULT NULL, contenum VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F307CBEF4 (recm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F307CBEF4 FOREIGN KEY (recm_id) REFERENCES rec (id)');
        $this->addSql('DROP TABLE home');
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
        $this->addSql('CREATE TABLE home (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F307CBEF4');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY FK_6405CA2C1BA724C1');
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY FK_6405CA2C1BA724C1');
        $this->addSql('ALTER TABLE rec CHANGE reponse reponse VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT fk_rec_rect FOREIGN KEY (rec_t_id) REFERENCES rec_t (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_6405ca2c1ba724c1 ON rec');
        $this->addSql('CREATE INDEX fk_rec_rect ON rec (rec_t_id)');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT FK_6405CA2C1BA724C1 FOREIGN KEY (rec_t_id) REFERENCES rec_t (id)');
    }
}
