<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50);
     */
    private $name;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email();
     */
    private $email;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=300);
     */
    private $subject;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=30);
     */
    private $message;
    
    function getName(): ?string {
        return $this->name;
    }

    function getSubject(): ?string {
        return $this->subject;
    }

    function getEmail(): ?string {
        return $this->email;
    }

    function getMessage(): ?string {
        return $this->message;
    }

    function setName(?string $name): void {
        $this->name = $name;
    }

    function setSubject(?string $subject): void {
        $this->subject = $subject;
    }

    function setEmail(?string $email): void {
        $this->email = $email;
    }

    function setMessage(?string $message): void {
        $this->message = $message;
    }

}
