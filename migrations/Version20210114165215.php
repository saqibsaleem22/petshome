<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114165215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals ADD placer_id INT NOT NULL');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD3BABD422 FOREIGN KEY (placer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_966C69DD3BABD422 ON animals (placer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD3BABD422');
        $this->addSql('DROP INDEX IDX_966C69DD3BABD422 ON animals');
        $this->addSql('ALTER TABLE animals DROP placer_id');
    }
}
