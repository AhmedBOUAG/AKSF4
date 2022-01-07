<?php 

namespace App\Event;

use App\Entity\LocalityMap;
use App\Entity\Resume;
use Symfony\Contracts\EventDispatcher\Event;

class LocalityMapEvent extends Event
{
    /** @var LocalityMap|Resume */
    private $object;
    
    /**
     * @param LocalityMap|Resume $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }


}