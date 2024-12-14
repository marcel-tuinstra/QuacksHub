<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214214443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "investment_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "investment" (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(64) NOT NULL, company VARCHAR(64) NOT NULL, url VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, category VARCHAR(64) NOT NULL, rate DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43CA0AD67E3C61F9 ON "investment" (owner_id)');
        $this->addSql('COMMENT ON COLUMN "investment".category IS \'(DC2Type:investment:category)\'');
        $this->addSql('COMMENT ON COLUMN "investment".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "investment".deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "investment" ADD CONSTRAINT FK_43CA0AD67E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "investment_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "investment" DROP CONSTRAINT FK_43CA0AD67E3C61F9');
        $this->addSql('DROP TABLE "investment"');
    }
}
