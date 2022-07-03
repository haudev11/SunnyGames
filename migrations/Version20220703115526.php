<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220703115526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invite_game (id INT AUTO_INCREMENT NOT NULL, from_id_id INT NOT NULL, to_id_id INT NOT NULL, UNIQUE INDEX UNIQ_3B1FF0064632BB48 (from_id_id), INDEX IDX_3B1FF0067478AF67 (to_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invite_game ADD CONSTRAINT FK_3B1FF0064632BB48 FOREIGN KEY (from_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invite_game ADD CONSTRAINT FK_3B1FF0067478AF67 FOREIGN KEY (to_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE invite_game');
    }
}
