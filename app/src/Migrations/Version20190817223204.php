<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190817223204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Migration of "companies" table';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE companies (
          id INT AUTO_INCREMENT NOT NULL, 
          symbol VARCHAR(10) NOT NULL, 
          name VARCHAR(250) NOT NULL, 
          ipo_year VARCHAR(4) DEFAULT NULL, 
          sector VARCHAR(255) DEFAULT NULL, 
          industry VARCHAR(255) DEFAULT NULL, 
          UNIQUE INDEX UNIQ_8244AA3AECC836F9 (symbol), 
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE companies');
    }
}
