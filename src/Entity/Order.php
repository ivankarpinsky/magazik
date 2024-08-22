<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $amount = null;


    #[ORM\ManyToOne(targetEntity: "App\Entity\User")]
    #[ORM\JoinColumn(nullable: false, name: "user_id")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\OrderDeliveryType")]
    #[ORM\JoinColumn(nullable: false, name: "delivery_type_id")]
    private ?OrderDeliveryType $deliveryType = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\OrderStatus")]
    #[ORM\JoinColumn(nullable: false, name: "status_id")]
    private ?OrderStatus $status = null;

    #[ORM\OneToMany(targetEntity: "App\Entity\OrderItem", mappedBy: "order")]
    private Collection $orderItems;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[Pure] public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDeliveryType(): OrderDeliveryType {
        return $this->deliveryType;
    }


    public function setDeliveryType(OrderDeliveryType $deliveryType): static
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function setDeliveryTypeId(int $deliveryTypeId): static
    {
        $this->deliveryTypeId = $deliveryTypeId;
        // Вам нужно будет загружать объект OrderDeliveryType из БД и устанавливать его здесь
        // $this->deliveryType = ...;

        return $this;
    }



    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): static {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): OrderStatus {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function setStatusId(int $statusId): static
    {
        $this->statusId = $statusId;
        // Вам нужно будет загружать объект OrderStatus из БД и устанавливать его здесь
        // $this->status = ...;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function setCreatedAtNow(): static
    {
        $this->created_at = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }
}
