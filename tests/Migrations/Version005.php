<?php

declare(strict_types=1);

namespace Logit\Tests\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version005 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Offer Domain';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TYPE offer_class AS ENUM (\'good\', \'service\');');

        $this->addSql('CREATE TABLE offer_type (
          id uuid NOT NULL,
          name varchar NOT NULL,
          class offer_class NOT NULL,
        
          PRIMARY KEY (id),
          UNIQUE(name)
        );');


        $this->addSql('CREATE TABLE ws_offer_type (
          offer_type_id uuid NOT NULL,
          ws_id uuid NOT NULL,
        
          PRIMARY KEY (ws_id, offer_type_id),
          FOREIGN KEY (ws_id) REFERENCES ws (id),
          FOREIGN KEY (offer_type_id) REFERENCES offer_type (id)
        );');


        $this->addSql('CREATE TABLE offer (
            id uuid NOT NULL,
            offer_type_id uuid not null,
            
            PRIMARY KEY (id),
            FOREIGN KEY (offer_type_id) REFERENCES offer_type(id)
        )');

        $this->addSql('CREATE TABLE ws_offer (
          offer_id uuid NOT NULL,
          ws_id uuid NOT NULL,
        
          PRIMARY KEY (ws_id, offer_id),
          FOREIGN KEY (ws_id) REFERENCES ws (id),
          FOREIGN KEY (offer_id) REFERENCES offer (id)
        );');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ws_offer');
        $this->addSql('DROP TABLE ws_offer_type');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_type');
        $this->addSql('DROP TYPE offer_class');
    }
}
