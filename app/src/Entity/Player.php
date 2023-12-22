<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
#[ORM\Table(name: 'players')]
#[ORM\Index(columns: ['name'], name: 'player_name_idx')]
class Player extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: PlayerClass::class, inversedBy: 'players')]
    #[ORM\JoinTable(name: 'player_player_class')]
    #[ORM\JoinColumn(name: 'player_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'player_class_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Serializer\Exclude]
    private Collection $playerClasses;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $name = null;

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
}