<?php

namespace AppBundle\Service;

use AppBundle\Document\Synonyms;
use Psr\Log\LoggerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DomCrawler\Crawler;

class ParseConsumer implements ConsumerInterface
{
    /**
     * @var DocumentManager $dm
     */
    public $dm;

    /**
     * @var LoggerInterface $logger
     */
    public $logger;

    /**
     * ParseConsumer constructor
     * @param DocumentManager $documentManager
     * @param LoggerInterface $logger
     */
    public function __construct(DocumentManager $documentManager, LoggerInterface $logger)
    {
        $this->dm = $documentManager;
        $this->dm->getConfiguration()->setLoggerCallable(function () {});
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        try {
            list('key' => $key, 'htmlPath' => $pathToHtml) = json_decode($msg->getBody(), true);

            $crawler = new Crawler(file_get_contents($pathToHtml));
            $content = $crawler->filter('.content div#article')->html();

            $synonyms = new Synonyms();
            $synonyms->setSynonyms([$key => $content]);


            $this->dm->persist($synonyms);
            $this->dm->flush();
            $this->dm->clear();
        } catch (\Exception $exception) {
            // I think I should return true also, because I can flush html with error manually instead of trash my logs
            // In such of task I could do it in this way
            $info = [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ];
            $context = [
                'key' => $key,
                'htmlPath' => $pathToHtml
            ];
            $this->logger->error(json_encode($info), $context);
        }

        return true;
    }
}
