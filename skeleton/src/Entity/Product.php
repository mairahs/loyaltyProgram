<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $unitary_gain;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PurchaseDate", inversedBy="products", cascade ={"persist"})
     */
    private $purchasedDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Period", inversedBy="products", cascade ={"persist"})
     */
    private $period;

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

    public function getUnitaryGain(): ?int
    {
        return $this->unitary_gain;
    }

    public function setUnitaryGain(int $unitary_gain): self
    {
        $this->unitary_gain = $unitary_gain;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPurchasedDate(): ?PurchaseDate
    {
        return $this->purchasedDate;
    }

    public function setPurchasedDate(?PurchaseDate $purchasedDate): self
    {
        $this->purchasedDate = $purchasedDate;

        return $this;
    }

    /**
     * permet d'obtenir le nombre de point généré par l'achat d'un produit en fonction de sa quantité et de son gain unitaire
     *
     * @return integer
     */
    public function getPoints()
    {
       return $this->quantity * $this->unitary_gain;
    }

    /**
     * permet d'obtenir le nombre total de point généré par des produits appartenant à la même période
     *
     * @return integer
     */
    public function getAllPointsByPeriod(ProductRepository $repository)
    {
        $totalPoints = array_reduce($repository->getAllProductsWithSamePeriod($this->period), function($total, $product){
            return $total + $product->getPoints();
        });
        return $totalPoints;
    }

    public function getPeriod(): ?Period
    {
        return $this->period;
    }

    public function setPeriod(?Period $period): self
    {
        $this->period = $period;

        return $this;
    }
}
