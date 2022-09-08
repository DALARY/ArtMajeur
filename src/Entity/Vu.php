<?php

namespace App\Entity;

use App\Repository\VuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VuRepository::class)]
class Vu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $valide = null;

    #[ORM\OneToOne(inversedBy: 'vu', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValide(): ?int
    {
        return $this->valide;
    }

    public function setValide(int $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getMessage(): ?Contact
    {
        return $this->message;
    }

    public function setMessage(Contact $message): self
    {
        $this->message = $message;

        return $this;
    }
}
