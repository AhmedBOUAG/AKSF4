<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionSondageRepository")
 */
class QuestionSondage
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
     * @ORM\Column(type="boolean")
     */
    private $approval;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;
    public function __construct(){
        $this->dateCreation = new \DateTimeImmutable();
        $this->approval = false;
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

    public function getApproval(): ?bool
    {
        return $this->approval;
    }

    public function setApproval(bool $approval = false): self
    {
        $this->approval = $approval;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
    public function __toString(){
        return $this->titre;
    }
}
