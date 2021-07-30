<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="date")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity=FactureLine::class, mappedBy="facture")
     */
    private $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|FactureLine[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(FactureLine $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setFacture($this);
        }

        return $this;
    }

    public function removeProduct(FactureLine $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getFacture() === $this) {
                $product->setFacture(null);
            }
        }

        return $this;
    }
}
