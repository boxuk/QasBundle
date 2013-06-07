<?php

namespace BoxUK\QasBundle\Repository;

use BoxUK\QasBundle\ClientFactory\ProWebClientFactory;
use Zend\Soap\Client;

class ProWebRepository
{
    /**
     * @var ProWebClientFactory
     */
    private $clientFactory;

    /**
     * @param ProWebClientFactory $clientFactory
     */
    public function __construct(ProWebClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function findAddressesMatchingQuery($query)
    {
        $obj = new \stdClass();
        $obj->Country = 'GBR';
        $obj->Engine = array(
            'Flatten' => true,
            'Intensity' => 'Exact',
            'Threshold' => "50",
            'Timeout' => "1",
            '_' => 'Singleline'
        );
        $obj->Layout = 'String';
        $obj->Search = $query;

        $client = $this->clientFactory->createClient();

        $response = $client->DoSearch($obj);

        var_dump($response);exit;
    }
}
