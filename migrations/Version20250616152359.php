<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616152359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE inscription ADD nationalite VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D68B13D439 FOREIGN KEY (id_evenement) REFERENCES event (id_evenement)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5E90F6D68B13D439 ON inscription (id_evenement)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D68B13D439
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5E90F6D68B13D439 ON inscription
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inscription DROP nationalite
        SQL);
    }
}
