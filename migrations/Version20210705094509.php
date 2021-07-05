<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705094509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9E085865');
        $this->addSql('DROP INDEX IDX_D34A04AD9E085865 ON product');
        $this->addSql('ALTER TABLE product ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP create_at, DROP update_at, CHANGE create_by_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADB03A8386 ON product (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB03A8386');
        $this->addSql('DROP INDEX IDX_D34A04ADB03A8386 ON product');
        $this->addSql('ALTER TABLE product ADD create_at DATETIME NOT NULL, ADD update_at DATETIME NOT NULL, DROP created_at, DROP updated_at, CHANGE created_by_id create_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9E085865 FOREIGN KEY (create_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD9E085865 ON product (create_by_id)');
    }
}
