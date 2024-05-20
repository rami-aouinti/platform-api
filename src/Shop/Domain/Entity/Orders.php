<?php

declare(strict_types=1);

namespace App\Shop\Domain\Entity;

use App\Shop\Domain\Entity\Trait\CreatedAtTrait;
use App\Shop\Domain\Repository\OrdersRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Orders
 *
 * @package App\Shop\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private ?string $reference;

    #[ORM\ManyToOne(targetEntity: Coupons::class, inversedBy: 'orders')]
    private ?Coupons $coupons;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, cascade: ['persist'], orphanRemoval: true)]
    private ArrayCollection $ordersDetails;

    public function __construct()
    {
        $this->ordersDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCoupons(): ?Coupons
    {
        return $this->coupons;
    }

    public function setCoupons(?Coupons $coupons): self
    {
        $this->coupons = $coupons;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): self
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails[] = $ordersDetail;
            $ordersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): self
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getOrders() === $this) {
                $ordersDetail->setOrders(null);
            }
        }

        return $this;
    }
}
