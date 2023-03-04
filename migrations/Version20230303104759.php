<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303104759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP adresse, DROP codepostale, DROP numtel, DROP ville, DROP nom_boutique, DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD adresse VARCHAR(255) DEFAULT NULL, ADD codepostale VARCHAR(255) NOT NULL, ADD numtel VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD nom_boutique VARCHAR(255) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL');
    }
}
