<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Version20230929194512 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return 'Set up initial tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE armor_type (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, max_dexterity_bonus INT UNSIGNED DEFAULT NULL, has_stealth_penalty TINYINT(1) DEFAULT 1 NOT NULL, INDEX armor_type_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_classes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, primary_statistic VARCHAR(256) NOT NULL, secondary_statistic VARCHAR(256) NOT NULL, INDEX player_class_idx (name, primary_statistic, secondary_statistic), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE armor_type_player_class (player_class_id INT UNSIGNED NOT NULL, armor_type_id INT UNSIGNED NOT NULL, INDEX IDX_C157890DECD74AF0 (player_class_id), INDEX IDX_C157890DA5BF8724 (armor_type_id), PRIMARY KEY(player_class_id, armor_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_class_weapon_type (player_class_id INT UNSIGNED NOT NULL, weapon_type_id INT UNSIGNED NOT NULL, INDEX IDX_6B93EDAEECD74AF0 (player_class_id), INDEX IDX_6B93EDAE607BCCD7 (weapon_type_id), PRIMARY KEY(player_class_id, weapon_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon_type (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, number_of_dice INT UNSIGNED NOT NULL, dice_sides INT UNSIGNED NOT NULL, INDEX weapon_type_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE players (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, INDEX player_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_player_class (player_id INT UNSIGNED NOT NULL, player_class_id INT UNSIGNED NOT NULL, INDEX IDX_D20432FB99E6F5DF (player_id), INDEX IDX_D20432FBECD74AF0 (player_class_id), PRIMARY KEY(player_id, player_class_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE armor_type_player_class ADD CONSTRAINT FK_C157890DECD74AF0 FOREIGN KEY (player_class_id) REFERENCES player_classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE armor_type_player_class ADD CONSTRAINT FK_C157890DA5BF8724 FOREIGN KEY (armor_type_id) REFERENCES armor_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_class_weapon_type ADD CONSTRAINT FK_6B93EDAEECD74AF0 FOREIGN KEY (player_class_id) REFERENCES player_classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_class_weapon_type ADD CONSTRAINT FK_6B93EDAE607BCCD7 FOREIGN KEY (weapon_type_id) REFERENCES weapon_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_player_class ADD CONSTRAINT FK_D20432FB99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_player_class ADD CONSTRAINT FK_D20432FBECD74AF0 FOREIGN KEY (player_class_id) REFERENCES player_classes (id) ON DELETE CASCADE');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeStatement('INSERT INTO armor_type (name, max_dexterity_bonus, has_stealth_penalty) VALUES (:name, NULL, FALSE)', ['name' => 'Leather Armor']);
        $this->connection->executeStatement('INSERT INTO weapon_type (name, number_of_dice, dice_sides) VALUES (:name, :numberOfDice, :diceSides)', ['name' => 'Dagger', 'numberOfDice' => 1, 'diceSides' => 4]);
        $this->connection->executeStatement('INSERT INTO player_classes (name, primary_statistic, secondary_statistic) VALUES (:name, :primary, :secondary)', ['name' => 'Rogue', 'primary' => 'dexterity', 'secondary' => 'constitution']);
        $this->connection->executeStatement('INSERT INTO player_class_weapon_type (player_class_id, weapon_type_id) VALUES (:playerClass, :weaponType)', ['playerClass' => 1, 'weaponType' => 1]);
        $this->connection->executeStatement('INSERT INTO armor_type_player_class (player_class_id, armor_type_id) VALUES (:playerClass, :armorType)', ['playerClass' => 1, 'armorType' => 1]);
        parent::postUp($schema);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE player_player_class DROP FOREIGN KEY FK_D20432FB99E6F5DF');
        $this->addSql('ALTER TABLE player_player_class DROP FOREIGN KEY FK_D20432FBECD74AF0');
        $this->addSql('ALTER TABLE armor_type_player_class DROP FOREIGN KEY FK_C157890DECD74AF0');
        $this->addSql('ALTER TABLE armor_type_player_class DROP FOREIGN KEY FK_C157890DA5BF8724');
        $this->addSql('ALTER TABLE player_class_weapon_type DROP FOREIGN KEY FK_6B93EDAEECD74AF0');
        $this->addSql('ALTER TABLE player_class_weapon_type DROP FOREIGN KEY FK_6B93EDAE607BCCD7');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE player_player_class');
        $this->addSql('DROP TABLE armor_type');
        $this->addSql('DROP TABLE player_classes');
        $this->addSql('DROP TABLE armor_type_player_class');
        $this->addSql('DROP TABLE player_class_weapon_type');
        $this->addSql('DROP TABLE weapon_type');
    }
}