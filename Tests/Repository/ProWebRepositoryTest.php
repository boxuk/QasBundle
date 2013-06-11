<?php

namespace BoxUK\QasBundle\Tests\Repository;

use BoxUK\QasBundle\Entity\QASearch;
use BoxUK\QasBundle\Repository\ProWebRepository;

class ProWebRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function searchResults()
    {
        $noResultsResponse = new \stdClass();
        $noResultsResponse->QAPicklist->Total = 0;

        $address1 = new \stdClass();
        $address1->PartialAddress = '1 Test Street, Town, CF11 8FG';

        $address2 = new \stdClass();
        $address2->PartialAddress = '10 Test Street, Other Town, SA14 1BY';

        $resultsResponse = new \stdClass();
        $resultsResponse->QAPicklist->Total = 2;
        $resultsResponse->QAPicklist->PicklistEntry = array($address1, $address2);

        return array (
            array ($noResultsResponse, array()),
            array ($resultsResponse, array('1 Test Street, Town, CF11 8FG', '10 Test Street, Other Town, SA14 1BY'))
        );
    }

    /**
     * @dataProvider SearchResults
     */
    public function testFindAddressesMatchingQuery($response, $expected)
    {
        $client = $this->getMockBuilder('Zend\Soap\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('DoSearch'))
            ->getMock();

        $client->expects($this->once())->method('DoSearch')->will($this->returnValue($response));

        $factory = $this->getMockBuilder('BoxUK\QasBundle\ClientFactory\ProWebClientFactory')
            ->disableOriginalConstructor()
            ->setMethods(array('createClient'))
            ->getMock();

        $factory->expects($this->once())->method('createClient')->will($this->returnValue($client));

        $repository = new ProWebRepository($factory);
        $search = new QASearch();

        $result = $repository->findAddressesMatchingQuery($search);

        $this->assertSame($expected, $result);
    }
}
