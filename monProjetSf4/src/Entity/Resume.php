<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResumeRepository")
 */
class Resume
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
    private $titre;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="json_array")
     */
    private $limites = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAd;
    
    public function __construct() {
        $this->dateAd = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }
    
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLimites(): ?array
    {
        return $this->limites;
    }

    public function setLimites(array $limites): self
    {
        $this->limites = $limites;

        return $this;
    }
/*
    public function getPoints(): ?array
    {
        return $this->points;
    }

    public function setPoints(array $points): self
    {
        $this->points = $points;

        return $this;
    }
*/
    public function getDateAd(): ?\DateTimeInterface
    {
        return $this->dateAd;
    }

    public function setDateAd(\DateTimeInterface $dateAd): self
    {
        $this->dateAd = $dateAd;

        return $this;
    }
}
