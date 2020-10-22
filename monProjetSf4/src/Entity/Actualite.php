<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActualiteRepository")
 */
class Actualite {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approbation;

    public function __construct() {

        $this->date = new \DateTime();
        $this->approbation = false;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self {
        $this->date = $date;

        return $this;
    }

    public function getTitre(): ?string {
        return $this->titre;
    }

    public function setTitre(string $titre): self {
        $this->titre = $titre;
        $this->setSlug($titre);

        return $this;
    }

    public function getMessage(): ?string {
        return $this->message;
    }

    public function setMessage(string $message): self {
        $this->message = $message;

        return $this;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }
    
    public function setSlug(string $slug): self {
        $this->slug = $slug;
        
        return $this;
    }

    public function getApprobation(): ?bool {
        return $this->approbation;
    }

    public function setApprobation(bool $approbation): self {
        $this->approbation = $approbation;

        return $this;
    }

}
