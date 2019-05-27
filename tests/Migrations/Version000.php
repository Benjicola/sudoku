<?php

declare(strict_types=1);

namespace Logit\Tests\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version000 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Common components';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql('CREATE TABLE ws (
            id uuid NOT NULL,
            name varchar NOT NULL,

            PRIMARY KEY (id),
            UNIQUE(name)
        );');

        $this->addSql('CREATE TABLE attribute_type (
            id uuid NOT NULL,
            type varchar NOT NULL UNIQUE,
            is_structure bool NOT NULL,

            PRIMARY KEY (id)
        )');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE attribute_type');
        $this->addSql('DROP TABLE ws');
    }
}
