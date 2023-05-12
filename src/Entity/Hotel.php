<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel extends User
{

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/^0|(\\+33)|(0033)[1-9][0-9]{8}/', message:" Entrez un numero valide")]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 255)]
    private ?string $managerName = null;

    #[ORM\Column(length: 255)]
    private ?string $managerFirstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/^0|(\\+33)|(0033)[1-9][0-9]{8}/', message:" Entrez un numero valide")]
    private ?string $managerPhone = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Gift::class, orphanRemoval: true)]
    private Collection $gifts;

    public function __construct()
    {
        $this->roles = ["ROLE_HOTEL"];
        $this->gifts = new ArrayCollection();
      
    }

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(string $contactPhone): self
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getManagerName(): ?string
    {
        return $this->managerName;
    }

    public function setManagerName(string $managerName): self
    {
        $this->managerName = $managerName;

        return $this;
    }

    public function getManagerFirstName(): ?string
    {
        return $this->managerFirstName;
    }

    public function setManagerFirstName(string $managerFirstName): self
    {
        $this->managerFirstName = $managerFirstName;

        return $this;
    }

    public function getManagerPhone(): ?string
    {
        return $this->managerPhone;
    }

    public function setManagerPhone(string $managerPhone): self
    {
        $this->managerPhone = $managerPhone;

        return $this;
    }

    /**
     * @return Collection<int, Gift>
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gifts->contains($gift)) {
            $this->gifts->add($gift);
            $gift->setHotel($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        if ($this->gifts->removeElement($gift)) {
            // set the owning side to null (unless already changed)
            if ($gift->getHotel() === $this) {
                $gift->setHotel(null);
            }
        }

        return $this;
    }
}
