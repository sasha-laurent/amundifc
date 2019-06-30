<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190629211030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F8C3DE27B');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP INDEX IDX_C4E0A61F8C3DE27B ON team');
        $this->addSql('ALTER TABLE team CHANGE related_match_id related_game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FDB9613A0 FOREIGN KEY (related_game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FDB9613A0 ON team (related_game_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FDB9613A0');
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP INDEX IDX_C4E0A61FDB9613A0 ON team');
        $this->addSql('ALTER TABLE team CHANGE related_game_id related_match_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F8C3DE27B FOREIGN KEY (related_match_id) REFERENCES `match` (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F8C3DE27B ON team (related_match_id)');
    }
}
