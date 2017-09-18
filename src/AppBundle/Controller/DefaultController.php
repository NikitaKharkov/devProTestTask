<?php

namespace AppBundle\Controller;

use AppBundle\Document\Synonyms;
use AppBundle\Service\FileSavingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $fs = new FileSavingService();
        $fs->saveContentToFile('point', '<div>Some text</div>');
        \Symfony\Component\VarDumper\VarDumper::dump('ok');
        die;
    }
}