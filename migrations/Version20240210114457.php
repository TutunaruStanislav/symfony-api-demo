<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210114457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE api_client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE geo_city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE geo_country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_client (id INT NOT NULL, name VARCHAR(50) DEFAULT \'\' NOT NULL, access_key VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE geo_city (id INT NOT NULL, code VARCHAR(20) DEFAULT \'\' NOT NULL, title VARCHAR(100) DEFAULT \'\' NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, timezone INT DEFAULT NULL, country_code VARCHAR(3) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX city_code_index ON geo_city (code)');
        $this->addSql('CREATE INDEX city_country_code_index ON geo_city (country_code)');
        $this->addSql('CREATE INDEX city_latitude_longitude_index ON geo_city (latitude, longitude)');
        $this->addSql('CREATE TABLE geo_country (id INT NOT NULL, title VARCHAR(255) NOT NULL, code_iso VARCHAR(3) DEFAULT \'\' NOT NULL, phone_mask VARCHAR(10) DEFAULT \'\' NOT NULL, phone_length INT DEFAULT 0 NOT NULL, sort_priority INT DEFAULT 999 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX country_code_iso_index ON geo_country (code_iso)');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(20) DEFAULT \'\' NOT NULL, name VARCHAR(50) DEFAULT \'\' NOT NULL, email VARCHAR(50) DEFAULT \'\' NOT NULL, password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_username_index ON "user" (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE api_client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE geo_city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE geo_country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE api_client');
        $this->addSql('DROP TABLE geo_city');
        $this->addSql('DROP TABLE geo_country');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE "user"');
    }
}
