<?php

namespace App\Entity;

use App\Repository\PlayerClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerClassRepository::class)]
#[ORM\Table(name: 'player_classes')]
#[ORM\Index(columns: ['name'], name: 'player_class_name_idx')]
#[ORM\Index(columns: ['primary_statistic'], name: 'player_class_primary_statistic_idx')]
#[ORM\Index(columns: ['secondary_statistic'], name: 'player_class_secondary_statistic_idx')]
class PlayerClass extends BaseEntity
{
    public const STRENGTH = 'Strength';
    public const DEXTERITY = 'Dexterity';
    public const CONSTITUTION = 'Constitution';
    public const WISDOM = 'Wisdom';
    public const INTELLIGENCE = 'Intelligence';
    public const CHARISMA = 'Charisma';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Player::class, mappedBy: 'playerClasses')]
    private Collection $players;

    #[ORM\ManyToMany(targetEntity: ArmorType::class, inversedBy: 'playerClasses')]
    #[ORM\JoinTable(name: 'armor_type_player_class')]
    #[ORM\JoinColumn(name: 'player_class_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'armor_type_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $armorTypes;

    #[ORM\ManyToMany(targetEntity: WeaponType::class, inversedBy: 'playerClasses')]
    #[ORM\JoinTable(name: 'player_class_weapon_type')]
    #[ORM\JoinColumn(name: 'player_class_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'weapon_type_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $weaponTypes;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $primaryStatistic = null;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $secondaryStatistic = null;

    public function __construct(array $attributes = [])
    {
        $this->armorTypes = new ArrayCollection();
        parent::__construct($attributes);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    public function removePlayer(ArmorType $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }

    public function getArmorTypes(): Collection
    {
        return $this->armorTypes;
    }

    public function addArmorType(ArmorType $armorType): self
    {
        if (!$this->armorTypes->contains($armorType)) {
            $this->armorTypes[] = $armorType;
        }

        return $this;
    }

    public function removeArmorType(ArmorType $armorType): self
    {
        if ($this->armorTypes->contains($armorType)) {
            $this->armorTypes->removeElement($armorType);
        }

        return $this;
    }

    public function getWeaponTypes(): Collection
    {
        return $this->weaponTypes;
    }

    public function addWeaponType(WeaponType $weaponType): self
    {
        if (!$this->weaponTypes->contains($weaponType)) {
            $this->weaponTypes[] = $weaponType;
        }

        return $this;
    }

    public function removeWeaponType(WeaponType $weaponType): self
    {
        if ($this->weaponTypes->contains($weaponType)) {
            $this->weaponTypes->removeElement($weaponType);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrimaryStatistic(): ?string
    {
        return $this->primaryStatistic;
    }

    public function setPrimaryStatistic(string $primaryStatistic): self
    {
        $this->primaryStatistic = $primaryStatistic;

        return $this;
    }

    public function getSecondaryStatistic(): ?string
    {
        return $this->secondaryStatistic;
    }

    public function setSecondaryStatistic(string $secondaryStatistic): self
    {
        $this->secondaryStatistic = $secondaryStatistic;

        return $this;
    }

    public function getFormattedArmorTypes(): array
    {
        $results = [];
        $armorTypes = $this->getArmorTypes();

        /** @var ArmorType $armorType */
        foreach ($armorTypes as $armorType) {
            $results[] = $armorType->jsonSerialize();
        }

        return $results;
    }

    public function getFormattedWeaponTypes(): array
    {
        $results = [];
        $weaponTypes = $this->getWeaponTypes();

        /** @var WeaponType $weaponType */
        foreach ($weaponTypes as $weaponType) {
            $results[] = $weaponType->jsonSerialize();
        }

        return $results;
    }

    public function getFormattedPlayers(): array
    {
        $results = [];
        $players = $this->getPlayers();

        /** @var Player $player */
        foreach ($players as $player) {
            $results[] = $player->jsonSerialize();
        }

        return $results;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->getId(),
            'name'         => $this->getName(),
            'primary'      => $this->getPrimaryStatistic(),
            'secondary'    => $this->getSecondaryStatistic(),
            'armor_types'  => $this->getFormattedArmorTypes(),
            'weapon_types' => $this->getFormattedWeaponTypes(),
            'players'      => $this->getFormattedPlayers()
        ];
    }
}