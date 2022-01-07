<?php 

namespace App\EventSubscriber;

use App\Entity\LocalityMap;
use App\Entity\Resume;
use App\Event\LocalityMapEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use UnexpectedValueException;

class LocalityMapSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            LocalityMapEvent::class => [
                ['onFormatedCoordinates', 0]
            ],
            KernelEvents::REQUEST => [
                ['onCheckDataInRequest']
            ]
        ];
    }

    public function onFormatedCoordinates(LocalityMapEvent $event)
    { 
        if($event->getObject() instanceof Resume) {
            $aJsonAttribute = 'limites';
        }
        if($event->getObject() instanceof LocalityMap) {
            $aJsonAttribute = 'coordinated';
        }
        if(!$aJsonAttribute) {
            throw new UnexpectedValueException('La valeur de l\'objet à traiter est inattendu');
        }
        try{
            $localityMapObject = $event->getObject(); 
            $remove_char = array("[", "]", "\"");
            $aCoordinates = $localityMapObject->{"get".ucfirst($aJsonAttribute)}();
            $limites = str_replace($remove_char, "", $aCoordinates[0]);
            $coordinatesToArrayJson =  json_encode(array_chunk(explode(",", $limites), 2));
            $localityMapObject->{"set".ucfirst($aJsonAttribute)}((array) $coordinatesToArrayJson);
            
        } catch (\Exception $e) {
            printf('Formattage des coordonnées `LocalityMap` semble impossible à traiter. Erreur: %s', $e->getMessage());
        }
    }

    public function onCheckDataInRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        
        $isBadRequest = false;
        $errorMessage='';
        $aPathInfo = explode('/', $request->getPathInfo());
        $type_operation = end($aPathInfo);
        $aSearch = $request->request->all();

        if($type_operation === 'edit' && 
            array_key_exists('locality_map', 
                $aSearch)) {
                    $propertyAccessor = PropertyAccess::createPropertyAccessor(); 
                    
                    foreach($propertyAccessor->getValue($aSearch, '[locality_map]') as $key=>$value) {
                        if(empty(trim($value))) {
                            $isBadRequest = true;
                            $errorMessage =  "la valeur <b>$key</b> doit être définie. \n";
                        }
                    }
                    if($isBadRequest) {
                        $response = new Response();
                        $response->setContent($errorMessage);
                        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                        $event->setResponse($response);
                    }  
        }
    }

}