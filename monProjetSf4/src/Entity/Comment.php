<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     */

    protected $thread;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $score = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudonyme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;
    
    /**
     * 
     * Sets the mail of person
     * @param string
     */
    public function setMail($mail) 
    {
        $this->mail = $mail;
    }
    
    /**
     * Get the mail of person
     * 
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
    
    /**
     * Sets the pseudonyme of the comment.
     *
     * @param string $pseudonyme
     */
    public function setPseudonyme($pseudonyme)
    {
        $this->pseudonyme = $pseudonyme;
    }

    /**
     * Returns the current Pseudonyme of the comment.
     *
     * @return string
     */
    public function getPseudonyme()
    {
        return $this->pseudonyme;
    }

    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer value
     *
     * @return integer The new comment score
     */
    public function incrementScore($by = 1)
    {
        $this->score += $by;
    }

}