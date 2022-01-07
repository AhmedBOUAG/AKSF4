<?php

namespace App\Service;

use App\Adapters\ApiRestInterface;
use Symfony\Component\HttpClient\HttpClient;

class ApiRestHelper implements ApiRestInterface
{

    public function call(string $uri, array $options = []) : array
    {
        if(!array_key_exists('method', $options) || !isset($options['method'])) {
            throw new \InvalidArgumentException("La methode de l'appel n'est pas définie");
        }
        
        if (array_key_exists('params', $options)) {
            foreach ($options['params'] as $key => $valeur) {
                $uri .= '&' . $key . '=' . $valeur;
            }
        }

        $client = HttpClient::create();
        $response = $client->request($options['method'], $uri);
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();

        return compact("statusCode", "contentType", "content");
    }
}