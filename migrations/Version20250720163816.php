<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250720163816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Tabla user
        $this->addSql('CREATE TABLE "user" (
            id SERIAL NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user"(email)');

        // Tabla restaurant
        $this->addSql('CREATE TABLE restaurant (
            id SERIAL NOT NULL,
            user_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_EB95123FA76ED395 ON restaurant(user_id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id)');

        // Tabla messenger_messages
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGSERIAL NOT NULL,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages(queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages(available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages(delivered_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE restaurant DROP CONSTRAINT FK_EB95123FA76ED395');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

