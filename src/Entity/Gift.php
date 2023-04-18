<?php

namespace App\Entity;

use App\Repository\GiftRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GiftRepository::class)]
class Gift
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $kindOfDonation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $recoveryDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $availablity = null;

    #[ORM\ManyToOne(inversedBy: 'gifts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'gifts')]
    private ?Benevole $benevole = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKindOfDonation(): ?string
    {
        return $this->kindOfDonation;
    }

    public function setKindOfDonation(string $kindOfDonation): self
    {
        $this->kindOfDonation = $kindOfDonation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRecoveryDate(): ?\DateTimeImmutable
    {
        return $this->recoveryDate;
    }

    public function setRecoveryDate(\DateTimeImmutable $recoveryDate): self
    {
        $this->recoveryDate = $recoveryDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isAvailablity(): ?bool
    {
        return $this->availablity;
    }

    public function setAvailablity(bool $availablity): self
    {
        $this->availablity = $availablity;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getBenevole(): ?Benevole
    {
        return $this->benevole;
    }

    public function setBenevole(?Benevole $benevole): self
    {
        $this->benevole = $benevole;

        return $this;
    }
}
