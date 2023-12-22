<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
#[ORM\Table(name: 'armor_type')]
#[ORM\Index(columns: ['name'], name: 'armor_type_name_idx')]
class ArmorType extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: PlayerClass::class, mappedBy: 'armorTypes')]
    #[Serializer\Exclude]
    private Collection $playerClasses;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true])]
    private ?int $maxDexterityBonus = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $hasStealthPenalty = true;

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

    public function getMaxDexterityBonus(): ?int
    {
        return $this->maxDexterityBonus;
    }

    public function setMaxDexterityBonus(?int $maxDexterityBonus): self
    {
        $this->maxDexterityBonus = $maxDexterityBonus;

        return $this;
    }

    public function hasStealthPenalty(): bool
    {
        return $this->hasStealthPenalty;
    }

    public function setHasStealthPenalty(bool $hasStealthPenalty): self
    {
        $this->hasStealthPenalty = $hasStealthPenalty;

        return $this;
    }
}