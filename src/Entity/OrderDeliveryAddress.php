<?php

namespace App\Entity;

use App\Repository\OrderDeliveryAddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDeliveryAddressRepository::class)]
class OrderDeliveryAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Order")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\Column(nullable: true)]
    private ?int $kladr_id = null;

    #[ORM\Column(length: 4095)]
    private ?string $full_address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getKladrId(): ?int
    {
        return $this->kladr_id;
    }

    public function setKladrId(?int $kladr_id): static
    {
        $this->kladr_id = $kladr_id;

        return $this;
    }

    public function getFullAddress(): ?string
    {
        return $this->full_address;
    }

    public function setFullAddress(string $full_address): static
    {
        $this->full_address = $full_address;

        return $this;
    }
}
