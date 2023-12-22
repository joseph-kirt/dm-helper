<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
#[ORM\Table(name: 'weapon_type')]
#[ORM\Index(columns: ['name'], name: 'weapon_type_name_idx')]
class WeaponType extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: PlayerClass::class, mappedBy: 'weaponTypes')]
    #[Serializer\Exclude]
    private Collection $playerClasses;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $numberOfDice = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $diceSides = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerClasses(): Collection
    {
        return $this->playerClasses;
    }

    public function addPlayerClass(PlayerClass $playerClass): self
    {
        if (!$this->playerClasses->contains($playerClass)) {
            $this->playerClasses[] = $playerClass;
        }

        return $this;
    }

    public function removePlayerClass(PlayerClass $playerClass): self
    {
        if ($this->playerClasses->contains($playerClass)) {
            $this->playerClasses->removeElement($playerClass);
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

    public function getNumberOfDice(): ?int
    {
        return $this->numberOfDice;
    }

    public function setNumberOfDice(?int $numberOfDice): self
    {
        $this->numberOfDice = $numberOfDice;

        return $this;
    }

    public function getDiceSides(): ?int
    {
        return $this->diceSides;
    }

    public function setDiceSides(?int $diceSides): self
    {
        $this->diceSides = $diceSides;

        return $this;
    }
}