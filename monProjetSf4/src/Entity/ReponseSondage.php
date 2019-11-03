<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseSondageRepository")
 */
class ReponseSondage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionSondage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbVote;
    
    public function __construct(){
        $this->nbVote = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?QuestionSondage
    {
        return $this->question;
    }

    public function setQuestion(?QuestionSondage $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getNbVote(): ?int
    {
        return $this->nbVote;
    }

    public function setNbVote(int $nbVote): self
    {
        $this->nbVote = $nbVote;

        return $this;
    }
}
