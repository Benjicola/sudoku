<?php

declare(strict_types=1);

namespace Logit\Tests\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version010 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'DProps for Offer domain';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "offer_type_x_offer.attribute" (
          id uuid NOT NULL,
          name varchar NOT NULL,
          is_array boolean NOT NULL,
          is_nullable boolean NOT NULL,
          model_id uuid,
          attribute_type_id uuid NOT NULL,
          structure_id uuid,
        
          PRIMARY KEY (id),
          EXCLUDE (model_id WITH =, name WITH =) WHERE (structure_id IS NULL) DEFERRABLE INITIALLY DEFERRED,
          EXCLUDE (model_id WITH =, name WITH =) WHERE (structure_id IS NOT NULL) DEFERRABLE INITIALLY DEFERRED,
          FOREIGN KEY (model_id) REFERENCES offer_type (id) ON DELETE CASCADE,
          FOREIGN KEY (attribute_type_id) REFERENCES attribute_type (id) ON DELETE RESTRICT,
          FOREIGN KEY (structure_id) REFERENCES "offer_type_x_offer.attribute" (id) ON DELETE CASCADE
        );');

        $this->addSql('CREATE TABLE "offer_x_offer_type.value" (
          id uuid NOT NULL,
          value varchar NOT NULL,
          index int CHECK (index >= 0),
          structure_index int CHECK (index >= 0),
          entity_id uuid NOT NULL,
          model_entity_attribute_id uuid NOT NULL,
        
          PRIMARY KEY (id),
          EXCLUDE (entity_id WITH =, model_entity_attribute_id WITH =, index WITH =) WHERE (structure_index IS NULL AND index IS NOT NULL) DEFERRABLE INITIALLY DEFERRED,
          EXCLUDE (entity_id WITH =, model_entity_attribute_id WITH =, structure_index WITH =) WHERE (structure_index IS NOT NULL AND index IS NULL) DEFERRABLE INITIALLY DEFERRED,
          EXCLUDE (entity_id WITH =, model_entity_attribute_id WITH =, structure_index WITH =, index WITH =) WHERE (structure_index IS NOT NULL AND index IS NOT NULL) DEFERRABLE INITIALLY DEFERRED,
          FOREIGN KEY (entity_id) REFERENCES offer (id) ON DELETE CASCADE,
          FOREIGN KEY (model_entity_attribute_id) REFERENCES "offer_type_x_offer.attribute" (id) ON DELETE RESTRICT
        );');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "offer_x_offer_type.value"');
        $this->addSql('DROP TABLE "offer_type_x_offer.attribute"');
    }
}
