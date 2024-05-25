<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511201454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, detail CLOB NOT NULL --(DC2Type:json)
        , discount NUMERIC(4, 2) NOT NULL, iva NUMERIC(4, 2) NOT NULL, subtotal NUMERIC(4, 2) NOT NULL, total NUMERIC(6, 2) NOT NULL, CONSTRAINT FK_7D3656A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7D3656A419EB6921 ON account (client_id)');
        $this->addSql('CREATE TABLE buy (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, detail CLOB NOT NULL --(DC2Type:json)
        , discount NUMERIC(4, 2) NOT NULL, iva NUMERIC(4, 2) NOT NULL, subtotal NUMERIC(4, 2) NOT NULL, total NUMERIC(6, 2) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(30) NOT NULL)');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404555E237E06 ON client (name)');
        $this->addSql('CREATE TABLE error (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, platform VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , type VARCHAR(100) NOT NULL, error CLOB NOT NULL, http_code INTEGER NOT NULL, error_code INTEGER NOT NULL, body CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , detail CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(100) NOT NULL, stock SMALLINT NOT NULL, description CLOB NOT NULL, price_old NUMERIC(4, 2) NOT NULL, price NUMERIC(4, 2) NOT NULL, image VARCHAR(255) NOT NULL, code VARCHAR(25) NOT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(20) NOT NULL)');
        $this->addSql('CREATE TABLE salt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, salt VARCHAR(8) NOT NULL, useri INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, useri_id INTEGER NOT NULL, uuid VARCHAR(255) NOT NULL, expired INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , active BOOLEAN NOT NULL, CONSTRAINT FK_D044D5D4581703C6 FOREIGN KEY (useri_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D044D5D4D17F50A6 ON session (uuid)');
        $this->addSql('CREATE INDEX IDX_D044D5D4581703C6 ON session (useri_id)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER DEFAULT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, nickname VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , active BOOLEAN NOT NULL, CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A188FE64 ON "user" (nickname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON "user" (role_id)');
        $roleId = time();
        $userId = $roleId + 972;
        $password = $_ENV['SALT'] . $_ENV['PASSWORD'];
        $encrypPassword = hash($_ENV['ALG'], $password);
        $datetime = new DateTime();
        $now = $datetime->format('Y-m-d H:i:s');
        $this->addSql("INSERT INTO role VALUES({$_ENV['ROLE_ADMIN_ID']}, 'ROLE_ADMIN'), ({$roleId}, 'ROLE_USER') ");
        $this->addSql("INSERT INTO \"user\" VALUES({$userId}, {$_ENV['ROLE_ADMIN_ID']}, 'super', 'admin', 'superAdmin', '{$_ENV['SUPER_ADMIN']}', '{$encrypPassword}', '{$now}', '{$now}', true)");
        $this->addSql("INSERT INTO salt VALUES(1, '{$_ENV['SALT']}', {$userId})");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE buy');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE error');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE salt');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE "user"');
    }
}
