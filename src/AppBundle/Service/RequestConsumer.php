<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Filesystem\Filesystem;

class RequestConsumer implements ConsumerInterface
{
    /**
     * @var Client $client
     */
    public $client;

    /**
     * @var Filesystem $filesystem
     */
    public $filesystem;

    /**
     * @var string $url
     */
    public $url;

    /**
     * ParseConsumer constructor
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->client = new Client();
        $this->filesystem = new Filesystem();
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        $word = json_decode($msg, true)['word'];

        $html = $this->client->get($this->url.'/'.$word);

        $this->filesystem->dumpFile(\AppKernel::getWebDir().'/data/htmlPages/'.$word, $html);

    }
}