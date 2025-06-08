<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607154721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE artiste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "release" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, artiste_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL, category VARCHAR(255) NOT NULL, CONSTRAINT FK_9E47031D21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9E47031D21D25844 ON "release" (artiste_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE track (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, album_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_D6E3F8A61137ABCF FOREIGN KEY (album_id) REFERENCES "release" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D6E3F8A61137ABCF ON track (album_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_artiste (user_id INTEGER NOT NULL, artiste_id INTEGER NOT NULL, PRIMARY KEY(user_id, artiste_id), CONSTRAINT FK_C40A2B45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C40A2B4521D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C40A2B45A76ED395 ON user_artiste (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C40A2B4521D25844 ON user_artiste (artiste_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_release (user_id INTEGER NOT NULL, release_id INTEGER NOT NULL, PRIMARY KEY(user_id, release_id), CONSTRAINT FK_C64A1D17A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C64A1D17B12A727D FOREIGN KEY (release_id) REFERENCES "release" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C64A1D17A76ED395 ON user_release (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C64A1D17B12A727D ON user_release (release_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_track (user_id INTEGER NOT NULL, track_id INTEGER NOT NULL, PRIMARY KEY(user_id, track_id), CONSTRAINT FK_342103FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_342103FE5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_342103FEA76ED395 ON user_track (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_342103FE5ED23C43 ON user_track (track_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE artiste
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "release"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE track
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_artiste
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_release
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_track
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
