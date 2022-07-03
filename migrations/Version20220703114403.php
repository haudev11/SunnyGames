<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220703114403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE current_game (id INT AUTO_INCREMENT NOT NULL, user_one_id INT NOT NULL, user_two_id INT NOT NULL, game_play LONGBLOB NOT NULL, time_user_one DATETIME NOT NULL, time_user_two DATETIME NOT NULL, game_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EC9858EA9EC8D52E (user_one_id), UNIQUE INDEX UNIQ_EC9858EAF59432E1 (user_two_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE current_game ADD CONSTRAINT FK_EC9858EA9EC8D52E FOREIGN KEY (user_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE current_game ADD CONSTRAINT FK_EC9858EAF59432E1 FOREIGN KEY (user_two_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE current_game');
    }
}
