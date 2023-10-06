<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerClassRepository")
 * @ORM\Table(name="player_classes", indexes={@ORM\Index(name="player_class_idx", columns={"name", "primary_statistic", "secondary_statistic"})})
 */
class PlayerClass extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToMany(targetEntity="Player", mappedBy="playerClasses")
     * @Serializer\Exclude()
     */
    private Collection $players;

    /**
     * @ORM\ManyToMany(targetEntity="ArmorType", inversedBy="playerClasses")
     * @ORM\JoinTable(name="armor_type_player_class", joinColumns={@ORM\JoinColumn(name="player_class_id", referencedColumnName="id", onDelete="CASCADE")}, inverseJoinColumns={@ORM\JoinColumn(name="armor_type_id", referencedColumnName="id", onDelete="CASCADE")})
     * @Serializer\Exclude()
     */
    private Collection $armorTypes;

    /**
     * @ORM\ManyToMany(targetEntity="WeaponType", inversedBy="playerClasses")
     * @ORM\JoinTable(name="player_class_weapon_type", joinColumns={@ORM\JoinColumn(name="player_class_id", referencedColumnName="id", onDelete="CASCADE")}, inverseJoinColumns={@ORM\JoinColumn(name="weapon_type_id", referencedColumnName="id", onDelete="CASCADE")})
     * @Serializer\Exclude()
     */
    private Collection $weaponTypes;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private ?string $primaryStatistic = null;

    /**
     * @ORM\Column(type="string", length=256)
     */
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
}