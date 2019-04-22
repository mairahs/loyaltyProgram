<?php

namespace App\Entity;

use App\Entity\PurchaseDate;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeriodRepository")
 */
class Period
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="period")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    /**
     * permet d'obtenir un intervalle de date en Timestamp
     *
     * @return Timestamp
     */
    public function getInterval($start,$end)
    {
        $interval = range($this->start_at->getTimestamp(), $this->end_at->getTimestamp(), 24*60*60);
        return $interval;
    }
    
    public function getAllPoints(PurchaseDate $purchaseDate, ProductRepository $repositoryProduct)
    {
        if(in_array($purchaseDate->getPurchasedAt()->getTimestamp(), $this->getInterval($this->start_at->getTimestamp(), $this->end_at->getTimestamp()) ))
        {     
            $products = $repositoryProduct->getAllProductsWithSamePeriod($this);
            foreach($products as $product)
            {
                $allPoints = $product->getAllPointsByPeriod($repositoryProduct);
            }
            return $allPoints;
        }
    }


    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setPeriod($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getPeriod() === $this) {
                $product->setPeriod(null);
            }
        }

        return $this;
    }
}