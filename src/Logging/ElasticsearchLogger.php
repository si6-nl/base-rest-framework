<?php

namespace Si6\Base\Logging;

use Monolog\Logger;
use Monolog\Handler\ElasticsearchHandler;
use Elasticsearch\ClientBuilder;
use Si6\Base\Logging\AWSElasticsearchHandler;
use Si6\Base\Logging\Formatter\ElasticsearchFormatter;

class ElasticsearchLogger
{
    public function __invoke(array $config)
    {
        $awsElasticsearchHandler = new AWSElasticsearchHandler(env('AWS_DEFAULT_REGION', 'us-west-2'));

        $elasticsearchClient = ClientBuilder::create()
            ->setHandler($awsElasticsearchHandler)
            ->setHosts($config['hosts'])
            ->setSSLVerification($config['ssl_verification'])
            ->build();

        $elasticsearchHandler = new ElasticsearchHandler(
            $elasticsearchClient,
            $config['options']
        );

        $elasticsearchHandler->setFormatter(new ElasticsearchFormatter(
            $config['index'],
            $config['type']
        ));

        return new Logger(
            $config['name'],
            [$elasticsearchHandler]
        );
    }
}