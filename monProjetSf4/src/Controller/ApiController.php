<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    private $knpSnappy;

    const uriMeteo = 'http://api.openweathermap.org/data/2.5/weather?lat=29.393176&lon=-9.546011&appid=56a1eb34577833c399ca0df6583a8efa&units=metric&lang=ar';
    const uriPrayer = 'http://api.aladhan.com/v1/calendar?latitude=30.427755&longitude=-9.598107';
    const uriLocalHour = 'http://worldtimeapi.org/api/timezone/Africa/Casablanca';
    const method = 'GET';


    /**
     * @Route("/meteo", name="api_meteo_index")
     */
    public function meteo()
    {
        //dump($this->get('app.meteo_api'));die;
        $aData = $this->callApiRest($this::uriMeteo, []);

        return $this->render('api/meteo.html.twig', [
            'data' => $aData['content'],
            'contentType' => $aData['contentType'],
            'status' => $aData['statusCode']
        ]);
    }

    /**
     * @Route("/prayer", name="api_prayer_index")
     */
    public function prayer()
    {

        $aData = $this->optionsPrayer();
        return $this->render('api/prayer.html.twig', [
            'data' => $aData['content']['data'],
            'contentType' => $aData['contentType'],
            'status' => $aData['statusCode']
        ]);
    }


    /**
     * @Route("/prayer/day", name="prayer_actual_day")
     */
    public function prayerDay()
    {
        $aData = $this->optionsPrayer();
        $date = new \DateTime();
        $actual_day = $date->format('d');

        return $this->render('api/prayer_day.html.twig', [
            'data' => $aData['content']['data'][$actual_day - 1],
            'contentType' => $aData['contentType'],
            'status' => $aData['statusCode'],
            'localHour' => $this->getLocalHour()
        ]);
    }

    public function getLocalHour()
    {
        $actualHour = $this->callApiRest($this::uriLocalHour, []);
        return substr($actualHour['content']['datetime'], 11, 5);
    }

    /**
     * @Route("/generatepdf/prayer",  name="generate_pdf_prayer")
     */
    public function generatePDF()
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $pdfOptions->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($pdfOptions);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $html = $this->prayer()->getContent();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $date = new \dateTime();
        $filename = "calendrier_priere_aitkermoune_" . $date->format('m-Y') . ".pdf";
        $dompdf->stream($filename, [
            "Attachment" => true
        ]);
        return new Response('OK');
    }

    public function callApiRest(string $uri, array $options)
    {

        if (array_key_exists('params', $options)) {
            foreach ($options['params'] as $key => $valeur) {
                $uri .= '&' . $key . '=' . $valeur;
            }
        }
        $client = HttpClient::create();
        $response = $client->request($this::method, $uri);
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        // $content = $response->getContent(); // typejson
        $content = $response->toArray();

        return compact("statusCode", "contentType", "content");
    }

    public function optionsPrayer()
    {
        $date = new \DateTime();
        $options = ['params' => array(
            'method' => 3,
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
            'tune' => '0,-6,-4,5,1,0,5,0,0' //Imsak, fajr, shuruq, duhr, asr, sunset, maghrib?, isha, midnight
        )];

        return $this->callApiRest($this::uriPrayer, $options);
    }
}