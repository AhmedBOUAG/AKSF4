<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalityMapRepository")
 */
class LocalityMap
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Cette valeur doit être specifiée")
     * @ORM\Column(type="string", length=255)
     */
    private $localityType;

    /**
     * @Assert\NotBlank(message="Cette valeur doit être specifiée")
     * @ORM\Column(type="string", length=30)
     */
    private $picto;

    /**
     * @Assert\NotBlank(message="Cette valeur doit être specifiée")
     * @ORM\Column(type="json_array")
     */
    private $coordinated = [];

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $color;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalityType(): ?string
    {
        return $this->localityType;
    }

    public function setLocalityType(string $localityType): self
    {
        dump($localityType);
        $this->localityType = $localityType;

        return $this;
    }

    public function getPicto(): ?string
    {
        return $this->picto;
    }

    public function setPicto(string $picto): self
    {
        $this->picto = $picto;

        return $this;
    }

    public function getCoordinated(): ?array
    {
        return $this->coordinated;
    }

    public function setCoordinated(array $coordinated): self
    {
        $this->coordinated = $coordinated;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
