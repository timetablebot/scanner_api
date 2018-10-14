<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cafeteria")
 * @ORM\Entity(repositoryClass="App\Repository\CafeteriaMealRepository")
 */
class CafeteriaMeal
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Id()
     * @ORM\Column(type="boolean")
     */
    private $vegetarian;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $meal;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getVegetarian(): ?int
    {
        return $this->vegetarian;
    }

    public function setVegetarian(int $vegetarian): self
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function getMeal(): ?string
    {
        return $this->meal;
    }

    public function setMeal(string $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
