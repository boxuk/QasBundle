<?php

namespace BoxUK\QasBundle\Tests\ClientFactory;

use BoxUK\QasBundle\ClientFactory\ProWebClientFactory;

class ProWebClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testClientCanBeCreated()
    {
        $factory = new ProWebClientFactory('http://localhost:2021/proweb.wsdl');

        $client = $factory->createClient();

        $this->assertInstanceOf("Zend\\Soap\\Client", $client);
    }
}
