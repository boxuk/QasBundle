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

        $response = $client->DoSearch($qaSearch);

        if ($response->QAPicklist->Total == 0) {
            return $results;
        }

        // Because QAS will return an array for multiple results and an object for a single result
        if (is_array($response->QAPicklist->PicklistEntry)) {
            foreach ($response->QAPicklist->PicklistEntry as $entry) {
                $results[] = $entry->PartialAddress;
            }
        } else if (is_object($response->QAPicklist->PicklistEntry)) {
            $results[] = $response->QAPicklist->PicklistEntry->PartialAddress;
        }

        return $results;
    }
}
