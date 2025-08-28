<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250827151321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_tag (album_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6397379A1137ABCF (album_id), INDEX IDX_6397379ABAD26311 (tag_id), PRIMARY KEY(album_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album_tag ADD CONSTRAINT FK_6397379A1137ABCF FOREIGN KEY (album_id) REFERENCES albums (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_tag ADD CONSTRAINT FK_6397379ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_tags DROP FOREIGN KEY FK_BB722661137ABCF');
        $this->addSql('ALTER TABLE album_tags DROP FOREIGN KEY FK_BB72266BAD26311');
        $this->addSql('DROP TABLE album_tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_tags (album_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_BB722661137ABCF (album_id), INDEX IDX_BB72266BAD26311 (tag_id), PRIMARY KEY(album_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE album_tags ADD CONSTRAINT FK_BB722661137ABCF FOREIGN KEY (album_id) REFERENCES albums (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_tags ADD CONSTRAINT FK_BB72266BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_tag DROP FOREIGN KEY FK_6397379A1137ABCF');
        $this->addSql('ALTER TABLE album_tag DROP FOREIGN KEY FK_6397379ABAD26311');
        $this->addSql('DROP TABLE album_tag');
    }
}
