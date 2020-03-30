<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
/**
 * @ORM\Entity(repositoryClass="App\Repository\YoutubeVideosRepository")
 */
class YoutubeVideos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategorieYoutube")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linkYoutube;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAd;

    public function __construct() {
        $this->user = 1;
        $this->dateAd = new DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCategorie(): ?CategorieYoutube
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieYoutube $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLinkYoutube(): ?string
    {
        return $this->linkYoutube;
    }

    public function setLinkYoutube(string $linkYoutube): self
    {
        $this->linkYoutube = $linkYoutube;

        return $this;
    }

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
