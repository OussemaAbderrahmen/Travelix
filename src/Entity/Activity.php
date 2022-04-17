<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleActivity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adressActivity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionActivity;

    /**
     * @ORM\Column(type="float")
     */
    private $priceActivity;

    /**
     * @ORM\ManyToOne(targetEntity=Categoryactivity::class, inversedBy="activities")
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $likes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleActivity(): ?string
    {
        return $this->titleActivity;
    }

    public function setTitleActivity(string $titleActivity): self
    {
        $this->titleActivity = $titleActivity;

        return $this;
    }

    public function getAdressActivity(): ?string
    {
        return $this->adressActivity;
    }

    public function setAdressActivity(string $adressActivity): self
    {
        $this->adressActivity = $adressActivity;

        return $this;
    }

    public function getDescriptionActivity(): ?string
    {
        return $this->descriptionActivity;
    }

    public function setDescriptionActivity(string $descriptionActivity): self
    {
        $this->descriptionActivity = $descriptionActivity;

        return $this;
    }

    public function getPriceActivity(): ?float
    {
        return $this->priceActivity;
    }

    public function setPriceActivity(float $priceActivity): self
    {
        $this->priceActivity = $priceActivity;

        return $this;
    }

    public function getCategory(): ?Categoryactivity
    {
        return $this->category;
    }

    public function setCategory(?Categoryactivity $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }
}
