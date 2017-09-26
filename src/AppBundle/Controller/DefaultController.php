<?php

namespace AppBundle\Controller;

use AppBundle\Document\Synonyms;
use AppBundle\Service\FileSavingService;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $url = 'https://raw.githubusercontent.com/dwyl/english-words/master/words.txt';

        $words = explode("\n", file_get_contents($url));
        $count = ceil(count($words) / 100); // only 1/100 for demo
        $words = array_slice($words, 100000, $count);

        $producer = $this->get('old_sound_rabbit_mq.request_words_producer');

        for ($i = 0; $i < count($words); $i++) {
            if ($words[$i] ==! '') {
                $producer->publish(json_encode(['word' => $words[$i]]), 'request');
            }
        }

        return new JsonResponse(['message' => 'Words are importing now into files and into mongoDB database']);
    }
}
