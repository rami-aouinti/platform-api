<?php

declare(strict_types=1);

namespace App\Shop\Domain\Entity;

use App\Shop\Domain\Repository\CouponsTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @package App\Shop\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[ORM\Entity(repositoryClass: CouponsTypesRepository::class)]
class CouponsTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'coupons_types', targetEntity: Coupons::class, orphanRemoval: true)]
    private ArrayCollection $coupons;

    public function __construct()
    {
        $this->coupons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupons $coupon): self
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons[] = $coupon;
            $coupon->setCouponsTypes($this);
        }

        return $this;
    }

    public function removeCoupon(Coupons $coupon): self
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getCouponsTypes() === $this) {
                $coupon->setCouponsTypes(null);
            }
        }

        return $this;
    }
}
