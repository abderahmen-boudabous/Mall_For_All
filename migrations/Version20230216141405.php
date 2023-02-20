<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216141405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE home (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rec_t (id INT AUTO_INCREMENT NOT NULL, nom_t VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE rect');
        $this->addSql('ALTER TABLE rec CHANGE rect rec_t_id INT NOT NULL, CHANGE UserR user_r VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rec ADD CONSTRAINT FK_6405CA2C1BA724C1 FOREIGN KEY (rec_t_id) REFERENCES rec_t (id)');
        $this->addSql('CREATE INDEX IDX_6405CA2C1BA724C1 ON rec (rec_t_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rec DROP FOREIGN KEY FK_6405CA2C1BA724C1');
        $this->addSql('CREATE TABLE rect (id INT AUTO_INCREMENT NOT NULL, nomT VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE home');
        $this->addSql('DROP TABLE rec_t');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX IDX_6405CA2C1BA724C1 ON rec');
        $this->addSql('ALTER TABLE rec CHANGE user_r UserR VARCHAR(255) NOT NULL, CHANGE rec_t_id rect INT NOT NULL');
    }
}
