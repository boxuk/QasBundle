<?php

namespace BoxUK\QasBundle\Repository;

use BoxUK\QasBundle\ClientFactory\ProWebClientFactory;
use BoxUK\QasBundle\Entity\QASearch;
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

    public function findAddressesMatchingQuery(QASearch $qaSearch)
    {
        $client = $this->clientFactory->createClient();

        $results = array();

        try {
            $response = $client->DoSearch($qaSearch);
        } catch (\SoapFault $e) {
            return $results;
        }

        if ($response->QAPicklist->Total == 0) {
            return $results;
        }

        foreach ($response->QAPicklist->PicklistEntry as $entry) {
            $results[] = $entry->PartialAddress;
        }

        return $results;
    }
}
