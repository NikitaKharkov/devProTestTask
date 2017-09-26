<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class RequestConsumer implements ConsumerInterface
{
    /**
     * @var Client $client
     */
    public $client;

    /**
     * @var string $url
     */
    public $url;

    /**
     * @var FileSavingService $fileSavingService
     */
    public $fileSavingService;

    /**
     * @var ProducerInterface $parseProducer
     */
    public $parseProducer;

    /**
     * @var ProducerInterface $parseProducer
     */
    public $logger;

    public $userAgents = [
        'Mozilla/5.0 (Windows NT',
        'Mozilla/5.0 (Macintosh',
        'Mozilla/5.0 (Linux' ,
    ];

    public $proxies = [
        '31.41.88.210:8080',
        '31.43.52.249:3129',
        '77.123.18.56:81',
        '109.200.128.225:8980',
        '193.93.216.95:8080',
        '194.126.224.253:9090',
        '77.123.18.56:81'
    ];

    /**
     * RequestConsumer constructor.
     * @param string $url
     * @param FileSavingService $fileSavingService
     * @param ProducerInterface $parseProducer
     * @param LoggerInterface $logger
     */
    public function __construct(string $url, FileSavingService $fileSavingService, ProducerInterface $parseProducer, LoggerInterface $logger)
    {
        $this->url = $url;
        $this->fileSavingService = $fileSavingService;
        $this->parseProducer = $parseProducer;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        $client = new Client(['proxy' => $this->proxies[rand(0,6)]]);
        try {
            $word = json_decode($msg->getBody(), true)['word'];
            $html = $client->get($this->url.'/'.$word, [
                'headers' =>
                    [
                        'User-Agent' => $this->userAgents[rand(0, 2)].' '.rand(1, 10000).'.'.rand(1, 10000),
                        'X-Forwarded-For' => '10.'.rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255)
                    ]
            ])->getBody()->getContents();
            $htmlPath = $this->fileSavingService->saveContentToFile($word, $html);

            $this->parseProducer->publish(json_encode(['key' => $word, 'htmlPath' => $htmlPath]), 'parse');
        } catch (\Exception $exception) {
            $info = [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ];
            $context = [
                'word' => $word
            ];
            $this->logger->error(json_encode($info), $context);
        }
        $client = null;

        return true;

    }
}
