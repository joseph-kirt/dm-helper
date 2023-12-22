<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20231222155916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update indexes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE armor_type RENAME INDEX armor_type_idx TO armor_type_name_idx');
        $this->addSql('DROP INDEX player_class_idx ON player_classes');
        $this->addSql('CREATE INDEX player_class_name_idx ON player_classes (name)');
        $this->addSql('CREATE INDEX player_class_primary_statistic_idx ON player_classes (primary_statistic)');
        $this->addSql('CREATE INDEX player_class_secondary_statistic_idx ON player_classes (secondary_statistic)');
        $this->addSql('ALTER TABLE players RENAME INDEX player_idx TO player_name_idx');
        $this->addSql('ALTER TABLE weapon_type RENAME INDEX weapon_type_idx TO weapon_type_name_idx');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE armor_type RENAME INDEX armor_type_name_idx TO armor_type_idx');
        $this->addSql('ALTER TABLE weapon_type RENAME INDEX weapon_type_name_idx TO weapon_type_idx');
        $this->addSql('ALTER TABLE players RENAME INDEX player_name_idx TO player_idx');
        $this->addSql('DROP INDEX player_class_name_idx ON player_classes');
        $this->addSql('DROP INDEX player_class_primary_statistic_idx ON player_classes');
        $this->addSql('DROP INDEX player_class_secondary_statistic_idx ON player_classes');
        $this->addSql('CREATE INDEX player_class_idx ON player_classes (name, primary_statistic, secondary_statistic)');
    }
}